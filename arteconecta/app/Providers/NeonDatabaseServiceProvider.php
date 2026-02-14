<?php

namespace App\Providers;

use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Connectors\PostgresConnector;
use PDO;

class NeonDatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Extender el conector PostgreSQL para agregar el endpoint ID
        $this->app->bind('db.connector.pgsql', function ($app) {
            return new class extends PostgresConnector {
                public function connect(array $config)
                {
                    // Extraer endpoint ID de la URL del host
                    $host = $config['host'] ?? '';
                    $parts = explode('.', $host);
                    $endpointId = $parts[0] ?? '';

                    // Solo agregar el parámetro de endpoint si el host parece ser de Neon Tech
                    if (strpos($host, 'neon.tech') !== false && !empty($endpointId)) {
                        // Construir DSN con endpoint
                        $dsn = "pgsql:host={$host};dbname={$config['database']};port={$config['port']};";
                        $dsn .= "options=endpoint={$endpointId}";
                        
                        $options = $this->getOptions($config);

                        // El pooler (PgBouncer) en modo transaction pooling suele romper
                        // los prepared statements del servidor. Emulamos prepares para
                        // evitar errores durante migraciones/DDL y en general.
                        if (str_contains($host, '-pooler.') || str_contains($host, 'pooler')) {
                            $options[PDO::ATTR_EMULATE_PREPARES] = true;
                        }

                        // Conectarse usando DSN personalizado
                        return $this->createPdoConnection(
                            $dsn, 
                            $config['username'], 
                            $config['password'], 
                            $options
                        );
                    }
                    
                    // Usar método normal para otras conexiones
                    return parent::connect($config);
                }
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
