<?php
class Material_Inputs
{
  const TYPE_TEXT = "text";
  const TYPE_EMAIL = "email";
  const TYPE_PHONE = "phone";

  public function hidden_input($name, $value)
  {
  ?>
    <input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
  <?php
  }

  public function material_input($type, $id, $name, $label, $required = true, $placeholder = "", $helper = "", $params = array())
  {
    ?>
    <label id="<?php echo $id; ?>" class="mdc-text-field mdc-text-field--outlined">
      <span class="mdc-notched-outline">
        <span class="mdc-notched-outline__leading"></span>
        <span class="mdc-notched-outline__notch">
          <span class="mdc-floating-label" id="label-<?php echo $id; ?>"><?php echo $label; ?></span>
        </span>
        <span class="mdc-notched-outline__trailing"></span>
      </span>
      <input
        type="<?php echo $type; ?>"
        name="<?php echo $name; ?>"
        class="mdc-text-field__input"
        aria-labelledby="label-<?php echo $id; ?>"
        <?php if($helper) : ?>
          aria-controls="helper<?php echo $id; ?>"
          aria-describedby="helper<?php echo $id; ?>"
        <?php endif; ?>
        placeholder="<?php echo $placeholder; ?>"
        autocomplete="on"
        <?php echo $required ? " required" : ""; ?>
        <?php
          foreach ($params as $key => $value) {
            echo " {$key}='{$value}'";
          }
        ?>
      />
    </label>
    <?php if($helper) : ?>
      <div class="mdc-text-field-helper-line">
        <div class="mdc-text-field-helper-text" id="helper<?php echo $id; ?>" aria-hidden="true"><?php echo $helper; ?></div>
      </div>
    <?php endif; ?>
    <script>
      mdc.textField.MDCTextField.attachTo(document.querySelector("#" + "<?php echo $id; ?>"));
    </script>
  <?php
  }
}
