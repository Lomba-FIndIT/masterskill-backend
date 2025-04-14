## How To Run
1. clone this repository
2. install php and composer
   * windows / powershell
   ```
   # Run as administrator...
   Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.4'))
   ```
   * Linux
   ```
   /bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.4)"
   ```

3. make file .env and paste all content in .env.example inside it.
4. install all dependencies needed
   ```
   composer i
   npm i
   ```
5. create new app key
   ```
   php artisan key:generate
   ```
6. create new database named "masterskill" and turn on database server, then migrate all tables with this command.
   ```
   # create your database first and turn on the database server
   php artisan migrate:fresh --seed
   ```
7. run all test case to make sure the program running properly.
   ```
   php artisan test
   ```
8. run the server
   ```
   php artisan serve
   ```

## Endpoint
method | url | request json | response | description | protected
-------|-----|--------------|----------|-------------|----------
POST | api/register | (name, email, password, password_confirmation, role_id, address(nullable), phone_number(nullable), img_url(nullable)) | - | register | [ ]
POST | api/login | (email, password) | (token) | login | [ ]
GET | api/logout | - | - | logout | [x]
POST | api/courses | (course_name, category, instructor_id, price, img(file)) | (id, course_name, category, instructor, price, img_url, total_duration, ratings) | create new course | [x]
GET | api/courses | - | [(id, course_name, category, instructor, price, img_url, total_duration, ratings)] | get all courses | [ ]
GET | api/courses/:id | - | (id, course_name, category, instructor, price, img_url, total_duration, ratings) | get course by id | [x]
PUT | api/courses/:id | (course_name, category, instructor_id, price, img(file)) | (id, course_name, category, instructor, price, img_url, total_duration, ratings) | update course by id | [x]
DELETE | api/courses/:id | - | - | delete course by id | [x]
GET | api/courses/:id/join | - | - | student assigning course by id | [x]
DELETE | api/courses/:id/join | - | - | student cancelling course by id | [x]
POST | api/courses/:id/rate | (rate) | - | student give the course rate | [x]
GET | api/courses/:id/students | - | [(id, name, email, role_id, address, phone_number, img_url)] | get all students by course id | [x]
GET | api/courses/:id/videos | - | [(id, title, course_id, description, video_url)] | get all videos by course id | [x]
POST | api/users | (name, email, password, password_confirmation, phone_number, role_id) | (id, name, email, role_id, address, phone_number, img_url) | create user (instructor, hrd) | [x]
GET | api/users | - | [(id, name, email, role_id, address, phone_number, img_url)] | get all users | [x]
GET | api/user | - | (id, name, email, role_id, address, phone_number, img_url) | get login user profile | [x]
GET | api/users/:id | - | (id, name, email, role_id, address, phone_number, img_url) | get user by id | [x]
PUT | api/users | (id, name, email, address, phone_number, role_id, img(file)) | (id, name, email, role_id, address, phone_number, img_url) | update user profile | [x]
GET | api/users/:id/courses | - | [(id, course_name, category, instructor, orice, img_url, total_duration, ratings)] | student get all applied courses | [x]
DELETE | api/users/:id | - | - | delete user by id | [x]
POST | api/videos | (title, course_id, description, video(file)) | (id, title, course_id, description, video_url) | upload video | [x]
GET | api/videos | - | [(id, title, course_id, description, video_url)]) | get all videos | [x]
GET | api/videos/:id | - | (id, title, course_id, description, video_url) | get video by id | [x]
PUT | api/videos/:id | (title, course_id, description, video(file)) | (id, title, course_id, description, video_url) | update video by id | [x]
DELETE | api/videos/:id | - | - | delete video by id | [x]

## Database Structure
### User
attributes | description
-----------|------------
id | PRIMARY KEY, INT, NOT NULL
name | VARCHAR , NOT NULL
password | VARCHAR, NOT NULL
address | TEXT, NULLABLE
phone_number | VARCHAR, NOT NULL
role_id | FOREIGN KEY -> roles, NOT NULL, DEFAULT = 4
img_url | VARCHAR, NULLABLE

### Role
attributes | description
-----------|------------
id | PRIMARY KEY, INT, NOT NULL
role_name | ENUM ('admin', 'instructor', 'hrd', 'student'), NOT NULL

### Course
attributes | description
-----------|------------
id | PRIMARY KEY, INT, NOT NULL
course_name | VARCHAR, NOT NULL
category_id | FOREIGN KEY -> categories, NOT NULL
price | INT, NOT NULL
instructor_id | FOREIGN KEY -> users, NOT NULL
img_url | VARCHAR, NOT NULL
total_duration | INT, NOT NULL, DEFAULT = 0 (seconds)
ratings | DOUBLE, NULLABLE, DEFAULT = 0

### course_user
attributes | description
-----------|------------
id | PRIMARY KEY, INT, NOT NULL
course_id | FOREIGN KEY -> courses, NOT NULL
user_id | FOREIGN KEY -> users, NOT NULL
payment_id | FOREIGN KEY -> payments, NOT NULL
rating | DOUBLE, NOT NULL, DEFAULT = 0

### Category
attributes | description
-----------|------------
id | PRIMARY KEY, INT, NOT NULL
category_name | VARCHAR, NOT NULL

### Payment
attributes | description
-----------|------------
id | PRIMARY KEY, INT, NOT NULL
paid | BOOLEAN, NOT NULL
student_id | FOREIGN KEY -> users, NOT NULL

### Video
attributes | description
-----------|------------
id | PRIMARY KEY, INT, NOT NULL
title | VARCHAR, NOT NULL
course_id | FOREIGN KEY -> courses, NOT NULL
description | TEXT, NOT NULL
video_url | VARCHAR, NOT 
duration | INT, NOT NULL (second)
free | BOOLEAN, NOT NULL, DEFAULT = false