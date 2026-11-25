# Prompt Antigravity — Build Complete Editorial Blog + Filament CMS

Act as a senior Laravel engineer and frontend designer. Build a complete, production-minded editorial / photography blog website based on the reference image provided in this conversation.

## Confirmed decisions
- Admin dashboard: Filament (not a custom Vue admin).
- Visual direction: follow the reference image — dark editorial / photography magazine, minimal, image-led, strong typography, spacious layout.
- Scope: complete website with CMS.
- Backend: Laravel 12.
- Admin CMS: Filament 5 Panel Builder.
- Public frontend: Vue 3 + Inertia.js.
- Styling: Tailwind CSS 4.1+.
- Database: MySQL.
- Local development environment: Windows + Laragon.
- Project folder target: `D:\laragon\www\blog-website`.

Read and follow `AGENTS.md` before making changes. If it does not exist, create it using the project's requirements and then continue.

## First: inspect, do not assume
1. Inspect the repository, `composer.json`, `package.json`, `.env.example`, Laravel version, PHP version, Node version, existing routes, migrations, and frontend setup.
2. Determine whether the project is empty, partially built, or already contains working features.
3. Do not overwrite existing code or delete files blindly.
4. If the project is not yet created, guide the setup for Laravel 12 and Vue + Inertia. If creating it yourself is possible in the current environment, create it in the specified folder; otherwise provide the exact commands for the user.
5. Confirm Filament 5 compatibility with installed PHP/Laravel/Tailwind versions using the official documentation. Do not guess package APIs.

## Installation requirements
For a new Laravel 12 project, use the Laravel installer and select the Vue starter kit / Inertia option if available. Configure MySQL in `.env`.

Install Filament Panel Builder using the PowerShell-compatible command when appropriate:
```powershell
composer require filament/filament:"~5.0"
php artisan filament:install --panels
php artisan make:filament-user
```
Do not run `make:filament-user` with a weak or hardcoded production password. Explain how the owner creates the first admin account locally.

Ensure the public Vue/Inertia frontend and Filament admin assets coexist correctly. Filament 5 requires Tailwind CSS 4.1+; do not downgrade Tailwind to v3. If the installed starter kit or dependencies conflict, stop and explain the exact conflict before applying a migration or major version change.

## Product requirements

### Public website
Create:
- `/` homepage
- `/blog` article archive with pagination
- `/blog/{slug}` article detail
- `/category/{slug}` category archive
- `/tag/{slug}` tag archive if tags are implemented
- `/gallery` gallery archive
- `/gallery/{slug}` gallery detail
- `/about` and `/contact` pages
- custom 404 page

Homepage should be modular and database-driven:
1. Hero / masthead with editable title, subtitle, and background image.
2. Featured article.
3. Editorial intro / profile section.
4. Optional shop / projects section (only if enabled and configured).
5. “Speech for the Pride” or equivalent editorial article grid; use neutral placeholder copy, not copied reference text.
6. Additional article grid / selected stories.
7. Gallery image strip.
8. Footer with editable text and social links.

The public design should:
- Use charcoal / near-black backgrounds and off-white text.
- Use photography as the main visual element.
- Have a strong editorial hierarchy and carefully controlled whitespace.
- Use a restrained accent color only if it improves the design.
- Use responsive image ratios, responsive typography, and mobile navigation.
- Avoid generic SaaS styling, excessive rounded cards, bright gradients, and unnecessary shadows.
- Include hover/focus states, accessible navigation, semantic HTML, and alt text.
- Include SEO metadata and Open Graph data for articles.
- Use lazy loading for non-critical images and pagination for archives.
- Use graceful placeholders when no image/content is configured.

### Admin CMS at `/admin`
Build Filament resources and dashboard widgets for:
- Posts
- Categories
- Tags
- Media library
- Galleries and gallery images (including image ordering)
- Pages
- Homepage sections
- Site settings

Posts must support:
- title, unique slug, excerpt/description, rich content, category, multiple tags, featured image, draft/published/archived status, published date, author.
- Preview where practical.
- Validation and slug generation, with manual slug editing.
- Public visibility only when status is published and publication date is valid.

Media must support:
- Upload image, preview, alt text, MIME type, file size, path, upload owner.
- Accept safe image types such as JPEG, PNG, and WebP.
- Enforce maximum file size and validate actual MIME type.
- Store files in Laravel Storage; never store image binary data in MySQL.
- Never delete an image file if it is still referenced by a post, gallery, setting, or homepage section.

Galleries must support:
- title, slug, description, draft/published status, publication date.
- Multiple images, captions, and explicit sort order.

Homepage sections must support:
- title, unique key, type, description, active/inactive, sort order, settings JSON.
- Strict allowlist of section types and validation of each settings shape.
- Drag-and-drop ordering if supported by the installed Filament version.

Site settings must support:
- site name, site description, logo, favicon, footer text, contact email, Instagram, YouTube, and other social URLs.
- Use a controlled settings form; do not expose arbitrary executable values.

### Database
Implement these tables with consistent UUID primary keys (`$table->uuid('id')->primary()`) and matching `foreignUuid()` foreign-key types:
1. users (extend Laravel users table with role admin/editor; no public registration)
2. categories
3. tags
4. media
5. posts (author, optional category, optional featured media, title, slug, excerpt, content, status, published_at, soft deletes)
6. post_tag (unique post_id + tag_id)
7. galleries
8. gallery_media (gallery_id, media_id, sort_order, caption; unique gallery_id + media_id)
9. pages
10. site_settings (unique key, value, optional type)
11. homepage_sections (unique section_key, type, description, settings JSON, sort_order, is_active)

Create migrations in dependency order. Use appropriate indexes, foreign-key deletion rules, model relationships, casts, factories, and seeders. Do not use `migrate:fresh` or `db:wipe` unless the user explicitly approves after being told that all data will be destroyed.

### Authorization and security
- No public admin registration.
- Enforce Filament panel access on the server side.
- Admin can manage all CMS content and site settings.
- Editor can manage editorial content and media but cannot manage users or global settings.
- Use policies/gates and validate all inputs.
- Sanitize rich HTML before public rendering or use a safe editor output strategy.
- Escape plain text.
- Protect login with rate limiting.
- Keep drafts and unpublished pages private.
- Keep credentials in `.env`; never commit secrets.

## Required build order
Work in these phases and do not skip ahead:

### Phase 0 — Audit and plan
Inspect project and environment. Report exact versions and a short implementation plan. Do not modify files until the audit is complete.

### Phase 1 — Project foundation
Set up Laravel 12, Vue 3 + Inertia, Tailwind 4.1+, MySQL, and Filament 5. Confirm public page renders and `/admin` login works.

### Phase 2 — Database and models
Create migrations, models, relationships, factories, seeders, casts, and policies. Run migrations against the intended local database only. Verify foreign keys.

### Phase 3 — Filament CMS
Implement panel authorization, dashboard, resources, forms, tables, filters, media uploads, gallery ordering, homepage section management, and site settings.

### Phase 4 — Public frontend
Build responsive homepage and all public routes. Fetch data from database through Laravel controllers and Inertia. Do not hardcode article data in Vue components.

### Phase 5 — Integration and tests
Verify changes made in admin appear on public pages. Test CRUD, roles, uploads, slugs, draft visibility, publication dates, gallery ordering, and responsive layouts. Run test suite and `npm run build`.

### Phase 6 — Documentation and deployment preparation
Write README with installation, `.env` configuration, database setup, storage link, first admin creation, test commands, build commands, and deployment notes. Do not deploy or alter hosting without explicit instruction.

## Quality gates
- No placeholder buttons that do nothing.
- No broken image paths.
- No N+1 query patterns on article/gallery pages; eager-load relationships.
- Use pagination.
- Add loading, empty, validation, and error states.
- Keep controllers thin and follow Laravel conventions.
- Keep Filament resources aligned with Filament 5 APIs.
- Run migrations, tests, and production build; report actual output.
- Do not mark a phase complete until its quality gates pass.

## How to work with me
I am developing on Windows using Laragon and Antigravity. Explain commands in PowerShell syntax. Work incrementally. After each phase, summarize:
- What you completed.
- Files created or changed.
- Commands I need to run.
- Test/build results.
- Any issue requiring my decision.

Start with Phase 0 only: inspect the project and environment, then report findings and the exact next commands. Do not generate the entire application in one uncontrolled pass.
