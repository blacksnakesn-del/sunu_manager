<?php

namespace Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::connect();
        }

        return self::$instance;
    }

    private static function connect(): PDO
    {
        // 1. Configuration PostgreSQL
        $pgHost = 'localhost';
        $pgPort = '5432';
        $pgDb   = 'boutique_db';
        $pgUser = 'postgres';
        $pgPass = 'postgres';

        $pgDsn = "pgsql:host={$pgHost};port={$pgPort};dbname={$pgDb}";

        try {
            $pdo = new PDO($pgDsn, $pgUser, $pgPass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);

            return $pdo;

        } catch (PDOException $e) {
            $sqlitePath = __DIR__ . '/../../database/erp.db';
            $sqliteDsn  = "sqlite:" . $sqlitePath;

            try {
                $pdo = new PDO($sqliteDsn, null, null, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);

                $pdo->exec('PRAGMA foreign_keys = ON;');

                return $pdo;

            } catch (PDOException $sqliteError) {
                die("Échec de connexion aux bases de données : " . $sqliteError->getMessage());
            }
        }
    }

    private function __construct() {}

    private function __clone() {}
}