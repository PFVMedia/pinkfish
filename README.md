# Pink Fish

A modern, content-managed marketing and CMS website built with Laravel 13, Inertia.js, and Vue 3. Pink Fish is the public-facing site and content platform for **Pink Fish Ventures Inc.**, a web development company — but the codebase is structured as a general-purpose starting point for any business that needs a polished marketing site backed by a real admin panel.

---

## What this app is for

Pink Fish gives you a complete, production-ready website with these capabilities out of the box:

- A **public marketing site** with homepage, about, services, tools, links, blog, and contact pages
- A **block-based page builder** so non-developers can compose new pages without touching code
- A **full-featured admin panel** for managing every piece of content
- **Built-in authentication** including 2FA, password reset, and email verification
- **Animated, themeable backgrounds** that adapt to your brand color
- **Live, no-deploy site configuration** — change the logo, navigation, footer, color palette, and background style from the admin

It is suited to:
- Small agency or consultancy websites that need a blog, services pages, and a contact form
- Any business that wants editable marketing pages without a developer in the loop
- Teams that want a Laravel + Vue starting point with admin, auth, and content management already wired up

---

## Public site

Visitors get a fast, single-page-app feel powered by Inertia.js with these pages:

| Page | Purpose |
|------|---------|
| `/` Home | Hero, featured content, latest blog posts |
| `/about` | Company story (editable copy) |
| `/services` | Services offered (editable copy) |
| `/tools` | Curated tools the company uses or recommends |
| `/links` | Categorized link directory |
| `/blog` | Blog index with published posts |
| `/blog/{slug}` | Individual blog post |
| `/contact` | Contact form, throttled (3 submissions/min), Cloudflare Turnstile spam protection |
| `/privacy` | Privacy policy (editable) |
| `/disclaimer` | Disclaimer (editable) |

All pages share a common layout featuring:
- Responsive top navigation with mobile menu
- Configurable footer with column-based link groups
- A live-themed parallax animated background that follows the chosen brand color
- Dark-mode support throughout

---

## Animated backgrounds

Two interchangeable background styles ship with the app, both using pure CSS (no WebGL, no extra dependencies):

- **Orbs** — nine soft, glowing spheres of varying size that drift, orbit, and respond to scroll and mouse movement.
- **Cubes** — nine true-3D cubes built with `transform-style: preserve-3d`, each rotating slowly on multiple axes with the same parallax behavior.

Both backgrounds tint themselves to the active brand color and offer per-element hue variation for depth. Switching between them is a one-click change in the admin — no rebuild required.

---

## Admin panel

Available at `/admin`, built on **Filament v5**. Admin users (`is_admin = true`) can:

### Manage content
- **Blog posts** — title, slug, body (rich text), publish status, publish date
- **Pages** — slug-routed pages composed from a block library (see below)
- **Tools** — name, description, URL, ordering
- **Links** — title, URL, description, category, ordering
- **Documents** — title, body, category (categorized via document categories)
- **Document categories** — name, slug, ordering

### Build pages with the block builder
The block-builder lets editors assemble pages from a set of pre-styled, configurable blocks. Each block has a Filament form on the back-end and a matching Vue component on the front-end. Available blocks:

- **Hero** — badge, heading, subtitle, multiple CTA buttons with style variants
- **Rich Text** — full WYSIWYG editor (Tiptap)
- **Card Grid** — heading + responsive grid of cards (icon, title, description, optional link)
- **Stats Panel** — heading + a row of label/value statistics with icons
- **Workflow** — step-by-step process diagram with badges and headings
- **Call to Action** — focused CTA section with button
- **Contact Form** — embeddable contact form
- **Latest Blog Posts** — auto-pulled list of recent posts
- **Model List** — list view of any registered content type
- **Image** — single image with optional caption
- **Page Header** — large header section for top of pages

Add or remove blocks per-page, reorder them, and publish — no code change needed for content updates.

### Configure the site
A dedicated **Site Settings** page in the admin lets you change globally:

- **Site name** and **logo text**
- **Brand tagline** and **copyright text**
- **Main navigation** — repeatable list of `{name, href}` items
- **CTA button** — text and URL shown in the header
- **Footer columns** — repeatable column groups with their own link lists
- **Color palette** — choose from indigo, blue, sky, teal, emerald, amber, orange, rose, pink, purple, or slate. The choice live-injects CSS custom properties so every primary-coloured element on the site re-themes immediately, including the parallax background.
- **Background style** — orbs or cubes

Changes take effect on the next page load (the settings cache is invalidated automatically on save).

---

## Authentication & user accounts

Powered by **Laravel Fortify** with full headless auth UI written in Vue:

- Login, registration, logout
- Email verification
- Password reset by email
- Password confirmation for sensitive actions
- **Two-factor authentication (TOTP)** with QR-code setup and recovery codes
- Login throttling and password update throttling

Logged-in users get:
- A **dashboard** at `/dashboard`
- A **profile page** at `/settings/profile` — edit name and email, delete account
- A **security page** at `/settings/security` — change password, manage 2FA
- An **appearance page** at `/settings/appearance` — light/dark/system mode

---

## Tech stack

- **Backend** — Laravel 13, PHP 8.5, MySQL 8.4, Redis (optional)
- **Admin** — Filament 5
- **Auth** — Laravel Fortify 1.x with two-factor authentication
- **Frontend** — Inertia 3 + Vue 3 + TypeScript, Tailwind CSS 4, Vite 8
- **Editing** — Tiptap rich text editor
- **UI primitives** — reka-ui (shadcn-vue style)
- **Typed routes** — Laravel Wayfinder generates TypeScript helpers from Laravel routes
- **Spam protection** — Cloudflare Turnstile on the contact form
- **Email** — Mailpit for local dev; pluggable mailer for production
- **Testing** — Pest 4 with Laravel plugin
- **Local dev** — Laravel Sail (Docker)
- **Code quality** — Laravel Pint, ESLint, Prettier, vue-tsc

---

## Quick start

```bash
git clone <repo-url> pinkfish && cd pinkfish
cp .env.example .env

./vendor/bin/sail up -d
./vendor/bin/sail composer install
./vendor/bin/sail npm install
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm run dev
```

Visit:
- **App** — http://localhost
- **Admin** — http://localhost/admin (login: `admin@pinkfishventures.com` / `password`)
- **Mailpit** — http://localhost:8025

For detailed setup, architecture, conventions, and instructions on extending the app, see **[BUILD.md](./BUILD.md)**.

---

## What you can build with it

Because the content layer is fully driven by editable settings, blocks, and resources, you can re-skin Pink Fish for almost any business website:

- Replace `siteSettings` (logo, palette, copy) and the site immediately feels like a different brand.
- Compose homepage and inner pages entirely from blocks — no rebuild.
- Add custom block types when you need something the library doesn't cover.
- Add new admin resources (`php artisan make:filament-resource ...`) to manage any model.
- Wire new public pages by adding a controller, a route, and a Vue page; the layout, theming, navigation, and background carry through automatically.

In short: it's a content-driven Laravel CMS with an admin a non-developer can actually use, plus a Vue front-end that designers and developers can extend without fighting the framework.

---

## License

Released under the MIT License. See [LICENSE](./LICENSE) for the full text.
