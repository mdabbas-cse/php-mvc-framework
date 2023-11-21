<?php

namespace LaraCore\Framework\Helpers;

class FlashMessages
{
  protected const FLASH_KEY = 'flash_messages';

  public function __construct()
  {
    $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
    foreach ($flashMessages as $key => &$flashMessage) {
      $flashMessage['remove'] = true;
    }
    $_SESSION[self::FLASH_KEY] = $flashMessages;
  }

  public function setFlash($key, $message)
  {
    $_SESSION[self::FLASH_KEY][$key] = [
      'remove' => false,
      'value' => $message
    ];
  }

  public function getFlash($key)
  {
    $message = $_SESSION[self::FLASH_KEY][$key]['value'] ?? null;
    if ($message) {
      return "<div class='alert alert-$key' role='alert'>{$message}</div>";
    }
    return $message;
  }

  public function __destruct()
  {
    $this->removeFlashMessages();
  }

  private function removeFlashMessages()
  {
    $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
    foreach ($flashMessages as $key => $flashMessage) {
      if ($flashMessage['remove']) {
        unset($flashMessages[$key]);
      }
    }
    $_SESSION[self::FLASH_KEY] = $flashMessages;
  }
}
