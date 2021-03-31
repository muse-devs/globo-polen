<?php

class Icon_Class {

    public static function polen_icon_star( $active = false )
    {
        if ($active) {
            echo '<i class="bi bi-star-fill" style="color: #FFF963;"></i>';
        } else {
            echo '<i class="bi bi-star"></i>';
        }
    }
    
    public static function polen_icon_clock()
    {
            echo '<i class="bi bi-clock"></i>';
    }

}
