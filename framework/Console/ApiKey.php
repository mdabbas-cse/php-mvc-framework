<?php

namespace LaraCore\Framework\Console;

class ApiKey
{
  private static function generateKey()
  {
    $key = base64_encode(random_bytes(32));
    $key = substr($key, 0, 32);
    $key = str_replace('/', '', $key);
    $key = str_replace('+', '', $key);
    $key = str_replace('=', '', $key);
    return $key;
  }

  /**
   * run command method
   */
  public static function generate()
  {
    $key = self::generateKey();

    // get config file
    $configFile = ROOT . DS . 'config' . DS . 'Config.php';
    $config = include $configFile;
    file_get_contents($configFile);

    $config['api-token']['key'] = $key;

    $updatedFileContents = '<?php' . PHP_EOL . PHP_EOL . 'return [' . PHP_EOL;
    $updatedFileContents .= self::formatArray($config, 1);
    $updatedFileContents .= '];';

    $result = file_put_contents($configFile, $updatedFileContents);
    if ($result) {
      Log::success("Api key generated successfully");
    } else {
      Log::error("Api key generation failed");
    }
  }

  private static function formatArray($array, $indentLevel)
  {
    $indent = str_repeat('  ', $indentLevel);
    $formatted = '';

    foreach ($array as $key => $value) {
      if (is_array($value)) {
        $formatted .= "{$indent}'{$key}' => [\n";
        $formatted .= self::formatArray($value, $indentLevel + 1);
        $formatted .= "{$indent}],\n";
      } else {
        $formatted .= "{$indent}'{$key}' => '{$value}',\n";
      }
    }

    return $formatted;
  }

}