<?php
// Simple role functions without authentication
function isLoggedIn() {
    return true; // Always return true since we're not using authentication
}

function hasRole($role) {
    return true; // Always return true since we're not using authentication
}

// These functions are kept for compatibility but don't do anything
function requireLogin() {
    // Do nothing
}

function requireAdmin() {
    // Do nothing
}

function requireProfessor() {
    // Do nothing
}
?>
