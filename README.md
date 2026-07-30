# Duralux - Ultimate Laravel 12 Enterprise Base Application

Duralux is a state-of-the-art, premium enterprise base application built on **Laravel 12**, **SQLite**, and **Tailwind CSS**. It combines advanced multi-tenancy, dynamic role-based access control, real-time user-targeted notifications, comprehensive system audits, custom dynamic drag-and-drop form building, and live interactive dashboards.

Designed to mimic a premium enterprise SaaS environment, it features an elegant dark-left-sidebar "Duralux" theme layout with sleek dashboard statistics, modern form designs, high-performance web components, and dynamic real-time integrations.

---

## 🌟 Key Features

### 🏢 1. Advanced Multi-Tenancy Architecture
- **Automatic Query Isolation:** Seamless query filtering across `users`, `forms`, and `settings` tables using a custom `TenantScope` and a reusable `BelongsToTenant` Eloquent trait.
- **Tenant Onboarding:** Extended Laravel Breeze registration flow requiring an onboarded user's Company / Tenant Name. This dynamically provisions a new tenant and designates the registrant as the tenant's `Super Admin`.
- **Global Tenancy Control Dashboard:** A specialized central Tenancy Control board designed exclusively for **Global Super Admins** (`tenant_id = null`), allowing them to view metadata, monitor user/form counts, and manage tenants across the entire database ecosystem.

### 🔐 2. Authentication & Granular RBAC (Spatie)
- **Breeze Integration:** Full-featured Breeze Authentication system with bespoke Tailwind card designs, floating circular emblems, social login aesthetics, and secure, responsive view layouts.
- **Dynamic Roles & Permissions (Spatie):** High-fidelity administration panels to manage Users, Roles, and Permissions. Dynamic checkboxes allow immediate role assignments and granular access customization.
- **Super-Admin Safeguard:** Strict permission-based authorization checks ensuring even high-privileged administrators must possess explicit permissions, preventing implicit bypasses.

### 📝 3. Comprehensive Audit Trail & Activity Logs
- **Dynamic Logging (Spatie):** Automatic recording of critical administrative events (e.g., creating users, role adjustments, permission modifications, system configuration overrides).
- **Audit Console:** Dedicated index and detail panels with full breadcrumbs, formatted timestamps, formatted JSON metadata diff views, and user attribution for full enterprise compliance.

### 🎛️ 4. Interactive Drag-&-Drop Form Builder
- **Alpine.js-powered Workspace:** Interactive drag-and-drop / click-to-add workspace to build bespoke form schemas (text inputs, textareas, dropdown selects, radio options, checkboxes).
- **Public Form Display & Iframe Embed Widget:** Unique public-facing routes (`/f/{id}`) to load and submit live active forms with dynamically compiled server-side validation. Includes standard HTML iframe codes and an external dynamic widget script (`public/js/form-embed-widget.js`) to embed forms onto external sites seamlessly.
- **Read / Unread Submission Auditing:** Dynamic unread indicators. Shows pulsing red notification dots and rose-tinted backgrounds for unread form entries on the Forms dashboard, the Left Sidebar, and the Submissions log, automatically marking entries as read upon administrative viewing.

### 🔔 5. Real-Time Web Notifications & Live Poller
- **Notification Center:** Custom unified inbox to filter database notifications (All, Read, Unread) and instantly broadcast role-targeted alerts across the application.
- **12-Second AJAX Poller:** A high-frequency poller querying `/notifications/poll` that captures incoming notifications and instantly triggers browser-native HTML5 Desktop Push notifications as well as elegant, clickable sliding screen Toasts.

### ⚙️ 6. System Settings & AI Template Writer
- **Mail & Dynamic SMTP Testing:** Runtime connection-tested SMTP settings that clear cached mail managers instantly via `forgetMailers()`, dynamically verifying server parameters without system reloads.
- **AI LLM Integrations:** Dynamic vendor selection (OpenAI, Groq, Anthropic) using dynamic toggle panels to manage API keys, host URL endpoints, and model configurations securely in the database.
- **AI Email Writer:** Interactive AJAX-driven email body template generator utilizing live AI API endpoints (or robust localized fallback simulation templates).

### 📊 7. Beautiful Live Analytics Panels
- **Chart.js Visualizations:** Replacing generic placeholders with three real-time, interactive data-viz widgets:
  - **Form Submissions Traffic (Bar Chart):** Displaying live cumulative submission metrics across active forms.
  - **User Roles Distribution (Doughnut Chart):** Displaying user counts mapped to respective roles in the system.
  - **Submissions Volume Trend (Line Chart):** Tracking daily submission traffic volume across the trailing 7 days.

---

## 🛠️ Installation & Setup Guide

### 📋 Prerequisites
Ensure you have the following installed on your machine:
- **PHP >= 8.2**
- **Composer**
- **Node.js & NPM**
- **SQLite3**

### 🚀 Step-by-Step Launch Instructions

1. **Clone the Repository:**
   ```bash
   git clone <repository-url>
   cd laravel-base-app
   ```

2. **Install Composer & NPM Dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment Variables:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Note: By default, database connection is set to `sqlite` pointing to `database/database.sqlite`.*

4. **Initialize Database:**
   Ensure an empty SQLite file exists:
   ```bash
   touch database/database.sqlite
   ```

5. **Run Migrations & Seed Default Roles/Users:**
   ```bash
   php artisan migrate:fresh --seed
   ```
   This will set up all core schemas (users, roles, permissions, activities, forms, notifications, settings, and tenants) and seed initial testing records.

6. **Compile Frontend Assets:**
   ```bash
   # Compiles and bundles Tailwind, CSS, and JS files for production
   npm run build
   ```

7. **Start the Local Development Server:**
   ```bash
   php artisan serve
   ```
   The application will be accessible at [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## 👥 Seeded Test Credentials

The system seeds several testing profiles by default:

| Role | Email | Password | Access Context |
| :--- | :--- | :--- | :--- |
| **Global Super Admin** | `admin@example.com` | `password` | Global Access (No Tenancy limit), full Tenancy Control sidebar. |
| **Standard Tenant Admin** | `staff@example.com` | `password` | Bound to Default Tenant, can manage forms, users, and settings within tenant. |
| **Regular Tenant User** | `user@example.com` | `password` | Bound to Default Tenant, standard read/write access. |

---

## 🧪 Running Tests

Ensure your code and configurations remain perfect and regression-free by executing the comprehensive test suite:

```bash
php artisan test
```

The test suite covers:
- Complete Authentication, Registration, and Password Reset flows.
- Granular Spatie role/permission creation and enforcement.
- Activity log auditing and metadata verification.
- Dynamic SMTP Dynamic Mailer testing.
- AI LLM Generation validation.
- Form Builder schema constraints and public submission logic.
- Dynamic database notification marking, polling, and unread badges.

---

## 📂 Architecture & Trait Guide

### Multi-Tenant Usage
To make any model multi-tenant, simply include the `BelongsToTenant` trait:
```php
namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use BelongsToTenant;

    protected $fillable = ['name', 'price', 'tenant_id'];
}
```
This automatically:
- Registers `TenantScope` to isolate all database select queries.
- Binds the model's `tenant_id` to the currently logged-in user's tenant during record creation.

---

## 📄 License
This platform is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
