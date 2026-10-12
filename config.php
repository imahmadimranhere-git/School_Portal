<?php
// Database settings (XAMPP ke default)
$host = 'localhost';
$db   = 'school_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Database connect nahi hua: ' . htmlspecialchars($e->getMessage()));
}

session_start();

// Output ko safe banane ke liye (XSS se bachao)
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

// Ek dafa dikhne wala message
function flash($msg, $type = 'success') { $_SESSION['flash'] = [$msg, $type]; }
function redirect($page) { header("Location: $page"); exit; }
