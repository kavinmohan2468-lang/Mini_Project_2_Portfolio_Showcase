<?php
try {
    $db = new PDO(
        "mysql:host=localhost;dbname=assignment_system;charset=utf8mb4",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    exit("Database connection failed.");
}

function query($sql, $data = []) {
    global $db;
    $stmt = $db->prepare($sql);
    $stmt->execute($data);
    return $stmt;
}