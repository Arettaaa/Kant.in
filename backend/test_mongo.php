<?php
require 'vendor/autoload.php';

try {
    $m = new MongoDB\Client('mongodb+srv://admin_kantin:Test1234@cluster0.j5tdtx8.mongodb.net/db_kantin?authSource=admin');
    $databases = $m->listDatabases();
    echo "✅ Koneksi berhasil! Database list:\n";
    foreach ($databases as $db) {
        echo "- " . $db->getName() . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}