<?php

namespace LaraCore\Framework\Console;

use LaraCore\Framework\Console\MigrationCommand;
use LaraCore\Framework\Console\Log;


class Command
{
    public static function run($argv)
    {
        if (!isset($argv[1])) {
            echo "Usage: php laracore <command>\n";
            exit(1);
        }

        $command = $argv[1];

        switch ($command) {
            case 'make:migration':
                MigrationCommand::makeMigration($argv);
                break;

            case 'migrate':
                MigrationCommand::migrate($argv);
                break;

            case 'migration:rollback':
                MigrationCommand::rollback();
                break;

            case 'make:controller':
                // self::makeController($argv);
                break;

            case 'make:model':
                // self::makeModel($argv);
                break;

            default:
                Log::warning("Unknown command: $command");
                exit(1);
        }
    }


}