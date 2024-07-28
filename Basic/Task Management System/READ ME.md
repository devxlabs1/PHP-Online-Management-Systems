Sure, Koder! Here is a step-by-step guide for setting up the task management system, including creating the database and importing the necessary files.

---

# Task Management System Setup Guide

Welcome to the setup guide for the Task Management System! Follow the steps below to set up your environment, create the necessary database, and run the application.

## Prerequisites

Before you begin, make sure you have the following installed:
- PHP (version 7.4 or higher)
- MySQL
- A web server (e.g., Apache, XAMPP, WAMP)

## Steps

### 1. Download the Source Code

1. Download repository from GitHub:

2. Navigate to the project directory:

### 2. Set Up the Database

1. Open your phpMyAdmin to create the database.

2. Create a new database:
    ```sql
    CREATE DATABASE task_management;
    ```

3. Select the database:
    ```sql
    USE task_management;
    ```

4. Create the `tasks` table:
    ```sql
    
    CREATE TABLE tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        status ENUM('Pending', 'Completed') NOT NULL DEFAULT 'Pending'
    );
    ```

### 3. Configure the Database Connection

1. Open the `db.php` file in your preferred code editor.

2. Update the database connection details to match your MySQL credentials:
3. 
    ```php
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "task_management";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    ?>
    ```

### 4. Running the Application

1. Start your web server (e.g., Apache via XAMPP or WAMP).

2. Place the project directory inside your web server's root directory (e.g., `htdocs` for XAMPP).

3. Open your web browser and navigate to:
    ```
    http://localhost/task-management-system
    ```

4. You should see the Task Management System home page.

### 5. Using the Application

1. **Add a New Task**: Click the `+` button at the top right to add a new task.
2. **Edit a Task**: Click the `Edit` button next to a task to modify its details.
3. **Delete a Task**: Click the `Delete` button next to a task to remove it.
4. **Search and Filter Tasks**: Use the search box and status filter to find specific tasks.

---

## Troubleshooting

- **Database Connection Issues**: Ensure your MySQL server is running and the credentials in `db.php` are correct.
- **Page Not Loading**: Check that your web server is running and the project is placed in the correct directory.

If you encounter any issues or have any questions, feel free to open an issue on the GitHub repository.

Happy coding!
