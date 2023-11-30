<?php
namespace LaraCore\Framework\Console;

class Server
{
  /**
   * @method  for run server with php -S localhost:8000
   * @param   array $argv
   * @return  void
   */
  public static function run($argv)
  {
    $host = 'localhost';
    $port = $argv[2] ?? 8000;

    $serverCommand = sprintf('php -S %s:%d -t %s', $host, $port, ROOT);

    Log::success("LaraCore development server started at http://$host:$port\n");
    Log::warning("Press Ctrl+C to stop the server.\n");

    // Execute the PHP built-in server command
    passthru($serverCommand);
  }
}