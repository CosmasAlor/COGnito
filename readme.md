# COGnito

A comprehensive Laravel-based business management system with modular architecture.

## Features

- **Timesheet Management Module**: Complete employee and timesheet management system
  - Employee Management (CRUD operations)
  - Timesheet tracking with daily entries
  - Monthly period tracking (21st to 20th of next month)
  - Leave management (Annual Leave, Family Resp/FL, MT/PT, PPH, CTO, Sick Leave, Unpaid, Absent)
  - Employee number tracking
  - Monthly totals and summaries

## Requirements

- PHP >= 8.0
- Composer
- MySQL/MariaDB
- Laravel Framework

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/COGnito.git
cd COGnito
```

2. Install dependencies:
```bash
composer install
```

3. Copy environment file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Configure your database in `.env` file

6. Run migrations:
```bash
php artisan migrate
```

7. (Optional) Install npm dependencies:
```bash
npm install
```

## Timesheet Management Module

The Timesheet Management module provides:

- **Employee Management**: Add, edit, delete employees with details like:
  - Employee Number
  - Full Name
  - National ID Number
  - Contact Information
  - Contract Dates
  - Position and Salary

- **Timesheet Management**: 
  - Create timesheets for employees by month
  - Daily entry tracking with:
    - Start/End times
    - Mission details
    - Leave types (checkboxes)
    - Absent status
    - Check-in/Check-out times
  - Monthly totals calculation
  - Period-based tracking (21st to 20th)

## License

[Your License Here]

## Contributing

[Your Contributing Guidelines Here]
