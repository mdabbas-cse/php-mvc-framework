<?php

/**
 * @author:  Md. Abbas uddin <gmabbas44@gmail.com>
 * @package: MVC\Framework\Components\Form
 */

namespace MVC\Framework\Components;

use MVC\Framework\Components\InputField;

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
