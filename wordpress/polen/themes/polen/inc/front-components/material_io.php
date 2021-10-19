<?php
class Material_Inputs
{
  const TYPE_TEXT = "text";
  const TYPE_EMAIL = "email";
  const TYPE_PHONE = "phone";
  const TYPE_SUBMIT = "submit";
  const TYPE_BUTTON = "button";

  function __construct()
  {
    wp_enqueue_script('material-js');

    ob_start();
    include $this->get_full_path_file('init');
    return ob_end_flush();
  }

  public function get_full_path_file($file_name)
  {
    return dirname(__FILE__) . "/material/{$file_name}.php";
  }

  public function input_hidden(string $name, string $value)
  {
    ob_start();
    include $this->get_full_path_file('input_hidden');
    return ob_end_flush();
  }

  public function material_input(
    string $type,
    string $id,
    string $name,
    string $label,
    bool $required = true,
    string $classes = "",
    string $helper = "",
    array $params = array()
  ) {
    ob_start();
    include $this->get_full_path_file('material_input');
    return ob_end_flush();
  }

  public function material_textarea(
    string $id,
    string $name,
    string $label,
    bool $required = true,
    string $helper = "",
    array $params = array()
  ) {
    ob_start();
    include $this->get_full_path_file('material_textarea');
    return ob_end_flush();
  }

  public function material_button(string $type = self::TYPE_BUTTON, string $id, string $title, string $classes = "")
  {
    ob_start();
    include $this->get_full_path_file('material_button');
    return ob_end_flush();
  }

  public function material_button_link(string $id, string $title, string $link, bool $blank = false, string $classes = "")
  {
    ob_start();
    include $this->get_full_path_file('material_button_link');
    return ob_end_flush();
  }

  public function material_select(
    string $id,
    string $name,
    string $label,
    array $items,
    bool $required,
    string $classes = "",
    array $params = array()
  ) {
    ob_start();
    include $this->get_full_path_file('material_select');
    return ob_end_flush();
  }
}
