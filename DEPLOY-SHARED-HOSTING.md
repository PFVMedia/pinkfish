# Deploying Pink Fish to Shared Hosting

This guide walks through deploying the app to a typical cPanel / Plesk / DirectAdmin shared host (e.g. Hostinger, Namecheap, SiteGround, A2, Bluehost, GoDaddy). Shared hosting puts real constraints on Laravel apps — no Docker, often no SSH, restricted PHP, and a fixed `public_html/` document root — so the steps below work around those.

If your host gives you SSH access, follow the **with-SSH** path where it's offered. If you only have File Manager / FTP and a phpMyAdmin tab, follow the **no-SSH** path.

---

## 1. Check your host meets the requirements

Before you start, log into your hosting control panel and verify:

| Requirement | Why |
|---|---|
| **PHP 8.3 or newer** (8.5 ideal) | Laravel 13 requires it. Most cPanel hosts have a "Select PHP Version" tool. |
| **PHP extensions enabled**: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd`, `curl`, `intl` | Standard Laravel requirements. |
| **MySQL 8.0+** (or MariaDB 10.6+) | Older versions may fail on JSON columns / migrations. |
| **MySQL database created** with a user attached | Note hostname (often `localhost`), DB name, user, password. |
| **At least 256 MB PHP memory_limit** | Composer install + Filament can need this. |
| **`exec`, `proc_open`, `symlink`** functions enabled | Required for `php artisan storage:link` and queues. Many budget hosts disable some of these — see workarounds below. |
| **HTTPS / Let's Encrypt** | Required for 2FA QR codes to render reliably and for Turnstile. |

If your host blocks `proc_open` or `symlink` outright, you can still deploy — the workarounds in section 7 cover this.

---

## 2. Build the app locally first

Shared hosts almost never have Node.js, so you must build the frontend assets on your laptop and upload the compiled output.

On your local machine, in the project root:

```bash
# 1. Install everything
composer install --no-dev --optimize-autoloader
npm ci

# 2. Build production assets
npm run build

# 3. (Optional) regenerate Wayfinder TypeScript helpers
#    `npm run build` does this automatically via the Vite plugin.

# 4. Make sure storage and bootstrap/cache are writable
chmod -R 775 storage bootstrap/cache
```

After this completes you should have:
- `vendor/` — production-only PHP dependencies
- `public/build/` — Vite-built JS/CSS with manifest
- `public/hot` should **not** exist (delete it if it does — Vite leaves it after `npm run dev`)

---

## 3. Prepare a deployment archive

Create a zip excluding things you don't want on the server:

```bash
zip -r pinkfish.zip . \
  -x 'node_modules/*' \
  -x '.git/*' \
  -x 'tests/*' \
  -x '.env' \
  -x 'storage/logs/*' \
  -x 'storage/framework/cache/*' \
  -x 'storage/framework/sessions/*' \
  -x 'storage/framework/views/*' \
  -x '*.log' \
  -x 'compose.yaml' \
  -x 'README.md' \
  -x 'BUILD.md' \
  -x 'DEPLOY-SHARED-HOSTING.md'
```

Keep `.env.example` in the archive — you'll copy it to `.env` on the server.

---

## 4. Upload and lay out the files

Shared hosts serve traffic from a fixed directory like `~/public_html/` or `~/httpdocs/`. **Do not put the entire Laravel app in there** — only the contents of Laravel's `public/` folder should be web-accessible.

Recommended layout (the safe pattern):

```
/home/<user>/
├── pinkfish/             ← entire Laravel app (uploaded zip extracted here)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── public/           ← contents copied/linked into public_html
│   └── ...
└── public_html/          ← what the web sees
    ├── build/            ← from public/
    ├── favicon.ico
    ├── robots.txt
    ├── .htaccess         ← from public/
    └── index.php         ← from public/, edited per below
```

### Steps via File Manager (no SSH)

1. Upload `pinkfish.zip` to `~/` (your home directory, **above** `public_html/`).
2. Use the host's File Manager → "Extract" to unzip into `~/pinkfish/`.
3. Move (or copy) **the contents of** `~/pinkfish/public/` into `~/public_html/`.
4. Open `~/public_html/index.php` and change the two paths that point back to the app root:

```php
// Before
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// After
require __DIR__.'/../pinkfish/vendor/autoload.php';
$app = require_once __DIR__.'/../pinkfish/bootstrap/app.php';
```

5. Make sure `~/public_html/.htaccess` was uploaded (some File Managers hide dotfiles — toggle "show hidden files").

### Steps via SSH

```bash
ssh <user>@<host>
cd ~
unzip pinkfish.zip -d pinkfish
cp -R pinkfish/public/. public_html/
# Edit index.php as shown above
```

---

## 5. Configure `.env`

On the server, copy `.env.example` to `~/pinkfish/.env`. Edit:

```env
APP_NAME="Your Site Name"
APP_ENV=production
APP_KEY=                              # generate next step
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=daily
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=<your_cpanel_db>
DB_USERNAME=<your_cpanel_user>
DB_PASSWORD=<your_cpanel_password>

CACHE_STORE=database
QUEUE_CONNECTION=database
SESSION_DRIVER=database
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=<your-host's-smtp>
MAIL_PORT=587
MAIL_USERNAME=<smtp-user>
MAIL_PASSWORD=<smtp-pass>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"

# Cloudflare Turnstile (contact form). Sign up free at https://dash.cloudflare.com
TURNSTILE_SITE_KEY=<your-real-site-key>
TURNSTILE_SECRET_KEY=<your-real-secret-key>
```

> Setting `CACHE_STORE=database`, `QUEUE_CONNECTION=database`, and `SESSION_DRIVER=database` means you don't need Redis, which most shared hosts don't offer.

### Generating `APP_KEY`

**With SSH:**
```bash
cd ~/pinkfish
php artisan key:generate
```

**Without SSH:** generate a key locally and paste it in:
```bash
# On your laptop
php artisan key:generate --show
# Copy output (starts with "base64:") into APP_KEY in the server's .env
```

---

## 6. Set up the database

### With SSH (preferred)

```bash
cd ~/pinkfish
php artisan migrate --force
php artisan db:seed --force      # creates default admin + sample content
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Without SSH

Most shared hosts include a "PHP Cron" or "Scheduled Task" panel and a "Terminal" or "Cron" entry that can run a one-off command. If you have any of those, use:

```
/usr/bin/php /home/<user>/pinkfish/artisan migrate --force
/usr/bin/php /home/<user>/pinkfish/artisan db:seed --force
```

If you have **none** of those, the fallback is to:

1. Locally point `.env` at the production DB (open the remote MySQL port if your host allows it, or tunnel via cPanel "Remote MySQL").
2. Run `php artisan migrate --force` and `php artisan db:seed --force` from your laptop.
3. Restore your local `.env` to its dev values.

> **Always** change the seeded admin password (`admin@pinkfishventures.com` / `password`) immediately after first login. See section 9.

---

## 7. Storage symlink

Laravel needs `public/storage` to point at `storage/app/public` for uploaded files. The standard command is:

```bash
php artisan storage:link
```

If you used the `public_html` redirection in section 4, the symlink target is wrong by default. Either:

- **SSH path:** create the symlink manually:
  ```bash
  ln -s ~/pinkfish/storage/app/public ~/public_html/storage
  ```
- **No SSH / `symlink()` disabled:** in File Manager, create a folder `~/public_html/storage` and either:
  - copy uploaded files there manually, or
  - configure the filesystem disk in `config/filesystems.php` to write directly to `public_html/storage`, or
  - use a S3-compatible disk (DigitalOcean Spaces, Backblaze B2) — set `FILESYSTEM_DISK=s3` and the AWS_* env vars.

---

## 8. Set up the scheduler and queue worker

Laravel's queue (used for verification emails, jobs) and scheduler (used for any scheduled commands) both need a recurring trigger. Shared hosts always offer cron jobs — add **one** cron entry:

```
* * * * * /usr/bin/php /home/<user>/pinkfish/artisan schedule:run >> /dev/null 2>&1
```

If you also want background queue processing (recommended so contact-form emails don't block the request), add:

```
* * * * * /usr/bin/php /home/<user>/pinkfish/artisan queue:work --stop-when-empty --tries=3 >> /dev/null 2>&1
```

Some hosts cap a cron at 1 minute minimum and kill long-running scripts — `--stop-when-empty` keeps the worker honest.

---

## 9. First login & lockdown

1. Visit `https://your-domain.com/admin`.
2. Log in with the seeded admin (`admin@pinkfishventures.com` / `password`) **or** with whichever admin user you created.
3. Go to **Settings → Profile** and **Settings → Security** to change the email and password.
4. Enable two-factor authentication.
5. In the Filament admin, open **Site Settings** and set your real site name, logo text, tagline, navigation, and footer.
6. Replace any seeded blog posts, tools, links, and pages with your real content.

---

## 10. File permissions

After uploading:

```bash
# Folders Laravel writes to need group-write
chmod -R 775 ~/pinkfish/storage ~/pinkfish/bootstrap/cache

# Everything else
find ~/pinkfish -type f -exec chmod 644 {} \;
find ~/pinkfish -type d -exec chmod 755 {} \;
```

If you see "permission denied" errors writing logs or cache, your host probably needs the writable folders to be `777` instead — try that as a last resort.

---

## 11. Updating the app

The simplest pattern on shared hosting is rebuild-locally / reupload-changed-files:

```bash
# 1. Local
git pull
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# 2. Upload changed files via FTP/File Manager
#    At minimum: app/, bootstrap/, config/, database/, public/build/, resources/, routes/, vendor/

# 3. On the server (SSH), or via a cron one-shot
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

For larger sites, switch to a Git-based deploy: clone the repo on the server (in `~/pinkfish/`), then `git pull` + the artisan steps above. Many cPanel hosts include a "Git Version Control" feature that automates this.

---

## 12. Common shared-hosting issues

| Symptom | Fix |
|---|---|
| **500 error after deploy** | Tail `storage/logs/laravel.log` (File Manager → View). Most often: missing `APP_KEY`, wrong `index.php` paths, or a cached config still pointing at dev DB. Run `php artisan config:clear` (or delete `bootstrap/cache/config.php`). |
| **"The Mix manifest does not exist" / "Unable to locate file in Vite manifest"** | You forgot to upload `public/build/` or didn't run `npm run build` locally. |
| **Database connection refused** | Confirm `DB_HOST=localhost` (most cPanel hosts) and that the DB user is attached to the database with all privileges. |
| **`storage:link` errors / no images load** | `symlink()` disabled — see section 7 alternatives. |
| **2FA QR codes don't render** | Your host is on PHP without `gd` or `imagick`, **or** the page isn't HTTPS. Enable both. |
| **Filament admin shows 403** | The user record must have `is_admin = true`. Update via phpMyAdmin: `UPDATE users SET is_admin = 1 WHERE email = 'you@example.com';` |
| **Contact form silently fails** | Check `MAIL_*` settings; many shared hosts require SMTP auth on port 587 with their own SMTP server. Tail the log. |
| **Cron commands don't run** | Use the absolute PHP path your host requires, e.g. `/opt/cpanel/ea-php83/root/usr/bin/php` instead of `/usr/bin/php`. cPanel's cron interface usually has a dropdown for the right binary. |
| **`exec`/`proc_open` disabled** | Some Composer commands and `queue:work` need them. If your host disables them entirely, run Composer locally and only upload `vendor/`, and skip the queue worker. |

---

## 13. Going further

When the site outgrows shared hosting (slow admin, frequent timeouts, can't run a real queue worker), the natural upgrade path is:

- **Laravel Forge + a $6-12/mo VPS** (DigitalOcean, Hetzner, Vultr) — proper SSH, Redis, supervisord queue workers, zero-downtime deploys, auto SSL.
- **Laravel Cloud** or **Laravel Vapor** (managed) — no servers to manage at all.
- **Ploi.io** — Forge alternative, often cheaper.

The codebase is identical on any of these; only the deploy mechanics change.

---

For full local-dev setup, architecture details, and conventions, see [BUILD.md](./BUILD.md).
