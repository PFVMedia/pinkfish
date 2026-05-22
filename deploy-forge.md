# Deploying to Laravel Forge (DigitalOcean) with Inertia SSR

Notes for migrating this app from shared hosting to a DigitalOcean droplet provisioned by Laravel Forge, with Inertia SSR enabled.

## Project SSR status

Already wired up — no code changes needed:

- `@inertiajs/vite` plugin in `vite.config.ts` (handles the SSR entry automatically).
- `npm run build:ssr` builds both client + SSR bundles into `bootstrap/ssr/`.
- `config/inertia.php` has SSR enabled at `127.0.0.1:13714`.
- `resources/js/app.ts` uses `createInertiaApp` (SSR-compatible).

## Droplet sizing

1 GB is the practical floor with Node SSR + PHP-FPM + MySQL co-resident. The $6 (1 GB) plan works; $12 (2 GB) gives breathing room.

## Forge one-time setup

1. **Provision** the droplet via Forge with PHP 8.5 + MySQL 8 + Nginx.
2. **Node version**: Server → Meta, set Node to 20 or 22 (Vite 8 requires ≥ 20).
3. **Database**: Server → Database, create `pinkfish` DB and a user. Forge wires the credentials into the site's `.env`.
4. **Site → Meta → Daemons → New Daemon**:
   - Command: `php artisan inertia:start-ssr`
   - Directory: `/home/forge/<yourdomain.com>`
   - User: `forge`
   - Processes: `1`
   Forge wraps this in supervisor and restarts on failure.
5. **Site → Environment** — ensure:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   APP_KEY=<copied from shared host .env so existing encrypted data still decrypts>

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pinkfish
   DB_USERNAME=forge
   DB_PASSWORD=<from Forge DB UI>
   ```
   SSR URL defaults to `127.0.0.1:13714` from `config/inertia.php` — no env var needed.

## Deploy script

Paste into Site → Deployments → Deploy Script:

```bash
cd /home/forge/$FORGE_SITE_USER_DOMAIN
git pull origin $FORGE_SITE_BRANCH

$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Frontend: client + SSR bundles
npm ci
npm run build:ssr

# Laravel
( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9>/tmp/fpmlock

$FORGE_PHP artisan migrate --force
$FORGE_PHP artisan config:cache
$FORGE_PHP artisan route:cache
$FORGE_PHP artisan view:cache
$FORGE_PHP artisan event:cache

# Bounce SSR so the new bundle is loaded (supervisor restarts it)
$FORGE_PHP artisan inertia:stop-ssr
```

## Data migration (MySQL → MySQL)

### On the shared host

```bash
mysqldump --single-transaction --quick --default-character-set=utf8mb4 \
  --no-tablespaces \
  -u <user> -p <dbname> > pinkfish.sql
```

- `--single-transaction` avoids locking the live site during the dump (InnoDB only).
- `--no-tablespaces` avoids a `PROCESS` privilege error common on shared hosting.
- Skip `--routines` / `--triggers` unless you have any.

### On the Forge droplet

```bash
# upload pinkfish.sql via scp, then:
mysql -u forge -p pinkfish < pinkfish.sql
```

### Gotchas

1. **MySQL version skew**: shared host is often MySQL 5.7; Forge installs MySQL 8 by default. Older → newer direction is safe. The reverse (8 → 5.7) can break on `utf8mb4_0900_ai_ci` collations.
2. **Schema vs Laravel migrations**: the deploy script runs `migrate --force`. **Do not run it again after the import.** Two safe options:
   - **Preferred**: let the first deploy run migrations (creates empty tables), then import the dump — `mysqldump`'s default `--add-drop-table` will replace the empty tables with real data.
   - **Alternative**: import first, comment out `migrate --force` for the initial deploy, re-enable after.
3. **`APP_KEY`**: copy the existing `APP_KEY` from the shared host's `.env` into Forge's `.env`. If it changes, all encrypted columns and existing sessions/cookies break.
4. **Uploaded files**: if the site stores uploads under `storage/app/public`, rsync that directory over, then confirm `php artisan storage:link` has run (Forge's default deploy does this).

## Verifying SSR is working

After the first deploy:

```bash
# On the droplet
curl -s https://yourdomain.com/ | grep -o 'data-page' | head -1
```

If SSR is rendering, the initial HTML will contain rendered page markup (not just an empty `<div id="app" data-page="..."></div>`). You can also check the daemon status in Forge → Site → Daemons — `inertia:start-ssr` should show as running.

If SSR isn't responding, the app falls back to client-only render automatically; check `storage/logs/laravel.log` for connection errors to `127.0.0.1:13714`.
