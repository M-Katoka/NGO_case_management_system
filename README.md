<<<<<<< HEAD
# 📊 NGO Case Management Information System (CMIS)

A robust, web-based Case Management Information System designed for non-governmental organizations (NGOs) to efficiently track, manage, and report on client cases, support services, and social interventions.

---

## 🌟 Key Features

* **🔐 User Authentication & Role Management**
  * Multi-role support (Admin, Case Worker / Social Worker, Supervisor).
  * Secure password hashing and session-based access control.

* **👤 Client & Beneficiary Management**
  * Centralized directory for intake and client profiles.
  * Unique case registration numbers and demographic tracking.

* **📂 Case Lifecycle & Tracking**
  * File and track cases from intake to resolution/closure.
  * Status updates (Open, In Progress, Pending, Closed).
  * Detailed case notes and interaction history.

* **🤝 Service & Referral Tracking**
  * Record services provided (legal aid, medical care, psycho-social support, financial aid).
  * External partner referral management.

* **📈 Analytics & Reporting**
  * Statistical breakdown of cases by status, age group, gender, and service type.
  * Exportable reports for funding partners and organizational audits.

---

## 🛠️ Tech Stack

* **Backend:** PHP (Native / Procedural / OOP)
* **Database:** MySQL / MariaDB
* **Frontend:** HTML5, CSS3, JavaScript, Bootstrap / Tailwind CSS
* **Environment:** XAMPP / WAMP / LAMP Stack

---

## 🚀 Installation & Setup Guide

### 1. Prerequisites
Ensure you have the following installed on your machine:
* [XAMPP](https://www.apachefriends.org/index.html) (or any web server with PHP and MySQL support)
* [Git](https://git-scm.com/)

---

### 2. Clone the Repository
Open your terminal/command prompt and clone this repository into your XAMPP `htdocs` folder:

```bash
cd C:\xampp\htdocs\
git clone https://github.com/M-Katoka/NGO_case_management_system.git ngo_system
```

---

### 3. Database Configuration
1. Start the **Apache** and **MySQL** modules in your XAMPP Control Panel.
2. Open your web browser and go to `http://localhost/phpmyadmin`.
3. Create a new database named:
   ```text
   ngo_system
   ```
4. Import the database schema:
   * Select the `ngo_system` database.
   * Click the **Import** tab.
   * Choose the database SQL file provided in the root folder (e.g., `schema.sql` or `ngo_system.sql`).
   * Click **Go**.

---

### 4. Database Connection Setup
Check your database configuration file (typically `config.php`, `db.php`, or `db_connect.php`) and ensure the connection details match your local MySQL configuration:

```php
<?php
$host     = "localhost";
$username = "root";
$password = "";          // Default XAMPP password is empty
$database = "ngo_system";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
```

---

### 5. Launch the Application
Open your browser and navigate to:
```text
http://localhost/ngo_system/
```

---

## 🔒 Default Login Credentials (For Testing)

> *Note: Change these credentials immediately after setting up in production.*

| Role | Username / Email | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin@ngo.org` | `admin123` |
| **Case Worker** | `caseworker@ngo.org` | `user123` |

---

## 📁 Recommended Folder Structure

```text
ngo_system/
│
├── assets/             # CSS styles, JS scripts, images, and vendor assets
├── config/             # Database connection and global system settings
├── includes/           # Reusable components (header, footer, sidebar)
├── modules/            # System features (clients, cases, reports)
│   ├── clients/        # Client management scripts
│   ├── cases/          # Case logging and updates
│   └── reports/        # Analytics and reporting scripts
├── database/           # SQL dump files (e.g., ngo_system.sql)
├── index.php           # Entry point / Login page
├── dashboard.php       # Main administrative dashboard
└── README.md           # Project documentation
```

---

## 🤝 Contributing

Contributions are welcome! Follow these steps to contribute:
1. **Fork** the project.
2. Create your Feature Branch (`git checkout -b feature/NewFeature`).
3. **Commit** your changes (`git commit -m 'Add some NewFeature'`).
4. **Push** to the Branch (`git push origin feature/NewFeature`).
5. Open a **Pull Request**.

---
=======
# 📊 NGO Case Management Information System (CMIS)

A robust, web-based Case Management Information System designed for non-governmental organizations (NGOs) to efficiently track, manage, and report on client cases, support services, and social interventions.

---

## 🌟 Key Features

* **🔐 User Authentication & Role Management**
  * Multi-role support (Admin, Case Worker / Social Worker, Supervisor).
  * Secure password hashing and session-based access control.

* **👤 Client & Beneficiary Management**
  * Centralized directory for intake and client profiles.
  * Unique case registration numbers and demographic tracking.

* **📂 Case Lifecycle & Tracking**
  * File and track cases from intake to resolution/closure.
  * Status updates (Open, In Progress, Pending, Closed).
  * Detailed case notes and interaction history.

* **🤝 Service & Referral Tracking**
  * Record services provided (legal aid, medical care, psycho-social support, financial aid).
  * External partner referral management.

* **📈 Analytics & Reporting**
  * Statistical breakdown of cases by status, age group, gender, and service type.
  * Exportable reports for funding partners and organizational audits.

---

## 🛠️ Tech Stack

* **Backend:** PHP (Native / Procedural / OOP)
* **Database:** MySQL / MariaDB
* **Frontend:** HTML5, CSS3, JavaScript, Bootstrap / Tailwind CSS
* **Environment:** XAMPP / WAMP / LAMP Stack

---

## 🚀 Installation & Setup Guide

### 1. Prerequisites
Ensure you have the following installed on your machine:
* [XAMPP](https://www.apachefriends.org/index.html) (or any web server with PHP and MySQL support)
* [Git](https://git-scm.com/)

---

### 2. Clone the Repository
Open your terminal/command prompt and clone this repository into your XAMPP `htdocs` folder:

```bash
cd C:\xampp\htdocs\
git clone https://github.com/M-Katoka/NGO_case_management_system.git ngo_system
```

---

### 3. Database Configuration
1. Start the **Apache** and **MySQL** modules in your XAMPP Control Panel.
2. Open your web browser and go to `http://localhost/phpmyadmin`.
3. Create a new database named:
   ```text
   ngo_system
   ```
4. Import the database schema:
   * Select the `ngo_system` database.
   * Click the **Import** tab.
   * Choose the database SQL file provided in the root folder (e.g., `schema.sql` or `ngo_system.sql`).
   * Click **Go**.

---

### 4. Database Connection Setup
Check your database configuration file (typically `config.php`, `db.php`, or `db_connect.php`) and ensure the connection details match your local MySQL configuration:

```php
<?php
$host     = "localhost";
$username = "root";
$password = "";          // Default XAMPP password is empty
$database = "ngo_system";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
```

---

### 5. Launch the Application
Open your browser and navigate to:
```text
http://localhost/ngo_system/
```

---

## 🔒 Default Login Credentials (For Testing)

> *Note: Change these credentials immediately after setting up in production.*

| Role | Username / Email | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin@ngo.org` | `admin123` |
| **Case Worker** | `caseworker@ngo.org` | `user123` |

---

## 📁 Recommended Folder Structure

```text
ngo_system/
│
├── assets/             # CSS styles, JS scripts, images, and vendor assets
├── config/             # Database connection and global system settings
├── includes/           # Reusable components (header, footer, sidebar)
├── modules/            # System features (clients, cases, reports)
│   ├── clients/        # Client management scripts
│   ├── cases/          # Case logging and updates
│   └── reports/        # Analytics and reporting scripts
├── database/           # SQL dump files (e.g., ngo_system.sql)
├── index.php           # Entry point / Login page
├── dashboard.php       # Main administrative dashboard
└── README.md           # Project documentation
```

---

## 🤝 Contributing

Contributions are welcome! Follow these steps to contribute:
1. **Fork** the project.
2. Create your Feature Branch (`git checkout -b feature/NewFeature`).
3. **Commit** your changes (`git commit -m 'Add some NewFeature'`).
4. **Push** to the Branch (`git push origin feature/NewFeature`).
5. Open a **Pull Request**.

---
>>>>>>> 400a57e (Add app core structure, setup scripts, routing controllers, and tests)
