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
method | url | json | description
-------|-----|------|------------
POST | api/register | (name, email, password, password_confirmation, role_id, address(nullable), phone_number, img_url(nullable)) | register
POST | api/login | (email, password) | login
GET | api/logout | - | logout

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