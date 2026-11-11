# Purchase + QC + Stock ERP (Laravel 13 + Vue 3)

This ZIP is a **complete application source overlay** for a freshly created Laravel 13 project. Laravel's vendor/framework and node_modules are intentionally not bundled. It implements item QC rules, suppliers/warehouses, purchase orders, approval, multi-lot goods receipts, QC measurements and approval, quarantine/available/rejected inventory, invoices, audit records, and a Vue frontend.

## Windows / XAMPP installation from scratch
1. Install PHP 8.3+, Composer, Node 20.19+ (or 22.12+), and XAMPP/MySQL. Start MySQL in XAMPP. Ensure `php -v`, `composer -V`, `node -v`, `npm -v` work in CMD/PowerShell.
2. `cd C:\xampp\htdocs`
3. `composer create-project laravel/laravel erp_complete "^13.0"`
4. Extract this ZIP, and **copy its contents** (app, database, routes, resources, vite.config.js, etc.) into `C:\xampp\htdocs\erp_complete`, replacing corresponding starter files. Do not copy the containing `erp_full_project` folder itself.
5. Create a MySQL database named `erp_complete` using phpMyAdmin.
6. `cd C:\xampp\htdocs\erp_complete`
7. Edit `.env`: `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=erp_complete`, `DB_USERNAME=root`, `DB_PASSWORD=` (adjust if needed). Keep the generated APP_KEY; otherwise run `php artisan key:generate`.
8. `composer install`
9. `npm install && npm install vue && npm install -D @vitejs/plugin-vue`
10. `php artisan migrate --seed`
11. Terminal A: `php artisan serve`
12. Terminal B: `npm run dev`
13. Open `http://127.0.0.1:8000/erp` (NOT the Vite port).

## Demo accounts
Seeded demo users (only for local development):
- buyer@example.test / password (buyer)
- manager@example.test / password (manager)
- inspector@example.test / password (inspector)
- qcmanager@example.test / password (qc_manager)
- accounts@example.test / password (accounts)

The frontend uses a Laravel session login. API endpoints are protected by `auth:sanctum`? **No**: the app uses the `web` middleware + session authentication for `/api/*` routes (see routes/web.php), with CSRF on writes. Log in on `/erp` first.

## Demonstration
1. Log in as buyer. Create a PO for seeded item `ITEM001`, supplier and warehouse; submit it.
2. Log in as manager and approve PO.
3. Log in as buyer and create GRN with one or more lots. Quantities must not exceed outstanding PO quantity. Stock starts in hold.
4. Log in as inspector. Open a lot and create an inspection. Fill all measurements for each required sample; submit it.
5. Log in as qc_manager. Approve inspection. All in-range measurements pass; any out-of-range fails. Passing stock moves hold -> available once only. Failed stock moves hold -> rejected once only.
6. Log in as accounts. Create supplier invoice against approved/accepted lots only, up to the unbilled accepted quantity.

## Key business rules
- QC rule quantity ranges must not overlap; missing rule is an error; sample count capped at lot physical quantity.
- QC limits and sample counts are snapshotted when an inspection is created.
- All sample/parameter readings required before submission.
- Role-based PO/QC approval; requester cannot approve their own QC inspection.
- Stock transitions and invoice creation use DB transactions + row locks; unique constraints provide idempotency.
- Rejected stock is blocked. Reinspection is supported by starting a new inspection on a rejected lot (with stock kept rejected until an authorized disposition; automatic restock of rejected lots is deliberately not implemented).
- Tax is calculated per line; partial receipts and invoices supported.

## Important limitations
This is an assessment/demo application, not production-ready accounting software. Configure production secrets, HTTPS, mail, backups, permissions and deployment separately. Stock is tracked through immutable movements, not a cached balance table. It does not implement supplier returns, debit notes, or credit notes; rejected stock is recorded and blocked from normal invoicing. Invoice lines reference accepted lots. A production system should additionally implement explicit return/disposition workflows and a full test suite.
