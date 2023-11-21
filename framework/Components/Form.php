<?php

/**
 * @author:  Md. Abbas uddin <gmabbas44@gmail.com>
 * @package: LaraCore\Framework\Components\Form
 */

namespace LaraCore\Framework\Components;

use LaraCore\Framework\Components\InputField;

class Form
{
  public static function begin($action, $method)
  {
    echo sprintf('<form action="%s" method="%s">', $action, $method);
    return new Form();
  }

  public static function end()
  {
    echo '</form>';
  }

  public function field($type, $attribute, $label, $wrp_cls = null)
  {
    return new InputField($type, $attribute, $label, $wrp_cls);
  }
}
