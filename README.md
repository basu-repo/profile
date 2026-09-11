# basudeoshrestha.com.np

A personal site with a small admin area, written in plain PHP and arranged as
MVC. There is no framework and no Composer dependency: the application boots
from `app/Core/bootstrap.php` and every request enters through `index.php`.

## Project structure

```
My_Profile/
├── index.php               Front controller - the only PHP file the web serves
├── router.php              Equivalent routing for `php -S` (development only)
├── .htaccess               Apache rules: HTTPS, headers, front controller, legacy URLs
│
├── app/
│   ├── routes.php          The URL map: "METHOD /path" => controller function
│   ├── Core/
│   │   ├── bootstrap.php   Loads everything below, in order
│   │   ├── helpers.php     base_path(), asset_url(), h(), redirect(), excerpt()
│   │   ├── database.php    Config loading and the shared PDO connection
│   │   ├── html.php        Rich-text sanitising and date formatting
│   │   ├── uploads.php     Storing images uploaded from the admin editor
│   │   ├── view.php        view(), view_capture(), json_response()
│   │   └── router.php      Route matching and dispatch
│   ├── Models/             Data and rules - nothing here prints anything
│   │   ├── SiteContent.php Editable site copy plus the admin form definition
│   │   ├── Entry.php       Research entries
│   │   ├── Message.php     Contact messages
│   │   └── AdminUser.php   Admin accounts, session, flash, CSRF
│   ├── Controllers/        Request in, model calls, one view out
│   │   ├── HomeController.php, AboutController.php, ContactController.php,
│   │   │   ContactFormController.php, NowController.php, ResearchController.php
│   │   └── Admin/          AuthController, DashboardController,
│   │                       MessageController, EntryController, ContentController
│   └── Views/
│       ├── layouts/        site.php (public chrome), admin.php (admin chrome)
│       ├── partials/       contact-form.php, wysiwyg-toolbar.php
│       ├── pages/          home, about, contact, now, research/, error
│       └── admin/          login, setup, dashboard, messages/, entries/, content/
│
├── config/config.php       Database credentials and the anti-spam thresholds
├── database/               schema.sql, entries.sql, update-retention.sql
├── bin/                    Command-line scripts (message cleanup)
├── storage/                Runtime logs - never served
├── legacy/                 The original static HTML pages, kept for reference
│
├── css/ js/ images/ certificates/   Public assets
└── uploads/                Images added through the admin editor (created on demand)
```

Only `index.php` and the asset folders are reachable from the web. `.htaccess`
returns 403 for `app/`, `bin/`, `config/`, `database/`, `storage/` and
`legacy/`, and `router.php` does the same during development.

## Routes

| URL | Handler |
|-----|---------|
| `/` | `home_index` |
| `/about` | `about_index` |
| `/contact` | `contact_index` |
| `/contact/submit` | `contact_submit` (JSON, POST from the contact form) |
| `/now` | `now_index` |
| `/research` | `research_index` |
| `/research/{slug}` | `research_show` |
| `/admin` | redirects to setup, login or dashboard |
| `/admin/login`, `/admin/logout`, `/admin/setup` | `AuthController` |
| `/admin/dashboard` | `admin_dashboard` |
| `/admin/messages`, `/admin/messages/{id}` | `MessageController` |
| `/admin/entries`, `/admin/entries/new`, `/admin/entries/{id}/edit` | `EntryController` |
| `/admin/content` | `admin_content_index` |

The addresses this site used before the restructure (`/index.php`,
`/about.php`, `/contact.php`, `/now.php`, `/research.php?slug=…`,
`/admin/dashboard.php` and the rest) are redirected to the routes above by
`.htaccess`, so existing links and search results keep working.
`/submit-form.php` is rewritten internally rather than redirected, because a
redirect would drop the POST body.

## Adding a page

1. Add a function to a controller in `app/Controllers/`.
2. Add its template under `app/Views/pages/`.
3. Register the URL in `app/routes.php`.

Controllers are plain functions rather than classes, so there is no
autoloader; `app/Core/bootstrap.php` and `app/routes.php` list the files
explicitly.

## Local development

```bash
php -S localhost:8000 router.php
```

Then open http://localhost:8000. The built-in server ignores `.htaccess`, which
is what `router.php` is for — it applies the same rules.

## Database

Import once, in this order:

```bash
mysql -u USER -p DATABASE < database/schema.sql
mysql -u USER -p DATABASE < database/entries.sql
mysql -u USER -p DATABASE < database/update-retention.sql
```

Then put the credentials in `config/config.php`. That file is never served —
it lives outside the document-root-visible tree and `.htaccess` blocks the
whole `config/` directory.

Visit `/admin/setup` once to create the first admin account. The setup page
disables itself as soon as an account exists.

## Scheduled cleanup

Visitors may ask for their message to be deleted after 7–90 days. A cron job
carries that out:

```
0 3 * * * php /home/USER/public_html/bin/cleanup-expired-messages.php
```

## Deploying to cPanel

Upload the whole project into `public_html`. The document root stays at the
project root, so nothing about the hosting setup needs to change: `.htaccess`
is what keeps the code directories private.

After uploading:

- [ ] Confirm `mod_rewrite` is enabled (clean URLs depend on it)
- [ ] Check `/` and `/research` load, and that an old link like `/about.php`
      redirects to `/about`
- [ ] Sign in at `/admin/login` and save one content section
- [ ] Send a test message through the contact form
- [ ] Make sure `uploads/` is writable by PHP (755)
