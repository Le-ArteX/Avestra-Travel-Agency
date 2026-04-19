# Avestra Travel Agency
------------------------------------------------------------------------------------------------------------

Avestra Travel Agency is a web-based platform for managing travel bookings, hotels, tours, tickets, and user profiles. The project is organized into Admin and User modules, each with their own controllers, database scripts, views, and assets.

## Features

- **Admin Panel**
  - Manage hotels, tours, tickets, and users
  - View and respond to contact messages
  - Profile management and settings
  - Payment and booking status updates
  - Maintenance mode support
  - Reports and analytics

- **User Panel**
  - Browse and book hotels, tours, and tickets
  - Manage personal profile and bookings
  - Contact support

- **Authentication**
  - Login, signup, and password recovery
  - OTP verification for enhanced security

- **Utilities**
  - Email notifications (SMTP)
  - Profile and ticket image uploads
  - Dark mode support

## Project Structure

```
Admin/
  controller/        # Admin controllers (PHP)
  database/          # Database scripts and data access (PHP)
  images/            # Images and user profile pictures
  js/                # JavaScript files for admin features
  styleSheets/       # CSS styles for admin views
  utils/             # Utility scripts (mail, OTP, etc.)
  validation/        # Validation scripts
  views/             # Admin-facing PHP views
User/
  controller/        # User controllers (PHP)
  database/          # User database scripts
  images/            # User images
  js/                # JavaScript files for user features
  styleSheets/       # CSS styles for user views
  views/             # User-facing PHP views
check_user.php       # User session check script
```
Data Management: Full CRUD control to manage Users, Tour Packages, Hotels, and Tickets.

Operations & Reports: Track overall metrics through the reports dashboard.

Booking & Financials: Effortless tracking and management of Bookings and processed Payments.

Customer Support: Centralized view to review and manage incoming contact messages.

Admin Settings: Tailor admin profile parameters, settings, and adapt the interface with Dark Mode.

🛠️ Technology Stack

## Requirements

Backend: PHP

Database: MySQL (Database schema included in avestra-travel-agency.sql)

Frontend: HTML, CSS, JavaScript (Icons via FontAwesome)

Features: Email/SMTP integration for verification and notifications.
- ------------------------------------------------------------------------------------------------------------
🚀 Live Demo: https://avestratravelagency.page.gd/



