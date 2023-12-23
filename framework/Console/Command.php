<?php

namespace LaraCore\Framework\Console;

use LaraCore\Framework\Console\MigrationCommand;
use LaraCore\Framework\Console\ControllerCommand;
use LaraCore\Framework\Console\ModelCommand;
use LaraCore\Framework\Console\ApiKey;
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
            case 'serve':
                Server::run($argv);
                break;

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
                ControllerCommand::make($argv);
                break;

            case 'make:model':
                ModelCommand::make($argv);
                break;

            case 'db:seed':
                SeederCommand::runDatabaseSeeder();
                break;

            case 'db:seeder': // for specific seeder
                SeederCommand::run($argv);
                break;

            case 'generate:api-key':
                ApiKey::generate();
                break;

            default:
                Log::warning("Unknown command: $command");
                exit(1);
        }
    }


}