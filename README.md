# 📋 Laravel Task Manager API

A comprehensive task management system built with Laravel, featuring role-based access control, task dependencies, and RESTful API endpoints.

## 🚀 Features

- **Role-based Authentication** (Manager/Employee)
- **Task Management** with dependencies
- **RESTful API** with Passport authentication
- **Task Assignment** and status tracking
- **Advanced Filtering** and search capabilities

---

## ⚙️ Installation & Setup

### 1. Install Dependencies
```bash
composer install
```

### 2. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Setup
```bash
php artisan migrate --seed
```

### 4. Setup Passport Authentication
```bash
php artisan passport:install
php artisan passport:client --personal --name="User Personal Access Client"
```

### 5. Start Development Server
```bash
php artisan serve
```

---

## 🔐 Authentication

### Login Credentials
- **Available Roles:** `manager`, `employee`
- **Default Password:** `password` (for all seeded users)
- **Registration:** Available for new users with any role

### API Authentication
After successful login, you'll receive an access token. Use it in your API requests:

```http
Authorization: Bearer {your-access-token}
Content-Type: application/json
```

---

## 👥 Roles & Permissions

### 🏢 Manager Role
**High-level permissions with full task management capabilities**

**Can perform:**
- ✅ Create new tasks
- ✅ Update existing tasks
- ✅ Assign tasks to employees
- ✅ View any task details
- ✅ Access all tasks with advanced filters:
  - Filter by title, status, user_id, manager_id
  - Filter by due date range

### 👤 Employee Role
**Limited permissions focused on assigned tasks**

**Can perform:**
- ✅ View only assigned tasks
- ✅ View task with dependencies for assigned tasks
- ✅ Update task status (`pending`, `completed`, `canceled`)

**Restrictions:**
- ❌ Cannot mark a task as completed until all dependencies are completed
- ❌ Cannot view or modify tasks not assigned to them

---

## ⚠️ Important Notes

- ✅ Ensure `.env` file is properly configured (Database, APP_KEY, Passport settings)
- ✅ Use `Bearer {token}` in API headers after authentication
- ✅ All API responses follow consistent JSON format
- ✅ Validation errors return detailed error messages
- ✅ Task dependencies must be completed before marking parent task as done

---

## 🏗️ Project Structure

```
app/
├── Http/Controllers/Api/    # API Controllers
├── Services/               # Business Logic
├── Repositories/           # Data Access Layer
├── Models/                # Eloquent Models
└── Middleware/            # Custom Middleware

routes/
└── api.php               # API Routes

database/
├── migrations/           # Database Migrations
└── seeders/             # Database Seeders
```

---
