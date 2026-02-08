# Copilot Instructions - Gym MVC Application

## Architecture Overview

This is a **gym membership management system** built with custom PHP MVC framework (no Laravel/Symfony). 

**Core Flow:**
- Entry: `public/index.php` → root `index.php` → `Router.php` dispatches to controllers
- URL format: `/gym-mvc/controller/method/param1/param2`
- Router extracts controller name (`UserController`), method name (`dashboard`), and params from URL segments

**Key Components:**
- **Controllers** (`app/controllers/`) - handle HTTP requests, require role-based auth
- **Models** (`app/models/`) - extend `Model` base class, access database via `$this->db` (PDO)
- **Views** (`app/views/`) - PHP templates, passed data via `extract($data)`
- **Core** (`app/core/`) - framework: `Router`, `Controller`, `Database` (singleton), `Auth`, `Model`, `Mailer`

## Database & Models

**Database Setup:**
- Auto-creates MySQL database on first access via `Database::getInstance()`
- Auto-migrations in `createTables()` - all tables initialized on app startup
- Singleton pattern: `Database::getInstance()` returns shared PDO connection

**Models:**
- All models inherit from `Model` - provides `$this->db` (PDO connection)
- Access database: `$stmt = $this->db->prepare()` then `$stmt->execute()`
- Use `PDO::FETCH_ASSOC` for arrays
- Settings table accessed via `getSetting($key)` / `updateSetting($key, $value)`

**Key Models:**
- `User` - authentication, profiles (role: admin/user/faculty)
- `Payment`/`Subscription` - handles gym plans and subscription state
- `Attendance` - marks gym visits, requires active paid subscription
- `Plan`/`Membership` - gym membership plans and membership types

## Authentication & Authorization

**Session-based:**
- `$_SESSION['user_id']` = user ID
- `$_SESSION['role']` = 'admin', 'user', or 'faculty'

**Auth Class Methods:**
```php
Auth::check()                  // Redirects to login if not authenticated
Auth::role(['admin', 'user'])  // Check multiple roles or die
Auth::isAdmin()  / Auth::isFaculty()  / Auth::isUser()  // Boolean checks
```

**Pattern in Controllers:**
- Use `Auth::role(['user'])` at start of methods requiring authentication
- Always check `$_SESSION['user_id']` and `$_SESSION['role']` for conditional logic
- Role-based redirect: admin → `/admin/dashboard`, user/faculty → `/user/dashboard`

## Controller Patterns

**Basic Structure:**
```php
class UserController extends Controller {
    public function dashboard() {
        Auth::role(['user', 'faculty']);  // Auth gate
        $userModel = $this->model('User');
        $data = $userModel->getProfile($_SESSION['user_id']);
        $this->view('user/dashboard', $data);
    }
}
```

**Key Methods:**
- `$this->model('ModelName')` - requires model file, returns new instance
- `$this->view('path/to/view', $data)` - renders view, injects `$data` via `extract()`
- View automatically checks if user has active subscription before rendering attendance options

**Attendance Marking Logic:**
- Only allowed if user role is 'user' (non-admin)
- Requires **active paid subscription** (from Payment model)
- Payment status must be 'verified'
- Checks if already marked today

## Common Workflows

### Adding a New Feature
1. Create model in `app/models/NewModel.php` extending `Model`
2. Create controller in `app/controllers/NewController.php` extending `Controller`
3. Create view in `app/views/new/view.php`
4. Router auto-loads based on URL: `/gym-mvc/new/method` → `NewController::method()`

### User Registration/Login
- `AuthController::register()` - creates user, requires email verification (flow incomplete)
- `AuthController::login()` - validates credentials, sets session, redirects by role
- Redirects stored in `$_SESSION['intended_url']` to return after login

### Payment & Subscription Flow
- `PaymentController` handles payment processing
- `Payment` model tracks payments (status: 'verified' = active)
- `Subscription` model links plans to users with start/end dates
- Attendance requires both active subscription AND verified payment

## Important Patterns & Gotchas

**Always use PDO prepared statements** - prevents SQL injection
```php
$stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
```

**View rendering injects data globally** - views use `$variable` directly, not `$data['variable']`

**Controller::view() auto-injects attendance data** - every view gets:
- `$attendanceMarkedToday` - boolean
- `$attendanceAllowed` - boolean (requires active verified subscription)

**Router requires exact file/class names** - `UserController` must be in `app/controllers/UserController.php`

**Settings system** - use `getSetting()` / `updateSetting()` for persistent config values

**No migrations** - schema auto-creates on first request. Modify `Database::createTables()` for schema changes.

**Email** - uses `Mailer` class with SMTP config from `config/config.php` (Gmail configured)

## File References for Context

- **Architecture**: [app/core/Router.php](app/core/Router.php), [app/core/Controller.php](app/core/Controller.php)
- **Auth**: [app/core/Auth.php](app/core/Auth.php), [app/controllers/AuthController.php](app/controllers/AuthController.php)
- **Database**: [app/core/Database.php](app/core/Database.php), [app/core/Model.php](app/core/Model.php)
- **Key Models**: [app/models/User.php](app/models/User.php), [app/models/Payment.php](app/models/Payment.php)
- **Example Controllers**: [app/controllers/AdminController.php](app/controllers/AdminController.php), [app/controllers/UserController.php](app/controllers/UserController.php)
- **Config**: [config/config.php](config/config.php)

## Development Notes

- **No build step** - direct PHP execution via Apache/LAMP
- **Database**: Runs migrations on app startup
- **Error handling**: Minimal - most errors die with message
- **Testing**: No test framework configured
- **Logging**: Check error logs in standard PHP/Apache locations
