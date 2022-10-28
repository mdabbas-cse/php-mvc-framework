<?php

namespace MVC\Framework\Components;


class InputField
{
  public $attributes;
  public $type;

  public function __construct($type, $attribute, $label, $wrp_cls = null)
  {
    $this->attribute = $attribute;
    $this->type = $type;
    $this->label = $label;
    $this->wrp_cls = $wrp_cls ? $wrp_cls : 'form-outline';
  }

  public function __toString()
  {
    $fld =  sprintf(
      '
      <div class="%s">
        <label class="form-label" for="%s">%s</label>
        <input type="%s" id="%s" name="%s" class="form-control %s" />
      </div>
      
    ',
      $this->wrp_cls,
      $this->attribute,
      $this->label,
      $this->type,
      $this->attribute,
      $this->attribute,
      errors($this->attribute) ? 'is-invalid' : ''
    );

    if (errors($this->attribute)) {
      $error = errors($this->attribute);
      $fld .= "<div id='{$this->attribute}' class='form-text'>{$error}</div>";
    }
    return $fld;
  }
}
