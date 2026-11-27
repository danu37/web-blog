# AGENTS.md — Blog Website

## 1. Project goal

Build a complete editorial / photography blog website aligned with the approved Figma design (`Blogging-Website-UI`, frames `Blog homepage` and `Blog single`).

The public website should feel like a dark, minimal, image-led editorial magazine:
- Near-black / charcoal backgrounds (`#121317` canvas, `#1a1c23` card surfaces, `#0a0a0a` footer bar) with restrained warm amber editorial accents (`#b46b35`).
- Full-bleed hero photography with transparent top navigation and strong visual hierarchy.
- Compact uppercase or editorial typography where appropriate (`Inter` / `Poppins` body and `Aladin` / editorial serif display headings).
- Carefully spaced 3-column article grids (including tall portrait `3:4` photography cards) and horizontal gallery strips.
- Responsive layout for desktop, tablet, and mobile.
- Avoid generic SaaS dashboard styling on the public website.
- Do not copy sample brand names, logos, placeholder text ("Lorem ipsum"), or copyrighted assets from the Figma reference. Use original placeholder content and replaceable images managed through the CMS.

The site must include a CMS so an authorized admin can change text, descriptions, images, article order, gallery content, shop/project items, and homepage sections without editing source code.

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
- Homepage (`/`) — modular layout matching Figma `Blog homepage`
- Article listing / blog archive (`/blog` with search and pagination)
- Article detail page (`/blog/{slug}`) — matching Figma `Blog single` (dark hero header with breadcrumb, title, subtitle, author, reading time, publication date; editorial prose body; optional "The Latest" & social sidebar; tags and 3-column "You May Also Like" related articles grid)
- Category archive (`/category/{slug}`)
- Tag archive (`/tag/{slug}`)
- Gallery listing (`/gallery`)
- Gallery detail (`/gallery/{slug}` with ordered frames and lightbox)
- About page (`/about`)
- Contact page (`/contact`, basic contact information; do not add email sending unless configured)
- Custom 404 page

### Top Navigation & Footer (matching Figma)
- Top navigation bar overlaid cleanly on the hero masthead (and solid dark on inner pages): site logo/title on the left, primary links (`Home`, `Journal`/`Blog`, `Galleries`, `About`, `Contact`, plus optional `Shop`/`Projects` or categories) in the center, and a working article search trigger/input on the right.
- Dark `#0a0a0a` footer with site identity/attribution on the left and configured social media links/icons on the right.

### Homepage sections
The homepage should be assembled from admin-managed content (`homepage_sections`):
- Hero / masthead (`hero`): full-bleed background photograph with dark overlay (`rgba(0,0,0,0.39)`), centered uppercase display title, and subtitle.
- Editorial intro / profile section (`editorial_intro`): two-column layout featuring an arched portrait frame (`rounded-t-full`) on the left, warm accent eyebrow (`#b46b35`), bold uppercase heading, and editorial profile paragraphs on the right.
- Selected shop / projects section (`projects`, if enabled and configured): centered eyebrow + heading with a 3-column `#1a1c23` card grid displaying item image, price/label (`#b46b35` currency/tag + value), short description, and optional link.
- Featured article (`featured_post`): prominent editorial story spotlight with photograph, category, publication date, and excerpt.
- Article / editorial grid (`article_grid`): centered eyebrow + heading with a 3-column grid (3 or 6 posts) using tall portrait photography (`aspect-[3/4]`), excerpts, and a "Load more" / "View all" link to `/blog`.
- Gallery strip (`gallery_strip`): full-width horizontal strip of published gallery photographs linking to `/gallery/{slug}`.
- Category digest (`category_digest`, optional): category filter tabs paired with a featured story and compact "latest" thumbnail list.
- Footer

Sections must be enabled/disabled and reordered in admin. The public frontend must render only active sections in their configured order. Avoid hardcoding the Figma reference's sample text.

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
   - Validate known settings in application code (site name, site description, logo, favicon, footer text, contact email/location, and social URLs such as Instagram, YouTube, X/Twitter, Facebook, Pinterest). Do not treat arbitrary stored values as executable code.

11. `homepage_sections`
   - title, section_key unique, type, description nullable, settings JSON nullable,
   - sort_order unsigned integer default 0, is_active boolean default true, timestamps.
   - Define a strict allowlist of supported section types (`hero`, `editorial_intro`, `projects`, `featured_post`, `article_grid`, `gallery_strip`, `category_digest`) and validate structured settings per type (e.g., `eyebrow`, `background_image`, `portrait_image`, `items` for shop/projects, `cta_label`/`cta_url`).

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

- Match the Figma design system (`Blogging-Website-UI`) and visual direction, not its sample/brand content:
  - **Colors**: `#121317` main dark canvas, `#1a1c23` elevated card surface, `#0a0a0a` footer surface, `#b46b35` warm editorial accent, `#ffffff` / `#f2efe9` primary text, `#b2b2b2` / `#6e6e6e` muted copy.
  - **Typography & Ratios**: uppercase display masthead, clean sans-serif / editorial body, arched (`rounded-t-full`) intro portrait, `aspect-[3/4]` portrait cards for homepage editorial grid, `aspect-[4/3]` for archive/shop cards, and horizontal full-bleed strip for gallery preview.
- Use a consistent design system: colors, spacing, typography, image ratios, container widths, and breakpoints.
- Use semantic HTML, accessible labels, keyboard-friendly navigation, visible focus states, and sufficient contrast.
- Make layouts responsive from small mobile screens upward (collapsing multi-column grids and top navbar cleanly into a mobile menu).
- Add SEO title, meta description, canonical URL, Open Graph image, and structured article metadata where appropriate.
- Use pagination for article archives and working search filtering (`/blog?q=...`).
- Add empty states and graceful fallbacks when images or sections are missing.
- Optimize images and avoid loading every gallery image at once.
- Do not use excessive cards, bright gradients, glassmorphism, or generic dashboard styling on the public editorial website.

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
