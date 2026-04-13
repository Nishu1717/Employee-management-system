# Employee Management System

## Overview
This was a group project for COMP-4150 (Advanced Database Topics) at the University of Windsor, built across three phases with two other team members. 
The project started from a blank schema and ended with a fully functional, role-based web application for managing employees, departments, projects, tasks, 
certifications, and performance scores. The challenge was not just to build a working app — it was to build one that followed real database design principles, 
applied proper server-side security, and produced something that resembled how an actual HR system would behave.

By Phase 3, you can log in as an Admin, Manager, or Employee and get a completely different experience depending on your role, with scoped dashboards, live-search 
tables, and an audit log that tracks every action in the system.

## What I Worked On
My contributions ran across all three phases. In Phase 1, I designed and implemented the core relational schema — working through the entity relationships, setting up 
primary and foreign keys, writing the constraints and indexes, building the views, and writing the analytical SQL queries that the application's reporting layer later
depended on. I also submitted a personal schema independently alongside the group version, which covered the core Employee, Department, Location, Project, Employee_Project, 
and Salary tables. In Phase 2, I worked on the PL/SQL components including stored procedures, triggers, and exception handling blocks across multiple question sets.

Phase 3 was where most of the engineering happened. I handled the frontend development — building the dashboard views and interface components for all three access roles 
and integrating them with the backend PHP logic. I worked on the database connection and query layer in PHP, and I ran systematic testing across all three roles to validate 
that the access controls, form submissions, AJAX endpoints, and audit logging were all behaving correctly. I also did code reviews across the codebase before final submission. 
I submitted a complete independent version of the Phase 3 application alongside the group's final deliverable.

## How the System Works
Three roles, three completely different experiences:

**Admin** — system-wide visibility. Can manage all users, activate and deactivate accounts, manage departments, and view the full audit log. Dashboard shows department 
budget breakdowns, role distribution, and active vs. inactive user counts via Google Charts.

**Manager** — team-scoped access. Can view their assigned employees, update performance scores (0–100), manage projects and tasks, and view audit logs scoped to their team. 
Dashboard shows employee status and task distribution for their group.

**Employee** — personal access only. Can view their assigned tasks, update task status, and edit their own profile. Dashboard shows a personal task analytics doughnut chart 
with completed, in-progress, and pending breakdowns.

## Security Features
Getting the security right was something I was deliberate about during Phase 3. Passwords are hashed using bcrypt — never stored as plain text. Every page checks the session
role before rendering anything, so there is no way to access admin or manager pages by manipulating the URL. All database queries use prepared statements to prevent SQL
injection. Database credentials are stored in a `.env` file via vlucas/phpdotenv rather than hardcoded anywhere in the codebase. Every login, logout, edit, update, and 
delete is written to an audit log table with the user ID, action description, timestamp, and IP address.

## Database Schema
The schema has 11 tables: Employee, Department, Project, Task, TimeLog, Certification, Role, Supervisor, DepartmentLocation, ProjectAssignment, and EmployeeContact. The 
analytical queries written in Phase 1 covered things like highest salaries by department, employees with the most project assignments, supervisory chains, unassigned 
employees, and average salary distributions — all of which fed into the dashboard and reporting views in Phase 3.

## Tech Stack
PHP 8.2, MySQL/MariaDB 10.4, HTML, CSS, JavaScript, jQuery, Bootstrap 5, Chart.js, Google Charts, XAMPP, Composer, phpMyAdmin, vlucas/phpdotenv

## How to Run

1. Clone the repository
2. Install XAMPP and start Apache and MySQL
3. Copy the project folder into `htdocs/`
4. Import `schema.sql` into phpMyAdmin as a new database
5. Rename `.env.example` to `.env` and fill in your database credentials
6. Run `composer install`
7. Open `http://localhost/Phase3/login.html` in your browser

## What I Would Do Next
- Move away from XAMPP to a proper Docker setup so the environment is portable and reproducible without manual configuration.
- Replace the jQuery AJAX calls with a cleaner REST API layer, which would make the frontend and backend easier to maintain independently.
- Add email notifications for key events like performance score updates or new project assignments, which would make it feel more like a real HR tool.
