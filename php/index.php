<?php
// Start session at the very beginning
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include auth functions
include_once 'includes/auth.php';

// Main entry point
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Check if certain pages require authentication
if (in_array($page, ['admin', 'admin-news', 'admin-approve-deny', 'admin-user-management'])) {
    requireAdmin();
} elseif (in_array($page, ['professor', 'professor-project'])) {
    requireProfessor();
}


// Special handling for login page
if ($page === 'login') {
    include_once 'pages/login.php';
    exit; // Exit after including login page to prevent further output
}

// Include header
include_once 'includes/header.php';

// Include navigation
include_once 'includes/navigation.php';

// Route to the correct page
switch ($page) {
    case 'projects':
        include_once 'pages/projects.php';
        break;
    case 'project-detail':
        include_once 'pages/project-detail.php';
        break;
    case 'news':
        include_once 'pages/news.php';
        break;
    case 'submit':
        include_once 'pages/submit.php';
        break;
    case 'admin':
        include_once 'pages/admin.php';
        break;
    case 'admin-news':
        include_once 'pages/admin-news.php';
        break;
    case 'admin-approve-deny':
        include_once 'pages/admin-approve-deny.php';
        break;
     case 'user-management':               
        include_once 'pages/admin-user-management.php';
    break;
    case 'professor':
        include_once 'pages/professor.php';
        break;
    case 'professor-project':
        include_once 'pages/professor-project.php';
        break;
    default:
        include_once 'pages/home.php';
        break;
}

// Include footer
include_once 'includes/footer.php';
?>
