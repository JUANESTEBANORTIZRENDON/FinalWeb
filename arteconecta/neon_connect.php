<?php

declare(strict_types=1);

// Script para verificar conectividad con Neon usando variables de entorno (.env)

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? '';
$port = $_ENV['DB_PORT'] ?? '5432';
$database = $_ENV['DB_DATABASE'] ?? '';
$username = $_ENV['DB_USERNAME'] ?? '';
$password = $_ENV['DB_PASSWORD'] ?? '';

if ($host === '' || $database === '' || $username === '') {
    fwrite(STDERR, "Faltan variables DB_* en .env\n");
    exit(1);
}

$endpointId = explode('.', $host)[0] ?? '';
$dsn = "pgsql:host={$host};dbname={$database};port={$port};options=endpoint={$endpointId}";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    echo "Conexión exitosa a Neon.\n";

    // Crear la extensión uuid-ossp (si tienes permisos)
    $pdo->exec('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
    echo "Extensión uuid-ossp habilitada.\n";
} catch (PDOException $e) {
    fwrite(STDERR, "Error de conexión: " . $e->getMessage() . "\n");
    exit(1);
}

