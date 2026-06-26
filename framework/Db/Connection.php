<?php

namespace LaraCore\Framework\Db;

use LaraCore\Framework\Configuration;
use LaraCore\Framework\Db\Exceptions\DatabaseConnectionException;
use PDO;
use PDOException;

class Connection
{
    private static ?PDO $instance = null;

    /**
     * Singleton PDO instance — reused across all models.
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::make(Configuration::get('database'));
        }
        return self::$instance;
    }

    /**
     * Override the singleton — used exclusively in tests.
     * Pass null to reset.
     */
    public static function setInstance(PDO $pdo = null): void
    {
        self::$instance = $pdo;
    }

    /**
     * Create a fresh PDO connection from config.
     */
    public static function make(array $config): PDO
    {
        try {
            $dsn = sprintf(
                'mysql:host=%s:%s;dbname=%s;charset=utf8mb4',
                $config['connection'],
                $config['port'],
                $config['dbname']
            );
            $options = array_merge([
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ], $config['options'] ?? []);
            return new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (PDOException $e) {
            throw new DatabaseConnectionException($e->getMessage(), (int) $e->getCode());
        }
    }
}
