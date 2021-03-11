<?php session_start();
/**
 * Template Name: Clear Page
 */

// DESTROY SESSION & COOKIE ----------------------------
session_destroy();
header('Location: login.php'); exit;

?>
