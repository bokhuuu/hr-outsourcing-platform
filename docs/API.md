# API Documentation

Base URL: `http://localhost:8000/api`

## Authentication

**Login**

```
POST /login
Body: { "email": "hr@platform.ge", "password": "password" }
Returns: { "token": "..." }
```

**Logout**

```
POST /v1/logout
Headers: Authorization: Bearer {token}
```

All endpoints below require `Authorization: Bearer {token}` header

## Vacancies

**Public endpoints:**

```
GET  /v1/public/vacancies           - List published vacancies
GET  /v1/public/vacancies/{id}      - View single vacancy
```

**HR only:**

```
POST /v1/companies/{id}/vacancies   - Create vacancy
Body: {
  "title": "Backend Developer",
  "description": "...",
  "employment_type": "full-time",
  "status": "published"
}
```

## Attendance

```
POST /v1/attendances/check-in       - Check in (once per day)
POST /v1/attendances/check-out      - Check out (requires check-in first)
GET  /v1/attendances                - List my attendance history
```

## Leave Requests

**Employee:**

```
POST /v1/leave-requests
Body: {
  "leave_type": "vacation",
  "start_date": "2026-02-01",
  "end_date": "2026-02-05",
  "reason": "Family vacation"
}
Status: pending by default

GET /v1/leave-requests               - List my requests
```

**HR and Company Admin:**

```
GET  /v1/leave-requests              - List all company requests
PUT  /v1/leave-requests/{id}/approve - Approve request
PUT  /v1/leave-requests/{id}/reject  - Reject with reason
Body: { "rejection_reason": "..." }
```

## Absences

**HR only:**

```
POST /v1/absences
Body: {
  "employee_id": 2,
  "date": "2026-01-10",
  "reason": "Sick leave"
}
```

**All users:**

```
GET /v1/absences                     - Employees see own, HR sees all
```

## Common Errors

```
401 - Missing or invalid token
403 - Insufficient permissions
404 - Resource not found
```
