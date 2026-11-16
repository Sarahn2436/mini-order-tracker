# Mini Order Tracker

A Laravel app for managing customers and tracking orders. Search customers by name or company, filter and paginate orders by status, and view dashboard summaries for order counts, value, and upcoming due dates.

## Requirements

- PHP 8.3+
- Composer
- Node.js and npm

## Install and run

Use a fresh SQLite database for first-time setup.

```bash
composer setup
php artisan db:seed
php artisan serve