CRM Ticket Management System

A web-based CRM Ticket Management System developed using PHP & MySQL to manage support tickets efficiently with role-based access and ticket lifecycle tracking.

📌 Features

User registration and login

Ticket creation with file upload

Ticket status management

Pending

In Progress

Completed

On Hold

Ticket assignment system

Role-based access (Admin & User)

Dashboard overview

Secure session management

MySQL database integration

🗂 Project Structure
crm-ticket-system/
├── assets/
├── controllers/
├── database/
│   ├── schema.sql
│   ├── db_connection.sample.php
├── includes/
├── uploads/
│   └── .gitkeep
├── views/
├── config/
│   ├── config.sample.php
├── index.php
├── .gitignore
└── README.md

🚀 Installation & Setup
1️⃣ Clone the Repository
git clone https://github.com/YOUR_USERNAME/crm-ticket-system.git
cd crm-ticket-system

2️⃣ Create Configuration Files

Rename:

config/config.sample.php → config/config.php
database/db_connection.sample.php → database/db_connection.php


Update database credentials in both files.

3️⃣ Create Database

Import the schema file:

database/schema.sql


via phpMyAdmin or MySQL CLI:

SOURCE database/schema.sql;

4️⃣ Run the Project

Access from browser:

http://localhost/crm-ticket-system


OR

https://yourdomain.com

☁ Hosting Support

✅ Works on:

InfinityFree

XAMPP / WAMP / LAMP

Shared hosting

VPS servers

❌ Not compatible with Vercel (Vercel does not support PHP hosting).

🔒 Security

Sensitive files ignored by .gitignore

No database credentials pushed to GitHub

Upload folder protected by placeholder .gitkeep file

🛠 Tech Stack

PHP (Backend)

MySQL (Database)

HTML / CSS (Frontend)

JavaScript (Optional)

Apache Server
