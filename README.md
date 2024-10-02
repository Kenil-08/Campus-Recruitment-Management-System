# Campus Recruitment Management System

## Overview

The **Campus Recruitment Management System** is a web-based application designed to facilitate the recruitment process for campus placements. The system features two key modules: **Student Module** and **TPO (Training and Placement Officer) Module**. It allows students to view and apply for jobs, while the TPO can manage job postings, track student applications, and monitor placement statistics.

## Features

### 1. Student Module
- **View Available Jobs**: Students can view all the jobs posted by the TPO.
- **Apply for Jobs**: Students can apply for jobs and track the status of their applications.
- **Profile Management**: Students can update their personal and academic information as part of their profiles.

### 2. TPO Module
- **Post Jobs**: The TPO can create and post jobs for students, specifying details like job title, description, requirements, and salary.
- **View All Posted Jobs**: TPO can manage all the jobs they have posted, including updating the job status (open/closed).
- **Student Management**: View and manage the profiles and details of all students.
- **Application Management**: Track all job applications submitted by students and update the status of applications.
- **Student Placement Status**: Update the placement status of students (placed/unplaced) based on job outcomes.
- **Job Status Management**: Update job status to either "open" or "closed."
- **Analytics & Reports**:
  - View a **pie chart** showing the distribution of placed vs. unplaced students.
  - View a **bar chart** displaying the **minimum, maximum, and average salary packages** offered to students.

## Technology Stack
- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP, MySQL
- **Libraries and Tools**: 
  - DataTables for sorting and searching through data tables
  - Chart.js for generating pie and bar charts
  - jQuery for enhancing user experience
  - Bootstrap for UI components
 
## Setup Instructions

### Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/campus-recruitment-management-system.git
   ```

2. Navigate into the project directory:
   ```bash
   cd campus-recruitment-management-system
   ```

3. Import the database:
   - Create a new MySQL database.
   - Import the provided `crms_db.sql` file into your database. 

### Login Information

Use the following credentials to log in as a TPO or a Student:

- **TPO (Training and Placement Officer)**:
  - **Email**: `tpo@example.edu.in`
  - **Password**: `tpo@example`

### Important Packages

The following packages are used in this project for specific functionalities. Make sure to download and install them to ensure the project works correctly.

- **PHPMailer** (for sending emails):
  - Repository: [PHPMailer GitHub](https://github.com/PHPMailer/PHPMailer)
  - Download It and place into your prject folder.

- **PHPExcel** (for working with Excel files):
  - Repository: [PHPExcel GitHub](https://github.com/PHPOffice/PHPExcel)
  - Download it and place Classes folder into your project folder.

### Create Uploads Directory

1. In the root directory of your project, create a folder named `uploads`.
2. Inside the `uploads` folder, create the following two subfolders:
   - `job_document` (for storing job-related documents)
   - `resume` (for storing student resumes)

## Conclusion

The Campus Recruitment Management System is designed to streamline the recruitment process, making it easier for students to find and apply for job opportunities while enabling TPOs to efficiently manage job postings and student applications. This system enhances communication and organization within the recruitment process, providing valuable insights through data visualization.

We encourage contributions and feedback to improve this project. For any issues or feature requests, please feel free to open an issue on the repository.

Thank you for using the Campus Recruitment Management System!



