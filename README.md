
# Advanced-Task-Management-System


- Ademola Plumptre [dplumptre@yahoo.com]
- 1.0, Feb 26, 2025: Readme...
- Laravel 12
- git Url : https://github.com/dplumptre/Advanced-Task-Management-System

The Task Management System is designed to allow users to effectively manage their tasks through a backend API (Laravel) and a ReactJS frontend. Users will be able to perform CRUD operations on tasks, upload tasks in bulk using an Excel file, process the file asynchronously with a job/queue system, and upload/display task images.




## Features

- Task Management: Users can create, read, update, and delete tasks.
- Excel File Upload: Bulk addition of tasks via an Excel file upload.
- Background Processing: Task files are processed asynchronously using a job/queue system.
- Task Image Upload: Allows users to upload images for tasks and display them within the system.




## Requirements

-   Must have installed composer on your machine
-   PHP 8.3 or higher
-   Laravel 12 or higher
-   you can check the laravel doc for more installation info  [here](https://laravel.com/docs/12.x)



## Steps

Clone the project

```bash
  git clone https://github.com/dplumptre/Advanced-Task-Management-System.git
```

Go to the project directory

```bash
  cd tms
```
Create .env file by copying and updating the .env.example. Then make sure you update these variables to your specification

```bash
APP_URL=http://tms.test  
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tms
DB_USERNAME=xxxxxxx
DB_PASSWORD=xxxxxxx
QUEUE_CONNECTION=database
```

Create Queue Table
```bash
php artisan queue:table
```

Install dependencies

```bash
  composer install
  php artisan migrate
```

Create a symbolic link for uploaded files
```bash
  php artisan storage:link
```

Start the app depending on your setup

```bash
  php artisan serve or http://tms.test
```
