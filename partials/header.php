<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../config.php";


// Get unread notification count (if logged in)
$unreadCount = 0;
if (!empty($_SESSION["user_id"])) {
    $uid = (int)$_SESSION["user_id"];
    $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM notifications WHERE user_id=? AND is_read=0");
    if ($stmt) {
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $unreadCount = (int)($res["c"] ?? 0);
    }
}


// Detect role safely
$role = strtolower(trim($_SESSION["role"] ?? ""));
?>
<!DOCTYPE html>
<html>
<head>
  <title>VU LostLink</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/style.css?v=20260304_2">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container">


    <!-- Logo -->
    <a class="navbar-brand fw-bold" href="index.php">
      VU LostLink
    </a>


    <!-- Mobile toggle -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>


    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="mainNavbar">


      <!-- Left side -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">


        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>


        <li class="nav-item">
          <a class="nav-link" href="browse_items.php">Browse</a>
        </li>


        <li class="nav-item">
          <a class="nav-link" href="about.php">About</a>
        </li>


        <li class="nav-item">
          <a class="nav-link" href="help.php">Help</a>
        </li>


        <!-- Chatbot only for normal users -->
        <?php if(!empty($_SESSION["user_id"]) && $role === "user"): ?>
      
        <?php endif; ?>


      </ul>


      <!-- Right side -->
      <ul class="navbar-nav ms-auto">


        <?php if(!empty($_SESSION["user_id"])): ?>


          <!-- Notifications -->
          <li class="nav-item">
            <a class="nav-link" href="notifications_page.php">
              Notifications
              <?php if($unreadCount > 0): ?>
                <span class="badge bg-danger ms-1">
                  <?= $unreadCount ?>
                </span>
              <?php endif; ?>
            </a>
          </li>


          <?php if($role === "admin"): ?>
            <li class="nav-item">
              <a class="nav-link" href="admin_dashboard.php">Admin Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="admin_users.php">Manage Users</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="user_dashboard.php">Dashboard</a>
            </li>
          <?php endif; ?>


          <li class="nav-item">
            <a class="nav-link text-warning" href="logout.php">Logout</a>
          </li>


        <?php else: ?>


          <li class="nav-item">
            <a class="nav-link" href="login.php">Login</a>
          </li>


          <li class="nav-item">
            <a class="nav-link fw-semibold" href="register.php">Register</a>
          </li>


        <?php endif; ?>


      </ul>


    </div>
  </div>
</nav>


<div class="container mt-4"></div>

