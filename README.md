# Student Result Portal

**Student Result Portal** is a secure, web-based application developed using PHP and MySQL. Designed as a DBMS course project, this portal allows students to access their examination results in a straightforward and secure way, while providing administrative functionalities for result management.

---

## Team Members
- **Viswanadhapalli Sujay** - B22CS063
- **Sonajoke Nikshiptha** - B22CS050
- **Kandrathi Sai Aishwarya** - B22CS028

## Acknowledgments
We extend our gratitude to **Dr. Suchetana Chakraborthy** for guidance and support throughout this project.

---

## Project Overview

The **Student Result Portal** is designed to simplify result management for educational institutions and to provide students with easy access to their results. It ensures secure login for students and administrative privileges for result management, minimizing manual errors and enhancing efficiency.

## Features

### Student
- **Login**: Students log in securely to view their results.
- **Result Access**: View examination results by entering a unique Roll ID.
- **PDF Download**: Students can download their results in PDF format.

### Admin
- **Admin Dashboard**: Access all system functionalities from a single dashboard.
- **Class & Subject Management**: Add, update, and manage classes and subjects.
- **Student Management**: Register new students, update student information, and manage student records.
- **Result Declaration**: Declare and update examination results.
- **Account Management**: Admins can update their passwords for secure access.

---

## Technologies Used

- **Frontend**: HTML, CSS, JavaScript, Bootstrap (for responsive design)
- **Backend**: PHP (version 5.6–7.1)
- **Database**: MySQL
- **Development Tools**: XAMPP/WAMP/MAMP/LAMP server for local testing

---

## Installation Instructions

1. **Download and Unzip**:
   - Download the project repository as a ZIP file and unzip it.

2. **Move Files to Server Directory**:
   - Place the unzipped files in the server directory, typically `c:/xampp/htdocs/` if using XAMPP.

3. **Database Configuration**:
   - Open phpMyAdmin.
   - Create a new database named `student_result_portal`.
   - Import the `student_result_portal.sql` file located in the project folder into this database.

4. **Launch Application**:
   - In your browser, navigate to `http://localhost/student_result_portal/`.

5. **Admin Login**:
   - Use the following default login credentials:
     - **Username**: admin
     - **Password**: 123

---

## Database Schema

### Tables

- **Students**: Manages student records, including student ID, name, class, and login credentials.
- **Subjects**: Stores subject information, including subject codes and names.
- **Results**: Manages results data, linked to students and subjects.
- **Admins**: Stores admin account details, allowing CRUD operations on all tables.

### Relationships
- **Student - Subject - Result**: Each student has multiple subjects with corresponding results.
- **Admin Privileges**: Admins have full access to modify records in all tables.

---

## Modules

1. **Student Module**:
   - Allows students to log in, view results, and download them in PDF format.

2. **Admin Module**:
   - Provides complete access for managing students, classes, subjects, and results.

3. **Result Module**:
   - Displays results in a user-friendly format, with secure download options for students.

---

## Usage

1. **Admin Operations**:
   - Add or update classes, subjects, and student records.
   - Declare or edit results for each student.

2. **Student Access**:
   - Students log in with their credentials to view their results and download them in PDF format.

---

## Contributing

To contribute:
1. Fork this repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Commit your changes (`git commit -m 'Add a new feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Open a pull request.

---


