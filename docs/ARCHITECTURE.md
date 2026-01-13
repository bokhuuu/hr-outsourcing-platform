# Architecture Overview

## Multi-Tenancy

The system uses database-level multi-tenancy with a shared database approach

**Implementation:**

-   All company-specific tables have a `company_id` column
-   Global Scopes automatically filter queries by company
-   `app/Models/Scopes/CompanyScope.php`

**Trait for reusability:**

-   `HasCompanyScope` trait applied to models: Employee, Position, LeaveRequest, Absence, Attendance, Vacancy
-   `app/Models/Traits/HasCompanyScope.php`

## Authorization

Two-level approach:

**Middleware (route-level blocking)**
**Controller (data filtering)**

## Database Design

**User vs Employee separation:**

-   `users` table: Authentication (login, roles)
-   `employees` table: HR data (position, manager, company)
-   HR and Admin users have no employee records (platform-level)
-   Company Admin and Employee users have both user + employee records

**Self-referencing relationships:**

-   `employees.manager_id` -> `employees.id` (manager is also an employee)
-   No separate managers table needed

## API Authentication

**Laravel Sanctum** for token-based authentication

**Token flow:**

```
1. POST /login
2. Client stores token
3. Client sends: Authorization: Bearer {token}
4. Sanctum validates
5. Request proceeds
```

## Service Layer Pattern

The application uses the Service Layer pattern to separate business logic from HTTP concerns.

### Architecture

```
HTTP Request
    ↓
Controller (HTTP layer)
    ├── Validate input
    ├── Get authenticated user
    ├── Call service method
    └── Format response (JSON/HTML)
    ↓
Service (Business layer)
    ├── Business logic validation
    ├── Database operations
    ├── Complex workflows
    └── Return results
    ↓
Controller formats and returns response
```

### Services

**AttendanceService**

-   `checkIn(Employee $employee)` - Check in employee for the day
-   `checkOut(Employee $employee)` - Check out employee

**LeaveRequestService**

-   `createRequest(Employee $employee, array $data)` - Create leave request with overlap validation
-   `approve(LeaveRequest $leaveRequest, int $approvedBy)` - Approve leave request
-   `reject(LeaveRequest $leaveRequest, int $rejectedBy, string $reason)` - Reject leave request

**AbsenceService**

-   `register(Employee $employee, string $date, ?string $reason, int $createdBy)` - Register employee absence

## Validation

**Form Requests:**

-   `StoreVacancyRequest`, `StoreLeaveRequest`, `StoreAbsenceRequest`
-   `app/Http/Requests/`

**Key validations:**

-   `end_date|after_or_equal:start_date` - Prevents invalid date ranges
-   `employee_id|exists:employees,id` - Ensures employee exists
-   Database unique constraints for business rules (one check-in per day, one absence per employee per day)

**Custom Validation Hooks**

Form Requests use `withValidator()` for complex validation:

-   Database queries for business logic
-   Multi-field validation (date range overlaps)
-   Runs after basic rules pass

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/                # API controllers (JSON responses)
│   │   └── Web/                # Web controllers (HTML views)
│   ├── Middleware/             # CheckRole middleware
│   └── Requests/               # Form Request validation
├── Models/
│   ├── Scopes/                 # CompanyScope
│   └── Traits/                 # HasCompanyScope
├── Services/                   # Business logic layer
│   ├── AttendanceService.php
│   ├── LeaveRequestService.php
│   └── AbsenceService.php
database/
├── migrations/                 # Schema definitions
└── seeders/                    # Test data
resources/
├── views/
│   ├── dashboard/              # Role-based dashboard partials
│   └── employee/               # Employee self-service pages
routes/
├── api.php                     # API routes with versioning
└── web.php                     # Web routes (employee UI)
```
