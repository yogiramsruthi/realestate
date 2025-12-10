# Real Estate Management Module for Perfex CRM

A comprehensive real estate management module for Perfex CRM that provides complete functionality for managing real estate projects, plots, customer bookings, transactions, and team assignments.

## Features

### 1. Customer Management
- Integrated with Perfex CRM's existing customer/client system
- Track customer bookings and transactions
- View customer history and payment records

### 2. Real Estate Project Management
- Create and manage multiple real estate projects
- Track project details: name, location, type, dates
- Monitor total and available plots per project
- Set project status (active/inactive)

### 3. Plots Management
- Add plots to projects with detailed information
- Track plot specifications: number, size, type, dimensions, facing
- Set plot prices
- Manage plot status (available, booked, sold, reserved)
- Auto-update project statistics when plots are added/removed

### 4. Plot Booking System
- Book plots for customers
- Track booking dates and amounts
- Manage payment plans and installments
- Calculate and track paid amounts and balances
- Assign bookings to staff members
- Set booking status (pending, confirmed, cancelled, completed)
- Auto-update plot status when booked

### 5. Accounts Management System
- Record transactions for bookings
- Support multiple payment modes (cash, cheque, bank transfer, online)
- Track payment dates and reference numbers
- Automatic balance calculations
- Transaction types: payments and refunds

### 6. Team Management System
- Assign staff members to projects
- Define roles (manager, sales executive, supervisor)
- Track assignment dates and status
- View team assignments per project
- Manage active/inactive assignments

## Installation

1. Copy the `realestate` folder to your Perfex CRM `modules` directory
2. Navigate to Setup > Modules in your Perfex CRM admin panel
3. Find "Real Estate Management" and click "Install"
4. Click "Activate" to enable the module

## Database Tables

The module creates the following tables:

- `tblrealestate_projects` - Real estate projects
- `tblrealestate_plots` - Plots within projects
- `tblrealestate_bookings` - Customer bookings
- `tblrealestate_transactions` - Payment transactions
- `tblrealestate_team_assignments` - Team member assignments

## Permissions

The module includes the following permission levels:
- View - View real estate data
- Create - Add new projects, plots, bookings, etc.
- Edit - Modify existing records
- Delete - Remove records

Configure permissions in Setup > Staff > Roles.

## Menu Structure

After activation, a new "Real Estate" menu will appear in the admin sidebar with:
- Dashboard - Overview with statistics
- Projects - Manage real estate projects
- Plots - Manage plots
- Bookings - Manage customer bookings
- Accounts - View and manage transactions
- Team - Manage team assignments

## Usage

### Creating a Project
1. Go to Real Estate > Projects
2. Click "Add Project"
3. Fill in project details (name, location, type, dates)
4. Save the project

### Adding Plots
1. Go to Real Estate > Plots
2. Click "Add Plot"
3. Select a project and fill in plot details
4. Set the plot price and status
5. Save the plot

### Booking a Plot
1. Go to Real Estate > Bookings
2. Click "Add Booking"
3. Select a plot (only available plots shown)
4. Select a customer
5. Enter booking amount, total amount, and paid amount
6. Assign to a staff member if needed
7. Save the booking (plot status auto-updates to "booked")

### Recording Transactions
1. Go to Real Estate > Accounts
2. Click "Add Transaction"
3. Select a booking
4. Enter transaction details (amount, date, payment mode)
5. Add reference number if applicable
6. Save the transaction (booking balance auto-updates)

### Managing Team
1. Go to Real Estate > Team
2. Click "Assign Team Member"
3. Select a staff member
4. Optionally select a project
5. Set role and assignment date
6. Save the assignment

## Technical Details

### Module Structure
```
modules/realestate/
├── controllers/
│   ├── Realestate.php (Dashboard)
│   ├── Projects.php
│   ├── Plots.php
│   ├── Bookings.php
│   ├── Accounts.php
│   └── Team.php
├── models/
│   ├── Projects_model.php
│   ├── Plots_model.php
│   ├── Bookings_model.php
│   ├── Transactions_model.php
│   └── Team_model.php
├── views/
│   ├── dashboard.php
│   ├── projects/
│   ├── plots/
│   ├── bookings/
│   ├── accounts/
│   └── team/
├── language/english/
│   └── realestate_lang.php
├── assets/
│   └── css/realestate.css
├── install.php
└── realestate.php (main module file)
```

### Key Features
- Automatic balance calculation
- Plot count updates
- Status management
- Activity logging
- Permission checks
- Data validation

## Version

Version 1.0.0

## Author

Real Estate Module

## License

This module is provided as-is for Perfex CRM installations.

## Support

For issues or questions, please contact the module administrator.
