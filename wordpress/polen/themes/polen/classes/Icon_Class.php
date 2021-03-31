<?php

class Icon_Class
{

	public static function polen_icon_star($active = false)
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

	public static function polen_icon_reload()
	{
		echo '<i class="bi bi-arrow-clockwise"></i>';
	}

	public static function polen_icon_arrows()
	{
		echo '<img src="' . TEMPLATE_URI . '/assets/img/arrows.png" />';
	}

	public static function polen_icon_chevron($direction)
	{
		echo '<i class="bi bi-chevron-' . $direction . '"></i>';
	}
}
