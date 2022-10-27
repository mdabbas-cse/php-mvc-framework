<?php

if (!function_exists('input')) {
  /**
   * @method for input
   * @param $type
   * @param $name
   * @param $value
   * @param $class
   * @param $id
   * @param $placeholder
   * @param $required
   * @param $error
   */
  function input($attributes)
  {
    $label = $attributes['label'] ?? '';
    $type = $attributes['type'] ?? '';
    $name = $attributes['name'] ?? '';
    $value = $attributes['value'] ?? '';
    $class = $attributes['class'] ?? '';
    $id = $attributes['id'] ?? '';
    $placeholder = $attributes['placeholder'] ?? '';
    $required = $attributes['required'] ? 'required' : false;
    $error = $attributes['error'] ?? false;

    $required = $required ? 'required' : '';
    $errorCls = $error ? 'is-invalid' : '';
    $html = <<<HTML
  <label for="{$id}" class="form-label">{$label}</label>
  <input type="{$type}" name="{$name}" value="{$value}" class="form-control {$class} {$errorCls}" id="{$id}" placeholder="{$placeholder}" $required>
HTML;
    if ($error) {
      $html .= "<div id='{$id}' class='form-text'>{$error}</div>";
    }
    return $html;
  }
}

if (!function_exists('button')) {
  /**
   * @function button
   * @param $attribute array
   * @return HTML String
   */
  function button($attributes)
  {
    $label = $attributes['label'] ?? '';
    $type = $attributes['type'] ?? '';
    $name = isset($attributes['name']) ? "name='{$attributes['name']}'" : '';
    $value = isset($attributes['value']) ? "value='{$attributes['value']}'" : '';
    $class = isset($attributes['class']) ?? '';
    $id = isset($attributes['id']) ? "id='{$attributes['id']}'" : '';
    $html = <<<HTML
  <button type="{$type}" {$name} {$value} class="btn btn-primary {$class}" {$id}>{$label}</button>
HTML;
    return $html;
  }
}
