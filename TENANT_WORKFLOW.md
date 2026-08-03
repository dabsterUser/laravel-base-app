# Duralux Multi-Tenancy Architecture & Workflow Guide

This document describes how the single-database Multi-Tenancy engine works in **Duralux**, outlining the lifecycle, automatic query isolation, and how developers can build new scoped features easily.

---

## 🏢 1. High-Level Concept

Duralux uses an elegant, high-performance **Single-Database, Column-Isolated Multi-Tenancy** approach.
1. Key models (`User`, `Form`, and `Setting`) contain a nullable `tenant_id` column referencing the `tenants` table.
2. Standard tenant accounts always have a non-null `tenant_id`. They can only see and modify data belonging to their company.
3. Global Super Admin accounts have a `tenant_id` of `null`. This bypasses constraints, giving them full visibility and control across all tenants.

---

## 🔄 2. Complete Tenant Lifecycle Workflow

### Step A: Tenant Onboarding (Registration)
- When a new customer registers via the `/register` onboarding flow, they are required to supply a **Company / Tenant Name** along with their personal registration details.
- Under the hood, `RegisteredUserController@store` dynamically:
  1. Creates a new record in the `tenants` table (automatically slugifying the name).
  2. Creates the new `User` record, setting its `tenant_id` to the newly created tenant's ID.
  3. Seamlessly assigns them the `'Super Admin'` role. This empowers them to manage users, settings, notifications, and forms within their company.

### Step B: Trait-Driven Automatic Query Isolation
- The `User`, `Form`, and `Setting` models utilize the `App\Models\Traits\BelongsToTenant` trait.
- On model booting, the trait automatically hooks up a global database query filter called `App\Scopes\TenantScope`.
- **Query Scoping:** Whenever an active tenant user runs a database query (e.g. `Form::all()`, `User::where(...)`, `Setting::get(...)`), the `TenantScope` automatically intercepts the SQL compilation and appends a `WHERE table.tenant_id = ?` condition based on the authenticated user's `tenant_id`.
- **Creation Seeding:** On saving or creating new records (e.g. `Form::create(...)`), the trait's Eloquent lifecycle listener automatically sets the `tenant_id` to the active user's `tenant_id` in-memory. **No manual assignment required!**

### Step C: Multi-Tenant Settings with Composite Unique Index
- Tenants need to configure their own custom parameters (like custom SMTP setups, API keys, and vendor options).
- To allow multiple tenants to save settings with the same key (e.g., `smtp_host`) without conflicts, the `settings` table utilizes a **Composite Unique Constraint** on `['key', 'tenant_id']`.
- When `Setting::set('key', 'value')` is called:
  1. It automatically resolves the active user's `tenant_id`.
  2. Runs `updateOrCreate` scoped on `['key' => $key, 'tenant_id' => $tenantId]`, ensuring the correct record is targeted.

### Step D: Zero-Code Auto-Discovered Reporting Isolation
- The Reporting Center auto-discovers active modules (like `Form`, `FormSubmission`, and `User`).
- When a report is exported, the engine inspects if the chosen model utilizes multi-tenancy. If it does, the query is automatically constrained to the authenticated user's `tenant_id`, maintaining absolute data privacy and protection during exports.

### Step E: Global Admin Bypass & Control Panel
- Users with `tenant_id = null` (like the seeded `admin@example.com` profile) bypass the `TenantScope` completely.
- A dedicated **Tenancy Control** sidebar link is rendered exclusively for them, routing to the `TenantController` panel. Here, they can:
  1. Monitor active tenants.
  2. Inspect tenant metadata, total users, and active form counts.
  3. Create new tenants or delete existing ones (with cascading delete protections).

---

## 🛠️ 3. Developer Guide: Creating Scoped Modules

As a developer, expanding the platform with new scoped modules takes less than 10 seconds!

### Step 1: Create your Migration and include `tenant_id`
Ensure your table has a nullable foreign key pointing to `tenants`.
```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->decimal('price', 8, 2);
    $table->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('cascade');
    $table->timestamps();
});
```

### Step 2: Implement the `BelongsToTenant` Trait & Fillable Array
In your Eloquent model, import and use the trait, and add `tenant_id` to your mass-assignment fillable protection list:
```php
namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'name',
        'price',
        'tenant_id', // Allow mass-assignment
    ];
}
```

### Step 3: Run Queries (Completely Scoped automatically!)
That's it! Queries are automatically isolated. Standard users will only see their own items, while Global Admins will see all records.
```php
// If logged in as User from Acme Corp (tenant_id = 1)
Product::all();
// Under the hood: SELECT * FROM products WHERE tenant_id = 1

// If saving a new product
Product::create(['name' => 'SaaS Widget', 'price' => 29.99]);
// Under the hood: tenant_id is automatically set to 1!
```

### Step 4: Bypassing the Scoping (If needed)
If you are writing a global command or background job and need to pull all records across all tenants regardless of the logged-in context, use `withoutGlobalScope`:
```php
$allGlobalProducts = Product::withoutGlobalScope(\App\Scopes\TenantScope::class)->get();
```
