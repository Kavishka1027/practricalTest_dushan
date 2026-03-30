## Subscription Platform

This project is a simple subscription platform built with Laravel, MySQL, queues, and Vue.js.

Users can subscribe to one or more websites using the UI or API. When a new post is created for a website, the system dispatches a queued job and sends an email notification to all subscribers of that website. Duplicate email delivery is prevented with the `sent_emails` table.

## Main Features

- REST APIs for listing websites, subscribing to websites, and creating posts
- Vue.js frontend for selecting a website and subscribing with email validation
- Background queue processing for post notification emails
- HTML email template for new post notifications
- Seeded website data for quick local testing
- Service layer with contracts
- Use case based controller flow for `Post`, `Subscription`, and `Website`

## Tech Stack

- Laravel 13
- PHP 8.3 or higher
- MySQL
- Vue 3
- Vite
- Laravel queues

## API Endpoints

- `GET /api/v1/websites`
- `GET /api/v1/websites/{website}`
- `POST /api/v1/websites/{website}/subscribe`
- `POST /api/v1/websites/{website}/posts`

Example subscription payload:

```json
{
  "email": "subscriber@example.com"
}
```

Example post payload:

```json
{
  "title": "Laravel 13 Released",
  "description": "A summary of the new framework improvements.",
  "url": "https://example.com/posts/laravel-13-released"
}
```

The `url` field is optional. If omitted, the application generates a unique URL automatically.

## Project Structure

This project uses a modular folder structure such as:

- `app/Post/...`
- `app/Subscription/...`
- `app/Website/...`

Each module follows a similar pattern:

- `IO/Http/Controllers`
- `UseCase`
- `UseCase/Requests`
- `Entities/Models`

## Local Setup

1. Clone the repository.
2. Move into the project directory.
3. Install PHP dependencies.
4. Install Node dependencies.
5. Create the environment file.
6. Configure your database and mail settings.
7. Generate the application key.
8. Run migrations and seeders.
9. Start the app and queue worker.

Commands:

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
composer run dev
```

`composer run dev` starts:

- Laravel development server
- queue listener
- log viewer
- Vite dev server

If you prefer to run them separately:

```bash
php artisan serve
php artisan queue:work
npm run dev
```

If you want a production-style frontend build:

```bash
npm run build
```

## Special Instructions For Local Platform

- Make sure your PHP version matches the Laravel requirement in `composer.json`.
- Configure a MySQL database in `.env` before running migrations.
- Configure `QUEUE_CONNECTION` properly if you want asynchronous email delivery.
- Configure `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, and `MAIL_PASSWORD` in `.env` for real email sending.
- If you only want to test the flow without sending real emails, use `MAIL_MAILER=log` or `MAIL_MAILER=array`.
- The queue worker must be running, otherwise post emails will stay in the queue and will not be sent.
- Seeded website data is available after running `php artisan migrate --seed`.

## Special Instructions For Remote Platform

- Use a production web server such as Nginx or Apache and point the document root to `public/`.
- Set `APP_ENV=production` and `APP_DEBUG=false`.
- Run `php artisan config:cache`, `php artisan route:cache`, and `php artisan view:cache` in production.
- Run `npm run build` during deployment so Vite assets are generated in `public/build`.
- Run migrations on the server with:

```bash
php artisan migrate --force
```

- Run a persistent queue worker in production, for example:

```bash
php artisan queue:work --tries=3
```

- Ensure the server has correct write permissions for:
  `storage/`
  `bootstrap/cache/`

## UI

Visit `/` to use the Vue.js subscription interface.

## Testing

Run:

```bash
php artisan test
```

## Notes

- No authentication is required for this task.
- Emails are triggered when a new post is created for a website.
- Duplicate post emails are prevented per subscriber and post combination.
