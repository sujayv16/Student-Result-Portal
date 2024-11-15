# Student Result Portal

The **Student Result Portal** is a secure, web-based application built using **PHP** and **MySQL**, designed to manage student registration, academic results, and reporting functionalities. The portal enables students to view their results and download them, while administrators and teachers can efficiently manage student records, results, and classes. This application implements several advanced database and security features to ensure smooth operation, accurate data handling, and secure access control.

---
## Demo

Check out a live demo of this project here:

[Demo Link](https://drive.google.com/file/d/17ppnOVlpifkI9gu8Vg2T-d-Dhx_anhPd/view?usp=sharing) 

## Table of Contents
1. [Introduction](#introduction)
2. [Project Members](#project-members)
3. [Acknowledgments](#acknowledgments)
4. [Features](#features)
   - [Student Registration and Management](#student-registration-and-management)
   - [Class and Results Management](#class-and-results-management)
   - [Reporting and Performance Analysis](#reporting-and-performance-analysis)
   - [User Authentication and Session Management](#user-authentication-and-session-management)
   - [Data Privacy and Masking](#data-privacy-and-masking)
   - [Dynamic Query Building](#dynamic-query-building)
   - [Transaction Management](#transaction-management)
5. [Technologies Used](#technologies-used)
6. [Database Functionalities](#database-functionalities)
7. [Installation Instructions](#installation-instructions)
8. [Contributing](#contributing)

## Team Members
- **Viswanadhapalli Sujay** - B22CS063
- **Sonajoke Nikshiptha** - B22CS050
- **Kandrathi Sai Aishwarya** - B22CS028

## Acknowledgments
We extend our gratitude to **Dr. Suchetana Chakraborty** for guidance and support throughout this project.


---

## Introduction

The **Student Result Portal** provides an easy-to-use platform for managing student data and academic results. It caters to both administrative and student users. Administrators can add and update student records, manage class and subject details, declare results, and generate reports. Students can view their results securely, track performance trends, and download reports in PDF format. 

---

## Project Members

- **Viswanadhapalli Sujay** - B22CS063
- **Sonajoke Nikshiptha** - B22CS050
- **Kandrathi Sai Aiswarya** - B22CS028

---

## Acknowledgments

We would like to express our sincere gratitude to the following individuals and entities for their support throughout the development of this project:

- **Dr. Suchetana Chakraborty**, for her expert guidance and support during the development of this project.
- Our peers and mentors, whose feedback helped us refine the features and functionalities of this portal.

---

## Features

### Student Registration and Management
- **Student Registration**: Administrators can register new students by entering essential information like name, roll number, date of birth, and more. The system ensures the data’s accuracy by validating inputs, such as preventing the use of duplicate roll numbers.
- **Update & Delete**: Admins can modify existing student records or delete students when necessary to maintain an up-to-date database.

### Class and Results Management
- **Class Management**: Teachers and administrators can create, update, and delete class records. This includes associating subjects, teachers, and schedules with specific classes.
- **Results Management**: Teachers can enter student exam scores and calculate percentages. The system also supports batch processing, where multiple results can be uploaded at once. **Transaction management** ensures that these operations are executed reliably and consistently, preserving data integrity.

### Reporting and Performance Analysis
- **Report Generation**: The system can generate various academic reports for students, such as performance summaries, attendance statistics, and average grade reports.
- **Performance Analysis**: Using SQL aggregate functions (e.g., `AVG()`, `COUNT()`, `SUM()`), the system summarizes data to generate trend reports. These reports allow administrators to track changes in student performance over time and make informed decisions.

### User Authentication and Session Management
- **Authentication**: The system ensures only authorized users (students, teachers, and admins) can access their respective sections by implementing user authentication mechanisms. Passwords are hashed for security.
- **Session Management**: The application uses session management to keep users logged in during their session. It also includes mechanisms to prevent unauthorized access by logging out users after a period of inactivity.

### Data Privacy and Masking
- **Data Masking**: Sensitive information such as student roll numbers is masked for non-authorized users, ensuring that only users with the proper privileges can view complete records. For example, roll numbers may be partially displayed, such as showing only the last few digits.
- **Role-Based Access Control (RBAC)**: Only authorized roles, such as administrators or teachers, can access and view complete student records, enhancing security and data privacy.

### Dynamic Query Building
- **Customizable Queries**: The system supports dynamic query building, allowing users to filter and search data based on various criteria such as class name, subject, or department. This ensures that users can interact with the system flexibly and retrieve results based on their input.
- **Enhanced User Experience**: The dynamic queries are built on the fly based on user inputs, which allows the system to generate custom reports, search results, and filters tailored to the user’s needs.

### Transaction Management
- **ACID Compliance**: Transaction management ensures that database operations are atomic, consistent, isolated, and durable (ACID properties). All operations are treated as a single transaction, ensuring that if an error occurs, no partial data is committed, maintaining the integrity of the data.
- **Savepoints**: For more granular control, the system supports savepoints within transactions. This allows rolling back to specific points in a transaction without affecting the entire process, improving fault tolerance and reliability.

---

## Technologies Used

- **Frontend**: 
  - HTML, CSS, JavaScript
  - Bootstrap (for responsive design)

- **Backend**: 
  - PHP (Version 5.6–7.1)
  
- **Database**: 
  - MySQL (for storing student, class, and results data)

- **Development Tools**: 
  - XAMPP/WAMP/MAMP/LAMP server for local testing

---

## Database Functionalities

The system employs a robust relational database model with the following key operations:

1. **Student Management**
   - **Add Student**: Register new students with details like name, roll number, and class.
   - **Update Student**: Modify existing student details such as name or class.
   - **Delete Student**: Remove a student’s record from the system.
   - **View Student**: Retrieve student details based on roll number.

2. **Results Management**
   - **Declare Result**: Insert results for a specific student in a subject.
   - **Update Result**: Modify a student's existing result in a subject.
   - **View Result**: Students can view their results by searching with their roll number.

3. **Class Management**
   - **Add Class**: Add new class records, specifying associated subjects, teachers, and schedules.
   - **Update Class**: Modify class details, such as subject or teacher information.
   - **Delete Class**: Remove a class record from the system.

4. **Subject Management**
   - **Add Subject**: Insert new subjects into the database.
   - **Update Subject**: Modify existing subject details.
   - **Delete Subject**: Remove subjects from the system.

---

## Installation Instructions

1. **Download and Unzip**:
   - Download the project repository as a ZIP file and unzip it.

2. **Move Files to Server Directory**:
   - Place the unzipped files in the server directory (e.g., `c:/xampp/htdocs/` if using XAMPP).

3. **Database Configuration**:
   - Open phpMyAdmin.
   - Create a new database named `student_result_portal`.
   - Import the `student_result_portal.sql` file into the newly created database.

4. **Launch Application**:
   - In your browser, navigate to `http://localhost/student_result_portal/`.

5. **Admin Login**:
   - Use the following default credentials to log in as admin:
     - **Username**: admin
     - **Password**: 123

---

## Contributing

To contribute:
1. Fork this repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Commit your changes (`git commit -m 'Add a new feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Open a pull request.

---

