<?php
// public/index.php

require_once __DIR__ . '/../app/bootstrap.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard.php");
} else {
    header("Location: ../login.php");
}
exit();