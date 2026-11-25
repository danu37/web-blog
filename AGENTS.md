# AGENTS.md — Blog Website

## 1. Project goal

Build a complete editorial / photography blog website inspired by the reference image supplied by the user.

The public website should feel like a dark, minimal, image-led editorial magazine:
- Near-black / charcoal backgrounds.
- Large photography and strong visual hierarchy.
- Compact uppercase or editorial typography where appropriate.
- Carefully spaced article grids.
- Responsive layout for desktop, tablet, and mobile.
- Avoid generic SaaS dashboard styling on the public website.
- Do not copy logos, text, or copyrighted assets from the reference. Use original placeholder content and replaceable images.

The site must include a CMS so an authorized admin can change text, descriptions, images, article order, gallery content, and homepage sections without editing source code.

## 2. Approved technology stack

- Backend: Laravel 12
- Language: PHP 8.2+
- Database: MySQL 8+
- Admin CMS: Filament 5 Panel Builder
- Public frontend: Vue 3 + Inertia.js
- Styling: Tailwind CSS 4.1+
- Build tool: Vite
- Rich text editor: use a maintained editor compatible with the chosen frontend; prefer TipTap if needed.
- Image storage: Laravel Filesystem / Storage (store file paths and metadata in MySQL, never image binaries).
- Testing: Pest or PHPUnit, following the test framework already installed.

Important:
- Filament 5 requires PHP 8.2+, Laravel 11.28+, and Tailwind CSS 4.1+. Laravel 12 satisfies the Laravel requirement.
- Do not downgrade Tailwind to v3 when using Filament 5.
- Keep Filament's admin panel and the public Vue/Inertia frontend as separate presentation areas in the same Laravel application.
- Do not create a separate REST API unless a real integration requires it. Use Laravel routes/controllers/Inertia for the public site and Filament resources for CMS operations.

## 3. Working rules for the coding agent

1. Inspect the existing repository before changing anything.
2. If the repository already contains code, preserve working features and conventions. Do not overwrite or delete files blindly.
3. Before installing or upgrading dependencies, inspect `composer.json`, `package.json`, PHP version, and installed package versions.
4. Use official documentation matching the installed major versions. Do not invent APIs or use outdated Filament syntax.
5. Work in small, verifiable phases. After each phase, run relevant tests and build commands.
6. Do not run destructive commands such as `migrate:fresh`, `db:wipe`, or commands that delete user uploads unless the user explicitly approves.
7. Never expose `.env`, passwords, API keys, database credentials, or private user data.
8. Never commit `.env`, vendor, node_modules, build artifacts unless the repository already intentionally tracks them.
9. Do not create fake success states. Report commands that failed and explain the actual blocker.
10. Do not leave TODO placeholders for core requested functionality.
11. Keep code readable, follow Laravel conventions, use Form Requests / validation, policies, Eloquent relationships, and transactions where appropriate.
12. Do not add dependencies unless they solve a clear requirement. Explain any new package.

## 4. Core product scope

### Public pages
- Homepage
- Article listing / blog archive
- Article detail page
- Category archive
- Tag archive (optional but implement if tags are included)
- Gallery listing
- Gallery detail
- About page
- Contact page (basic contact information; do not add email sending unless configured)
- Custom 404 page

### Homepage sections
The homepage should be assembled from admin-managed content:
- Hero / masthead
- Featured article
- Intro / editorial profile section
- Selected shop / projects section (if configured)
- Article or editorial grid
- Gallery strip
- Footer

Sections must be enabled/disabled and reordered in admin. The public frontend must render only active sections in their configured order. Avoid hardcoding the reference image's sample text.

### Admin CMS
Use Filament at `/admin`.
- Secure login; no public registration.
- Dashboard with useful counts: published posts, drafts, categories, media items, galleries.
- CRUD resources for posts, categories, tags, media, galleries, pages, and homepage sections.
- Site settings management for site name, description, logo, favicon, footer, and social links.
- Article editor with title, slug, excerpt, rich content, category, tags, featured image, status, and publication date.
- Media library with upload, preview, alt text, file size, MIME type, and safe deletion.
- Gallery editor with multiple images and explicit ordering.
- Homepage section manager with active toggle, order, title, description, type, and JSON settings only where justified.
- Use Filament forms, tables, filters, actions, notifications, and relation managers where appropriate.

## 5. Database design

Use UUID primary keys (`$table->uuid('id')->primary()`) consistently for all related tables. All foreign keys must use `$table->foreignUuid(...)` with matching UUID types.

### Required tables

1. `users`
   - name, email (unique), password, role (`admin` or `editor`), avatar nullable, timestamps.
   - Keep Laravel's standard authentication columns if present.
   - No public registration.
   - Admin-only panel access must be enforced server-side, not just hidden in the UI.

2. `categories`
   - name, slug (unique), description nullable, timestamps.

3. `tags`
   - name, slug (unique), timestamps.

4. `media`
   - user_id nullable FK to users, disk, file_name, file_path, mime_type, file_size, alt_text nullable, timestamps.
   - Store actual files in Laravel Storage; store only metadata/path in DB.

5. `posts`
   - user_id FK to users, category_id nullable FK to categories, featured_image_id nullable FK to media,
   - title, slug (unique), excerpt nullable, content LONGTEXT,
   - status (`draft`, `published`, `archived`), published_at nullable, timestamps, soft deletes.
   - Public queries must show only published posts whose published_at is null or not in the future, according to the chosen publishing rule. Prefer requiring published_at for published content and document that rule consistently.

6. `post_tag`
   - post_id FK, tag_id FK, timestamps if useful, unique composite index on post_id + tag_id.

7. `galleries`
   - title, slug (unique), description nullable, status (`draft`, `published`), published_at nullable, timestamps, soft deletes.

8. `gallery_media`
   - gallery_id FK, media_id FK, sort_order unsigned integer default 0, caption nullable, unique composite index gallery_id + media_id.

9. `pages`
   - title, slug (unique), content LONGTEXT nullable, status (`draft`, `published`), timestamps, soft deletes.

10. `site_settings`
   - key unique, value LONGTEXT nullable, type nullable, timestamps.
   - Validate known settings in application code. Do not treat arbitrary stored values as executable code.

11. `homepage_sections`
   - title, section_key unique, type, description nullable, settings JSON nullable,
   - sort_order unsigned integer default 0, is_active boolean default true, timestamps.
   - Define a strict allowlist of supported section types and validate settings per type.

### Database implementation rules
- Create migrations in dependency order: users (existing Laravel migration), categories, tags, media, posts, post_tag, galleries, gallery_media, pages, site_settings, homepage_sections.
- Add indexes for status, published_at, sort_order, and common lookup fields where useful.
- Use `restrictOnDelete`, `cascadeOnDelete`, or `nullOnDelete` intentionally and document the choice.
- Avoid polymorphic relationships unless a real requirement justifies them.
- Use factories and seeders for demo content, but never seed a default weak password in production.
- Add a unique constraint to all slugs and settings keys.
- Use model casts for JSON, booleans, dates, and enums where appropriate.

## 6. Authorization and security

- Use Laravel policies / gates for content operations.
- `admin` can manage users, site settings, all content, media, and homepage sections.
- `editor` can create and edit posts, categories/tags if permitted by policy, and upload/select media; editor cannot manage users or global site settings.
- Enforce Filament panel access in the User model / panel authorization hook.
- Validate all uploaded files by MIME type, extension, and maximum size. Allow only safe raster image formats needed by the site (for example JPEG, PNG, WebP); SVG uploads should be disabled unless sanitized handling is implemented.
- Generate safe filenames; do not trust the original filename as a path.
- Escape plain text output. Render rich HTML only through a safe editor/rendering strategy and sanitize untrusted HTML.
- Protect forms with CSRF and use Laravel's built-in authentication/session protections.
- Add rate limiting to login and contact endpoints.
- Never expose draft posts, private media paths, or admin-only data to public pages.
- Prevent mass assignment by using explicit validation and fillable/guarded rules.

## 7. Media handling

- Use Laravel Storage with a configurable disk.
- Store uploaded files under organized paths such as `media/YYYY/MM/`.
- Create the public storage symlink using `php artisan storage:link`.
- Use responsive image dimensions and lazy loading where suitable.
- Keep alt text editable and use it in public image markup.
- When replacing or deleting media, check whether it is referenced by posts, galleries, site settings, or homepage sections. Do not delete referenced files automatically.
- Handle failed database writes by cleaning up newly uploaded orphan files safely.
- Do not store binary image data in MySQL.

## 8. Public frontend quality

- Match the supplied reference's visual direction, not its exact content.
- Use a consistent design system: colors, spacing, typography, image ratios, container widths, and breakpoints.
- Use semantic HTML, accessible labels, keyboard-friendly navigation, visible focus states, and sufficient contrast.
- Make layouts responsive from small mobile screens upward.
- Add SEO title, meta description, canonical URL, Open Graph image, and structured article metadata where appropriate.
- Use pagination for article archives.
- Add empty states and graceful fallbacks when images or sections are missing.
- Optimize images and avoid loading every gallery image at once.
- Do not use excessive cards, gradients, glassmorphism, or generic dashboard styling on the public editorial website.

## 9. Suggested code organization

Follow the existing Laravel structure. Suggested additions:

- `app/Models/`: Post, Category, Tag, Media, Gallery, Page, SiteSetting, HomepageSection
- `app/Policies/`: policies for content models
- `app/Filament/Resources/`: CMS resources
- `app/Http/Controllers/`: public controllers
- `app/Http/Requests/`: validation requests where needed
- `resources/js/Pages/`: Inertia public pages
- `resources/js/Components/`: reusable public components
- `resources/js/Layouts/`: public layouts
- `routes/web.php`: public routes
- `database/factories/` and `database/seeders/`: safe demo data

Do not force this structure if the installed starter kit uses a different convention; follow the actual project.

## 10. Required implementation phases

### Phase 0 — Repository and environment audit
- Inspect project files and versions.
- Confirm PHP, Composer, Node, npm, MySQL.
- Identify whether this is a new or existing project.
- Report a concise plan before significant changes.

### Phase 1 — Foundation
- Set up Laravel 12 and public Vue 3 + Inertia frontend.
- Configure MySQL through `.env`.
- Install Filament 5 Panel Builder and register the admin panel.
- Confirm `/admin` is reachable and protected.
- Confirm the public frontend builds.

### Phase 2 — Database and models
- Implement migrations, models, relationships, casts, factories, and seeders.
- Run migrations only against the intended local development database.
- Verify relationships and constraints with tests.

### Phase 3 — Filament CMS
- Implement authentication/roles/panel access.
- Build resources and dashboard widgets.
- Implement media upload and gallery ordering.
- Implement homepage section and site settings management.

### Phase 4 — Public website
- Implement all public routes/controllers/Inertia pages.
- Build responsive editorial design inspired by the reference.
- Load content dynamically from the database.
- Implement SEO and empty states.

### Phase 5 — Integration and quality
- Verify admin changes appear on the public website.
- Add feature tests for public visibility, authorization, CRUD, uploads, and slugs.
- Run formatter, tests, and production frontend build.
- Fix all reported errors.

### Phase 6 — Deployment readiness
- Provide deployment documentation for a PHP-compatible hosting environment.
- Document PHP extensions, environment variables, database migration, storage link, cache commands, queue needs (if any), and writable directories.
- Never assume shared hosting supports every required PHP version or shell command; verify hosting compatibility first.

## 11. Definition of done

The project is not complete until:
- Admin can log in securely at `/admin`.
- Unauthorized users cannot access the panel.
- Admin can create, edit, publish, unpublish, and archive posts.
- Admin can change article descriptions and featured images.
- Admin can manage categories and tags.
- Admin can upload, browse, and safely remove unused media.
- Admin can create galleries and reorder gallery images.
- Admin can edit About/Contact pages.
- Admin can edit site identity and social links.
- Admin can enable, disable, and reorder homepage sections.
- Public homepage and archives render database content.
- Draft/unpublished content is never visible publicly.
- Website is responsive and accessible.
- Migrations, tests, and frontend production build pass.
- README documents local setup, admin user creation, storage setup, and deployment.

## 12. Final reporting format

At the end of each phase, report:
1. What was implemented.
2. Files created/changed.
3. Commands executed.
4. Test/build results.
5. Remaining issues or decisions.
6. The exact next step.

Do not claim the whole project is finished if only a phase has been completed.
