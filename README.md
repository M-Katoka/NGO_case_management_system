📊 NGO Case Management Information System (CMIS)A robust, web-based Case Management Information System designed for non-governmental organizations (NGOs) to efficiently track, manage, and report on client cases, support services, and social interventions.🌟 Key Features🔐 User Authentication & Role ManagementMulti-role support (Admin, Case Worker / Social Worker, Supervisor).Secure password hashing and session-based access control.👤 Client & Beneficiary ManagementCentralized directory for intake and client profiles.Unique case registration numbers and demographic tracking.📂 Case Lifecycle & TrackingFile and track cases from intake to resolution/closure.Status updates (Open, In Progress, Pending, Closed).Detailed case notes and interaction history.🤝 Service & Referral TrackingRecord services provided (legal aid, medical care, psycho-social support, financial aid).External partner referral management.📈 Analytics & ReportingStatistical breakdown of cases by status, age group, gender, and service type.Exportable reports for funding partners and organizational audits.🛠️ Tech StackBackend: PHP (Native / Procedural / OOP)Database: MySQL / MariaDBFrontend: HTML5, CSS3, JavaScript, Bootstrap / Tailwind CSSEnvironment: XAMPP / WAMP / LAMP Stack🚀 Installation & Setup Guide1. PrerequisitesEnsure you have the following installed on your machine:XAMPP (or any web server with PHP and MySQL support)Git2. Clone the RepositoryOpen your terminal/command prompt and clone this repository into your XAMPP htdocs folder:cd C:\xampp\htdocs\
git clone https://github.com/M-Katoka/NGO_case_management_system.git ngo_system
3. Database ConfigurationStart the Apache and MySQL modules in your XAMPP Control Panel.Open your web browser and go to http://localhost/phpmyadmin.Create a new database named:ngo_system
Import the database schema:Select the ngo_system database.Click the Import tab.Choose the database SQL file provided in the root folder (e.g., schema.sql or ngo_system.sql).Click Go.4. Database Connection SetupCheck your database configuration file (typically config.php, db.php, or db_connect.php) and ensure the connection details match your local MySQL configuration:<?php
$host     = "localhost";
$username = "root";
$password = "";          // Default XAMPP password is empty
$database = "ngo_system";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
5. Launch the ApplicationOpen your browser and navigate to:http://localhost/ngo_system/
🔒 Default Login Credentials (For Testing)Note: Change these credentials immediately after setting up in production.RoleUsername / EmailPasswordAdministratoradmin@ngo.orgadmin123Case Workercaseworker@ngo.orguser123📁 Recommended Folder Structurengo_system/
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
🤝 ContributingContributions are welcome! Follow these steps to contribute:Fork the project.Create your Feature Branch (git checkout -b feature/NewFeature).Commit your changes (git commit -m 'Add some NewFeature').Push to the Branch (git push origin feature/NewFeature).Open a Pull Request.
