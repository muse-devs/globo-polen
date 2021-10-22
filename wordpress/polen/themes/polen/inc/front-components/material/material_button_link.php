<a id="<?php echo $id; ?>" href="<?php echo $link; ?>" target="<?php echo $blank ? "_blank" : "_self"; ?>" class="mdc-button mdc-button--raised<?php echo $classes ? " " . $classes : ""; ?>"<?php foreach ($params as $key => $value) {
                                                                                                                                                  echo " {$key}='{$value}'";
                                                                                                                                                } ?>>
  <span class="mdc-button__ripple"></span>
  <span class="mdc-button__touch"></span>
  <span class="mdc-button__label"><?php echo $title; ?></span>
</a>
