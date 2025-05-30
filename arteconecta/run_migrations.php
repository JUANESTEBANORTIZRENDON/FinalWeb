<?php

// Este script ejecuta las migraciones de Laravel manualmente usando una conexión directa a Neon Tech

// Cargar el autoloader de Composer para acceder a las clases de Laravel
require __DIR__ . '/vendor/autoload.php';

// Cargar variables de entorno desde .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

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
    
    // Verificar si la tabla migrations existe
    $stmt = $pdo->query("SELECT to_regclass('migrations') as exists");
    $result = $stmt->fetch();
    
    if ($result['exists'] === null) {
        // Crear la tabla de migraciones
        $pdo->exec("
            CREATE TABLE migrations (
                id SERIAL PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INTEGER NOT NULL
            )
        ");
        echo "Tabla migrations creada\n";
    }
    
    // Ejecutar las migraciones: Users table
    echo "Creando tabla users...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            email_verified_at TIMESTAMP NULL,
            password VARCHAR(255) NOT NULL,
            remember_token VARCHAR(100) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            avatar_path VARCHAR(255) NULL,
            bio TEXT NULL,
            user_type VARCHAR(20) NOT NULL CHECK (user_type IN ('artist', 'visitor')),
            website_url VARCHAR(255) NULL,
            social_media JSONB NULL,
            reset_password_token VARCHAR(100) NULL,
            reset_password_token_expires_at TIMESTAMP NULL
        )
    ");
    
    // Tabla password_reset_tokens
    echo "Creando tabla password_reset_tokens...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS password_reset_tokens (
            email VARCHAR(255) PRIMARY KEY,
            token VARCHAR(255) NOT NULL,
            created_at TIMESTAMP NULL
        )
    ");
    
    // Tabla art_categories
    echo "Creando tabla art_categories...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS art_categories (
            id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
            name VARCHAR(100) NOT NULL UNIQUE,
            description TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Insertar categorías predeterminadas
    echo "Insertando categorías predeterminadas...\n";
    $defaultCategories = [
        ['Pintura', 'Paintings using oil, acrylic, watercolor, etc.'],
        ['Escultura', '3D artworks with various materials.'],
        ['Fotografía', 'Photography captured with a camera.'],
        ['Digital', 'Digitally created artwork.'],
        ['Ilustración', 'Graphic or illustrative art.'],
        ['Mixta', 'Mixed media art.']
    ];
    
    $insertStmt = $pdo->prepare("INSERT INTO art_categories (name, description) VALUES (?, ?)");
    
    foreach ($defaultCategories as $category) {
        try {
            // Verificar si la categoría ya existe
            $checkStmt = $pdo->prepare("SELECT id FROM art_categories WHERE name = ?");
            $checkStmt->execute([$category[0]]);
            $exists = $checkStmt->fetch();
            
            if (!$exists) {
                $insertStmt->execute($category);
                echo "Categoría '{$category[0]}' creada.\n";
            } else {
                echo "Categoría '{$category[0]}' ya existe.\n";
            }
        } catch (PDOException $e) {
            echo "Error al insertar categoría '{$category[0]}': " . $e->getMessage() . "\n";
        }
    }
    
    // Tabla artworks
    echo "Creando tabla artworks...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS artworks (
            id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
            artist_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            title VARCHAR(200) NOT NULL,
            description TEXT NULL,
            category_id UUID NOT NULL REFERENCES art_categories(id),
            image_path VARCHAR(255) NOT NULL,
            creation_date DATE NULL,
            technique VARCHAR(100) NULL,
            dimensions VARCHAR(50) NULL,
            is_public BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Tabla artwork_likes
    echo "Creando tabla artwork_likes...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS artwork_likes (
            id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
            artwork_id UUID NOT NULL REFERENCES artworks(id) ON DELETE CASCADE,
            user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE (artwork_id, user_id)
        )
    ");
    
    // Tabla comments
    echo "Creando tabla comments...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS comments (
            id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
            artwork_id UUID NOT NULL REFERENCES artworks(id) ON DELETE CASCADE,
            user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            content TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Tabla followers
    echo "Creando tabla followers...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS followers (
            id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
            artist_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            follower_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE (artist_id, follower_id)
        )
    ");
    
    // Tabla notifications
    echo "Creando tabla notifications...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS notifications (
            id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
            user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            type VARCHAR(50) NOT NULL,
            notifiable_type VARCHAR(50) NOT NULL,
            notifiable_id UUID NOT NULL,
            data JSONB NULL,
            read_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Tabla sessions
    echo "Creando tabla sessions...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS sessions (
            id VARCHAR(255) PRIMARY KEY,
            user_id UUID NULL REFERENCES users(id) ON DELETE CASCADE,
            ip_address VARCHAR(45) NULL,
            user_agent TEXT NULL,
            payload TEXT NOT NULL,
            last_activity INTEGER NOT NULL
        )
    ");
    
    // Tabla personal_access_tokens
    echo "Creando tabla personal_access_tokens...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS personal_access_tokens (
            id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
            tokenable_type VARCHAR(255) NOT NULL,
            tokenable_id UUID NOT NULL,
            name VARCHAR(255) NOT NULL,
            token VARCHAR(64) NOT NULL UNIQUE,
            abilities TEXT NULL,
            last_used_at TIMESTAMP NULL,
            expires_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Tabla failed_jobs
    echo "Creando tabla failed_jobs...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS failed_jobs (
            id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
            uuid VARCHAR(255) NOT NULL UNIQUE,
            connection TEXT NOT NULL,
            queue TEXT NOT NULL,
            payload TEXT NOT NULL,
            exception TEXT NOT NULL,
            failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Registrar todas las migraciones en la tabla de migraciones
    $migrations = [
        '2014_10_12_000000_create_users_table',
        '2014_10_12_100000_create_password_reset_tokens_table',
        '2019_08_19_000000_create_failed_jobs_table',
        '2019_12_14_000001_create_personal_access_tokens_table',
        '2023_05_29_120001_create_art_categories_table',
        '2023_05_29_120002_create_artworks_table',
        '2023_05_29_120003_create_artwork_likes_table',
        '2023_05_29_120004_create_comments_table',
        '2023_05_29_120005_create_followers_table',
        '2023_05_29_120006_create_notifications_table',
        '2023_05_29_120007_create_sessions_table'
    ];
    
    // Verificar el último lote de migraciones
    $stmt = $pdo->query("SELECT MAX(batch) as last_batch FROM migrations");
    $result = $stmt->fetch();
    $batch = ($result['last_batch'] ?? 0) + 1;
    
    // Insertar registros de migración
    $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
    
    foreach ($migrations as $migration) {
        $stmt->execute([$migration, $batch]);
    }
    
    echo "Todas las migraciones completadas y registradas correctamente.\n";
    echo "¡La base de datos está lista para usar!\n";
    
    // Ahora puedes usar Laravel normalmente con estas tablas
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
