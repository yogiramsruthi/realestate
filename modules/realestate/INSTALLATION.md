# Installation Guide - Real Estate Management Module

## Prerequisites

- Perfex CRM version 2.3.* or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- Apache or Nginx web server

## Installation Steps

### Step 1: Download the Module

1. Download the module files from the repository
2. Ensure all files are intact and in the correct structure

### Step 2: Upload to Perfex CRM

1. Connect to your Perfex CRM installation via FTP or File Manager
2. Navigate to the `modules` directory in your Perfex CRM root folder
3. Upload the `realestate` folder to the `modules` directory
4. Verify the path: `perfex_root/modules/realestate/`

### Step 3: Install the Module

1. Log in to your Perfex CRM admin panel
2. Navigate to **Setup** → **Modules**
3. Find "Real Estate Management" in the list of available modules
4. Click the **Install** button
5. Wait for the installation to complete (database tables will be created automatically)
6. Click the **Activate** button to enable the module

### Step 4: Configure Permissions

1. Navigate to **Setup** → **Staff** → **Roles**
2. Select each role you want to grant access to
3. Find "Real Estate" in the permissions list
4. Set appropriate permissions:
   - **View**: View all real estate data
   - **Create**: Add new projects, plots, bookings, etc.
   - **Edit**: Modify existing records
   - **Delete**: Remove records

### Step 5: Start Using the Module

1. Look for the new **Real Estate** menu in the admin sidebar
2. Start by creating your first project under **Real Estate** → **Projects**
3. Add plots to the project under **Real Estate** → **Plots**
4. Create bookings for customers under **Real Estate** → **Bookings**
5. Record transactions under **Real Estate** → **Accounts**
6. Assign team members under **Real Estate** → **Team**

## Database Tables Created

The module automatically creates the following tables during installation:

- `tblrealestate_projects` - Stores real estate projects
- `tblrealestate_plots` - Stores plots within projects
- `tblrealestate_bookings` - Stores customer bookings
- `tblrealestate_transactions` - Stores payment transactions
- `tblrealestate_team_assignments` - Stores team member assignments

## Menu Structure

After successful installation, the following menu items will appear:

```
Real Estate
├── Dashboard
├── Projects
├── Plots
├── Bookings
├── Accounts
└── Team
```

## Troubleshooting

### Module Not Appearing in Modules List

- Clear your browser cache
- Check file permissions (should be 644 for files, 755 for directories)
- Verify the module files are in the correct directory

### Installation Fails

- Check PHP error logs for specific errors
- Verify database user has CREATE TABLE permissions
- Ensure MySQL version is 5.6 or higher

### Permission Issues

- Clear application cache
- Log out and log back in
- Check role permissions in Setup → Staff → Roles

### Database Errors

- Verify database connection settings
- Check if tables already exist (may need to drop and reinstall)
- Ensure database user has sufficient privileges

## Uninstallation

To uninstall the module:

1. Navigate to **Setup** → **Modules**
2. Find "Real Estate Management"
3. Click **Deactivate** first
4. Then click **Uninstall**
5. Confirm the action

**Note**: Uninstalling will remove all module data and cannot be undone. Make sure to backup your database before uninstalling.

## Support

For issues or questions:
- Check the README.md file for detailed documentation
- Review the module structure and code
- Contact your system administrator

## Version Information

- **Module Version**: 1.0.0
- **Compatible with**: Perfex CRM 2.3.* or higher
- **Last Updated**: December 2025

## Next Steps

After installation:
1. Familiarize yourself with the dashboard
2. Create your first project
3. Add plots to the project
4. Start managing bookings
5. Track transactions and payments
6. Assign team members to projects

Enjoy using the Real Estate Management Module!
