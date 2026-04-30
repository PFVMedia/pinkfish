# Pink Fish — Build Guide

A complete, reproducible guide to building this application from scratch. Written so an AI assistant (or a developer) can recreate the project end-to-end.

---

## 1. What this app is

Pink Fish is a Laravel 13 + Inertia v3 + Vue 3 marketing/CMS site with:

- A **public site** (homepage, about, services, tools, blog, links, contact, privacy, disclaimer) rendered via Inertia + Vue.
- A **Filament v5 admin panel** at `/admin` for managing blog posts, pages (block-based content builder), tools, links, documents, and global site settings.
- **Fortify v1** headless authentication (login, registration, password reset, email verification, 2FA).
- **Wayfinder** for typed TypeScript routes/controller actions.
- **Tailwind v4** styling, **Pest 4** tests, **Sail** for local Docker dev.
- A swappable **animated parallax background** (orbs or 3D CSS cubes).

---

## 2. Tech stack & versions

| Layer | Tool | Version |
|------|------|--------|
| Runtime | PHP | 8.5 (also supports ^8.3) |
| Framework | Laravel | ^13.0 |
| Admin | Filament | ^5.0 |
| Auth | Laravel Fortify | ^1.34 |
| SPA bridge | Inertia (Laravel + Vue) | ^3.0 |
| Typed routes | Laravel Wayfinder | ^0.1.14 |
| AI | laravel/ai | ^0.4.4 |
| Captcha | ryangjchandler/laravel-cloudflare-turnstile | ^3.0 |
| Frontend | Vue | ^3.5 |
| Styling | Tailwind CSS | ^4.1 |
| Bundler | Vite | ^8 |
| Editor | Tiptap | ^3.22 |
| UI primitives | reka-ui | ^2.6 |
| Icons | lucide-vue-next | ^0.468 |
| Testing | Pest | ^4.4 |
| Formatter | Laravel Pint | ^1.27 |
| Local dev | Laravel Sail | ^1.53 |

---

## 3. Project layout

```
app/
  Actions/Fortify/        # CreateNewUser, ResetUserPassword
  Filament/
    Blocks/PageBlocks.php # Filament block builder definitions for Pages
    Pages/                # Filament admin pages (e.g. ManageSiteSettings)
    Resources/            # Filament CRUD: BlogPosts, Pages, Tools, Links, Documents, DocumentCategories
  Http/
    Controllers/
      Settings/           # Profile, Security (password, 2FA)
      Site/               # Public-site controllers (one per page)
    Middleware/HandleInertiaRequests.php
  Models/                 # User, BlogPost, Page, ContentBlock, Tool, Link, Document, DocumentCategory, SiteSetting
  Providers/
    AppServiceProvider.php
    FortifyServiceProvider.php
    Filament/AdminPanelProvider.php
config/                   # Standard Laravel + fortify.php, turnstile.php
database/
  factories/              # One per model
  migrations/             # See section 6
  seeders/                # DatabaseSeeder, PageSeeder, SiteSettingsSeeder
resources/
  css/app.css             # Tailwind v4 entry
  js/
    app.ts                # Inertia bootstrap
    layouts/SiteLayout.vue
    layouts/auth/
    pages/
      Site/               # Public pages (Home, About, Services, Tools, Links, Blog/, Contact, Privacy, Disclaimer)
      auth/               # Login, Register, ForgotPassword, ResetPassword, VerifyEmail, ConfirmPassword, TwoFactorChallenge
      settings/           # Profile, Security, Appearance
      Dashboard.vue
      Welcome.vue
    components/
      ParallaxOrbs.vue    # Background option A (radial-gradient circles)
      ParallaxCubes.vue   # Background option B (CSS 3D cubes with preserve-3d)
      blocks/             # Page-builder block components (Hero, RichText, CardGrid, Stats, Workflow, CTA, ContactForm, BlogLatest, ModelList, Image, PageHeader, BlockRenderer)
      ui/                 # reka-ui-based primitives
    actions/, routes/     # Wayfinder-generated (do not hand-edit)
routes/
  web.php                 # Public site routes
  settings.php            # Profile/security routes (auth)
  console.php
tests/
  Feature/                # AdminAuth, AdminBlog, Auth/, Dashboard, Settings/, SitePage
  Unit/
phpunit.xml, pint.json, eslint.config.js, tsconfig.json, vite.config.ts
compose.yaml              # Sail services: laravel.test, mysql 8.4, redis, mailpit
```

---

## 4. Prerequisites

- **PHP 8.5** (8.3+ works) with extensions: pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json, bcmath, fileinfo, gd, redis (optional)
- **Composer 2.x**
- **Node.js 20+** and **npm 10+**
- **Docker** + Docker Compose (for Sail) — or a local MySQL 8.4 + Redis if not using Sail
- **Git**

---

## 5. Bootstrap from scratch

If recreating the project on a new machine from this repo:

```bash
git clone <repo-url> pinkfish && cd pinkfish

cp .env.example .env
# Edit .env — see section 5.1

composer install
npm install

php artisan key:generate
php artisan migrate --seed
npm run build
```

For development:

```bash
# Option A: Sail (Docker)
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm run dev

# Option B: native, all-in-one (requires local MySQL/Redis or sqlite)
composer run dev
# spawns: php artisan serve, queue:listen, pail (logs), npm run dev
```

### 5.1 `.env` — required values

The `.env.example` ships with **sqlite** as the default database. For Sail, switch to mysql:

```env
APP_NAME="Pink Fish"
APP_URL=http://localhost
APP_KEY=                       # generated by `artisan key:generate`

DB_CONNECTION=mysql
DB_HOST=mysql                  # "mysql" inside Sail; "127.0.0.1" if running native against local MySQL
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

CACHE_STORE=database           # or redis
QUEUE_CONNECTION=database      # or redis
SESSION_DRIVER=database

MAIL_MAILER=log                # dev: Sail uses mailpit on :8025

# Cloudflare Turnstile (contact form). Use Cloudflare's test keys for dev:
TURNSTILE_SITE_KEY=1x00000000000000000000AA
TURNSTILE_SECRET_KEY=1x0000000000000000000000000000000AA
```

---

## 6. Database schema (migration order)

Run with `php artisan migrate`. Existing migrations:

1. `0001_01_01_000000_create_users_table` — users, password_reset_tokens, sessions
2. `0001_01_01_000001_create_cache_table`
3. `0001_01_01_000002_create_jobs_table`
4. `2025_08_14_170933_add_two_factor_columns_to_users_table` — Fortify 2FA columns
5. `2026_04_07_000222_add_is_admin_to_users_table` — boolean `is_admin`
6. `2026_04_07_000226_create_blog_posts_table` — id, title, slug (unique), body (longText/HTML), published_at, is_published, timestamps
7. `2026_04_07_000226_create_content_blocks_table` — singleton key/value content store: key (unique), label, type (text|html), value (longText)
8. `2026_04_07_000227_create_documents_table` — title, slug, body, category, sort_order
9. `2026_04_07_000227_create_links_table` — title, url, description, category, sort_order
10. `2026_04_07_000227_create_tools_table` — name, description, url, sort_order
11. `2026_04_08_025408_create_pages_table` — slug (unique), title, blocks (json — Filament block builder), is_published, timestamps
12. `2026_04_08_025409_create_site_settings_table` — group, key (unique), value (json)
13. `2026_04_11_034035_create_document_categories_table` — name, slug, sort_order
14. `2026_04_11_034038_update_documents_table_use_category_fk` — replace string `category` with `document_category_id` FK

Use `php artisan make:migration ...` to add new ones. Always create the matching factory and seeder when adding a model.

---

## 7. Models

All in `app/Models/`:

- `User` — Fortify-aware: `HasFactory`, `Notifiable`, `TwoFactorAuthenticatable`. Casts `email_verified_at` and `two_factor_confirmed_at` to datetime. `is_admin` boolean. `admin()` factory state.
- `BlogPost` — `is_published` boolean, `published_at` datetime, slug-routed. `factory()->create(['is_published' => true])`.
- `Page` — `slug`, `blocks` JSON cast (array), `is_published`. Backs Filament block-builder pages.
- `ContentBlock` — singleton key/value/type/label store used for editable copy across the site.
- `Tool`, `Link`, `Document`, `DocumentCategory` — straightforward CRUD with `sort_order`.
- `SiteSetting` — `group`, `key`, `value` JSON cast. Read everywhere via shared Inertia prop (see section 9).

---

## 8. Routing

### Public (`routes/web.php`)
```
GET  /                  HomeController
GET  /about             AboutController
GET  /services          ServicesController
GET  /tools             ToolsController
GET  /links             LinksController
GET  /blog              BlogController@index
GET  /blog/{slug}       BlogController@show     (route-model-bound on slug)
GET  /contact           ContactController@show
POST /contact           ContactController@send  (throttle:3,1)
GET  /privacy           PrivacyController
GET  /disclaimer        DisclaimerController
```

Auth-required: `GET /dashboard` (Inertia `Dashboard`).

### Settings (`routes/settings.php`, auth)
- `GET/PATCH /settings/profile`
- `DELETE /settings/profile` (verified)
- `GET /settings/security`, `PUT /settings/password` (throttle:6,1)
- `GET /settings/appearance`

### Auth — provided by Fortify
Standard Fortify routes: `/login`, `/register`, `/forgot-password`, `/reset-password/{token}`, `/email/verify`, `/user/confirm-password`, `/two-factor-challenge`, etc. Frontends live under `resources/js/pages/auth/`.

### Admin
Filament panel at `/admin` (id `admin`, brand "Pink Fish Admin", indigo primary, dark mode enabled). Login is the standard Filament screen. Access is gated by `is_admin` on the user model — see `User::canAccessPanel()` (or equivalent) in the AdminPanelProvider.

---

## 9. Inertia integration

`app/Http/Middleware/HandleInertiaRequests.php` shares globally:

```php
'name' => config('app.name'),
'auth' => ['user' => $request->user()],
'sidebarOpen' => /* cookie-based */,
'flash' => ['success' => ..., 'error' => ...],
'turnstileSiteKey' => config('turnstile.site_key'),
'siteSettings' => fn () => Cache::remember('site_settings', 3600, fn () =>
    SiteSetting::pluck('value', 'key')->toArray()
),
```

`siteSettings` is a flat key/value map consumed by `SiteLayout.vue` and any page that needs branding / nav / footer / background config. **Always `Cache::forget('site_settings')` after writing settings** (the Filament page does this in `save()`).

Currently shared `siteSettings` keys:
- `site_name`, `logo_text`, `brand_tagline`, `copyright_text`
- `main_nav` (array of `{name, href}`)
- `cta_text`, `cta_url`
- `footer_columns` (array of `{title, links: [{name, href}]}`)
- `color_palette` (one of indigo/blue/sky/teal/emerald/amber/orange/rose/pink/purple/slate)
- `background_style` (`orbs` | `cubes`)

---

## 10. Frontend architecture

- Entry: `resources/js/app.ts` boots Inertia with the Vue 3 plugin.
- Pages live in `resources/js/pages/...`; the path matches `Inertia::render('Site/Home')`.
- Layouts: `SiteLayout.vue` (public marketing chrome), `AuthSimpleLayout.vue` (login/register), settings layout for the authed `/settings/*` pages.
- `SiteLayout.vue` handles:
  - Navigation, mobile menu, footer (driven by shared `siteSettings`).
  - **Color palette injection**: writes CSS custom properties (`--primary`, `--ring`, …) on `<html>` based on `color_palette` and dark-mode class.
  - **Background switch**: renders `<ParallaxCubes />` if `siteSettings.background_style === 'cubes'`, else `<ParallaxOrbs />`.
- Components:
  - `components/ParallaxOrbs.vue` — 9 radial-gradient circles, hue-rotate, scroll + mouse parallax, three orbit keyframes. Pure CSS.
  - `components/ParallaxCubes.vue` — 9 true-3D cubes (`transform-style: preserve-3d`, six face divs each), parallax + slow X/Y/Z rotation. Pure CSS.
  - `components/blocks/` — one component per Filament block type (Hero, RichText, CardGrid, StatsPanel, Workflow, Cta, ContactForm, BlogLatest, ModelList, Image, PageHeader). `BlockRenderer.vue` switches on block type to render an array of blocks.
  - `components/ui/` — reka-ui primitives wrapped per shadcn-vue conventions.
- **Tailwind v4** configured via `@tailwindcss/vite` — no `tailwind.config.js`; theme tokens are defined in CSS via `@theme` in `resources/css/app.css`.
- **Wayfinder** generates typed route helpers into `resources/js/actions/` and `resources/js/routes/`. They are regenerated on `npm run build` / `npm run dev` via `@laravel/vite-plugin-wayfinder`. Use them in TS instead of hand-rolled URLs.

---

## 11. Filament admin

Provider: `app/Providers/Filament/AdminPanelProvider.php`. Key config:

```php
->id('admin')
->path('admin')
->login()
->brandName('Pink Fish Admin')
->colors(['primary' => Color::Indigo])
->darkMode()
->discoverResources(in: app_path('Filament/Resources'), ...)
->discoverPages(in: app_path('Filament/Pages'), ...)
->pages([Dashboard::class])
```

### Resources (CRUD)
`app/Filament/Resources/`: `BlogPosts`, `Pages`, `Tools`, `Links`, `Documents`, `DocumentCategories`. Each follows the standard Filament v5 layout (`Resource.php` + `Pages/{List,Create,Edit}*.php`). Pages use the **block builder** via `Builder::make('blocks')` with the block schemas from `app/Filament/Blocks/PageBlocks.php`.

### Custom pages
`app/Filament/Pages/ManageSiteSettings.php` — a single-form page (no resource) that reads/writes the `site_settings` table by key. Backed by `resources/views/filament/pages/manage-site-settings.blade.php`. Save buttons are rendered top and bottom of the form.

To add a new site setting:
1. Add the key with a default in `mount()`'s `$this->form->fill([...])`.
2. Add the form field in `form(Schema $schema)`.
3. Add the row in `save()`'s `$settings` array (group + key).
4. Add a default in `database/seeders/SiteSettingsSeeder.php`.
5. Consume it in Vue from `usePage().props.siteSettings.<key>`.

### Block builder (`app/Filament/Blocks/PageBlocks.php`)
Returns an array of `Builder\Block` definitions. Block types currently: `hero`, `rich_text`, `card_grid`, `stats_panel`, `workflow`, `cta`, `contact_form`, `blog_latest`, `model_list`, `image`, `page_header`. Each has its own schema and a corresponding Vue component under `resources/js/components/blocks/`.

### Admin access
Only users with `is_admin = true` can access `/admin`. The seeded default admin is `admin@pinkfishventures.com` (password `password` — change in any non-dev environment).

---

## 12. Authentication (Fortify)

- `app/Providers/FortifyServiceProvider.php` registers Fortify view callbacks → Inertia pages.
- `app/Actions/Fortify/CreateNewUser.php` — registration validation + create.
- `app/Actions/Fortify/ResetUserPassword.php` — password rules.
- 2FA columns are on `users` (`two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`).
- TwoFactorChallenge / Confirmation flow has Vue pages under `resources/js/pages/auth/`.
- Profile + security settings pages live under `/settings/*` (see `routes/settings.php`).

---

## 13. Seeders & dev data

`database/seeders/DatabaseSeeder.php` orchestrates:
1. Seeded admin user `admin@pinkfishventures.com` (password `password`, `is_admin=true`).
2. `seedContentBlocks()` — homepage + about/services/contact/privacy/disclaimer copy as `ContentBlock` rows.
3. `seedBlogPosts()` — three published posts.
4. `seedLinks()` — Laravel/Vue/Tailwind/etc.
5. `seedTools()` — Forge, Vapor, Pest, Vite, TablePlus, Ray.
6. `PageSeeder` — page content for the block-builder.
7. `SiteSettingsSeeder` — site_name, logo_text, brand_tagline, copyright_text, color_palette (`indigo`), background_style (`orbs`), main_nav, cta_text, cta_url, footer_columns.

Run with `php artisan migrate --seed` or `php artisan db:seed`.

---

## 14. Build & dev commands

### Composer scripts (`composer.json`)
```bash
composer setup       # install + .env + key:generate + migrate + npm install + npm run build
composer dev         # concurrent: artisan serve, queue:listen, pail, npm run dev
composer test        # config:clear + lint:check + artisan test
composer lint        # pint --parallel
composer lint:check  # pint --parallel --test
composer ci:check    # lint:check + format:check + types:check + test
```

### npm scripts (`package.json`)
```bash
npm run dev          # vite (HMR + Wayfinder + Inertia + Tailwind)
npm run build        # vite build
npm run build:ssr    # build + ssr build
npm run lint         # eslint . --fix
npm run lint:check   # eslint .
npm run format       # prettier --write resources/
npm run format:check # prettier --check resources/
npm run types:check  # vue-tsc --noEmit
```

### Sail
```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan ...
./vendor/bin/sail npm ...
./vendor/bin/sail artisan test --compact
```
Mailpit dashboard: http://localhost:8025. App: http://localhost.

### Pint formatting
After any PHP change: `vendor/bin/pint --dirty --format agent`.

---

## 15. Testing

Pest 4 with the Laravel plugin. Tests live in `tests/Feature` (most cases) and `tests/Unit`. Existing files:

- `tests/Feature/SitePageTest.php` — every public page returns 200 + Inertia prop assertions.
- `tests/Feature/AdminAuthTest.php`, `AdminBlogTest.php` — admin panel access + CRUD.
- `tests/Feature/Auth/*` — Fortify flows.
- `tests/Feature/Settings/*` — profile/security flows.
- `tests/Feature/DashboardTest.php`.

### Conventions
- Run with `php artisan test --compact` (or `sail artisan test --compact`).
- Filter: `php artisan test --compact --filter='descriptive name fragment'`.
- Use factories with states (`User::factory()->admin()->create()`), not manual model creation.
- Prefer Pest `it()`/`expect()` syntax. Use `assertInertia(fn ($page) => $page->where('prop.path', 'value'))` for Inertia prop assertions.
- The phpunit `.env` overrides set `DB_DATABASE=testing`. The Sail MySQL container creates this DB automatically via `vendor/laravel/sail/database/mysql/create-testing-database.sh`. **Tests must be run inside Sail** if your app DB host is `mysql` (the container name).

---

## 16. Adding common things — recipes

### A new public page
1. Controller: `php artisan make:controller --no-interaction Site/FooController --invokable`.
2. Render via `Inertia::render('Site/Foo', [...])`.
3. Vue page: `resources/js/pages/Site/Foo.vue`, wrap with `<SiteLayout>`.
4. Route in `routes/web.php`: `Route::get('/foo', FooController::class)->name('foo');`.
5. Add a feature test in `tests/Feature/SitePageTest.php`: `it('loads the foo page', fn () => $this->get('/foo')->assertSuccessful());`.
6. Run: `php artisan test --compact --filter='foo'`, `vendor/bin/pint --dirty --format agent`, `npm run dev`.

### A new Filament resource
1. `php artisan make:filament-resource ModelName --generate --no-interaction`.
2. Wire fields in the resource's `form()` and columns in `table()`.
3. Make sure the matching model has a factory + migration. Add a feature test.

### A new site setting
See section 11 → "To add a new site setting". Don't forget to invalidate the `site_settings` cache on save.

### A new page-builder block
1. Add a `Builder\Block` to `app/Filament/Blocks/PageBlocks.php` with its schema.
2. Create the matching `resources/js/components/blocks/FooBlock.vue`.
3. Register it in `BlockRenderer.vue`'s switch.
4. Test by editing a page in the admin.

---

## 17. Conventions the AI must follow

These are enforced by `CLAUDE.md`:

- **Activate skills** when relevant: `laravel-best-practices`, `wayfinder-development`, `pest-testing`, `inertia-vue-development`, `tailwindcss-development`, `fortify-development`.
- **Boost MCP first** for Laravel docs/db/log inspection (`search-docs`, `database-query`, `database-schema`, `browser-logs`, `last-error`, `read-log-entries`).
- **Always test** every change. Run the minimum set: `php artisan test --compact --filter=...`.
- **Always run Pint** after PHP edits: `vendor/bin/pint --dirty --format agent`.
- **Prefer Wayfinder route helpers** in TS over hardcoded URLs; import from `@/actions/` and `@/routes/`.
- **PHP 8 conventions**: constructor property promotion, explicit return types, typed parameters, curly braces always, PHPDoc array shapes.
- **Vue components**: single root element; activate `inertia-vue-development` for Vue work.
- **Don't create docs** unless explicitly asked (this file was explicitly requested).
- **Don't change dependencies** without approval.
- **Don't create new top-level directories** without approval.

---

## 18. Recreating from zero (greenfield)

If starting truly from nothing without this repo:

```bash
# 1. Fresh Laravel + Sail
composer create-project laravel/laravel pinkfish
cd pinkfish
php artisan sail:install --with=mysql,redis,mailpit
./vendor/bin/sail up -d

# 2. Inertia + Vue starter (provides app.ts, layouts, ui primitives)
composer require laravel/breeze --dev
php artisan breeze:install vue --typescript --pest --ssr
# OR start with the laravel/vue-starter-kit as this project does.

# 3. Core packages
./vendor/bin/sail composer require \
  filament/filament:^5.0 \
  inertiajs/inertia-laravel:^3.0 \
  laravel/fortify:^1.34 \
  laravel/wayfinder:^0.1.14 \
  laravel/ai:^0.4.4 \
  ryangjchandler/laravel-cloudflare-turnstile:^3.0

./vendor/bin/sail composer require --dev \
  laravel/boost:^2.0 \
  laravel/pail:^1.2.5 \
  pestphp/pest:^4.4 \
  pestphp/pest-plugin-laravel:^4.1

# 4. Frontend deps
./vendor/bin/sail npm install \
  @inertiajs/vue3 @inertiajs/vite \
  @tailwindcss/vite tailwindcss \
  @laravel/vite-plugin-wayfinder \
  @tiptap/vue-3 @tiptap/starter-kit @tiptap/extension-link \
  @vueuse/core class-variance-authority clsx tailwind-merge tw-animate-css \
  reka-ui lucide-vue-next vue-input-otp

# 5. Install Filament panel + Fortify
./vendor/bin/sail artisan filament:install --panels --no-interaction
./vendor/bin/sail artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"

# 6. Configure
#    - vite.config.ts: register laravel(), inertia(), tailwindcss(), vue(), wayfinder({formVariants:true})
#    - resources/css/app.css: @import "tailwindcss"; define @theme tokens
#    - app/Providers/FortifyServiceProvider.php: register view callbacks → Inertia pages
#    - app/Providers/Filament/AdminPanelProvider.php: brand, colors, dark mode, discover paths
#    - app/Http/Middleware/HandleInertiaRequests.php: share auth, flash, siteSettings, turnstileSiteKey

# 7. Build out models, migrations, factories, seeders per sections 6, 7, 13.
# 8. Build out controllers, Vue pages, Filament resources per sections 8, 10, 11.
# 9. Tests per section 15.
```

---

## 19. Useful URLs (local)

| Service | URL |
|---------|-----|
| App | http://localhost |
| Admin panel | http://localhost/admin |
| Vite dev server | http://localhost:5173 |
| Mailpit | http://localhost:8025 |
| MySQL | localhost:3306 (sail/password) |
| Redis | localhost:6379 |

Default seeded admin: `admin@pinkfishventures.com` / `password`.

---

## 20. Troubleshooting

- **"Vite manifest" error** in browser → run `npm run dev` (or `npm run build`) inside Sail.
- **DB connection refused on `mysql` host** → you're running native; either start Sail (`sail up -d`) or change `DB_HOST=127.0.0.1` and run a local MySQL.
- **Filament panel shows 403** → user lacks `is_admin=true`; update via tinker or seed.
- **`siteSettings` change not visible** → `Cache::forget('site_settings')` (the admin page does this on save).
- **Wayfinder types out of date** → restart `npm run dev` so the Vite plugin regenerates `resources/js/actions/` and `resources/js/routes/`.
- **2FA recovery codes not regenerating** → ensure the user has `two_factor_secret` set; Fortify only regenerates on confirmed users.
