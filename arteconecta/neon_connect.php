<?php

// Script para crear tablas directamente en Neon Tech

try {
    // Conectarse a Neon Tech con el endpoint ID especificado
    $dsn = "pgsql:host=ep-wandering-pine-acgrhd1v-pooler.sa-east-1.aws.neon.tech;dbname=BDarteconecta;options=endpoint=ep-wandering-pine-acgrhd1v";
    $user = "BDarteconecta_owner";
    $password = "npg_F8X3rBgToWkd";
    
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "¡Conexión exitosa a Neon Tech!\n";
    
    // Crear la extensión uuid-ossp
    $pdo->exec('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
    echo "Extensión uuid-ossp habilitada\n";
    
    // Si llegamos aquí, la conexión fue exitosa
    echo "La conexión a Neon Tech fue exitosa. Puedes usar este script para ejecutar migraciones manualmente si es necesario.\n";
    
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage() . "\n";
}
?>
