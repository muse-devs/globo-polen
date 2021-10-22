<button type="<?php echo $type; ?>" id="<?php echo $id; ?>" class="mdc-button mdc-button--outlined<?php echo $classes ? " " . $classes : ""; ?>"<?php foreach ($params as $key => $value) {
                                                                                                                                                  echo " {$key}='{$value}'";
                                                                                                                                                } ?>>
  <span class="mdc-button__ripple"></span>
  <span class="mdc-button__label"><?php echo $title; ?></span>
</button>
