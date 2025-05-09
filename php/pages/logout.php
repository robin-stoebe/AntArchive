<?php
// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to home page
header('Location: index.php');
exit;
?>

<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50">
    <div class="text-center">
        <h1 class="text-2xl font-bold mb-4">Logging out...</h1>
        <p>You will be redirected to the home page.</p>
        <p class="mt-4">If you are not redirected, <a href="index.php" class="text-blue-600 hover:underline">click here</a>.</p>
    </div>
</div>
