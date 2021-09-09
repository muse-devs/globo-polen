<?php

class Icon_Class
{
	public static function polen_icon_criesp()
	{
		echo '<span class="icon icon-criesp"></span>';
	}

	public static function polen_icon_clipboard()
	{
		echo '<i class="bi bi-clipboard"></i>';
	}

	public static function polen_icon_play()
	{
		echo '<i class="bi bi-play"></i>';
	}

	public static function polen_icon_card($name)
	{
		echo '<span class="svg-icon svg-' . $name . ' svg-' . $name . '-dims"></span>';
	}

	public static function polen_icon_company()
	{
		echo '<span class="icon icon-company"></span>';
	}

	public static function polen_icon_phone()
	{
		echo '<i class="icon icon-phone"></i>';
	}

	public static function polen_icon_camera_video()
	{
		echo '<i class="icon icon-camera-video"></i>';
	}

	public static function polen_icon_hand_thumbs_up()
	{
		echo '<i class="icon icon-hand-thumbs-up"></i>';
	}

	public static function polen_icon_check_o()
	{
		echo '<i class="bi bi-check-circle"></i>';
	}

	public static function polen_icon_check_o_alt()
	{
		echo '<i class="icon icon-check-o" style="color: var(--success)"></i>';
	}

	public static function polen_icon_exclamation_o()
	{
		echo '<i class="icon icon-error-o"></i>';
	}

	public static function polen_icon_checkmark()
	{
		echo '<i class="bi bi-check"></i>';
	}

	public static function polen_icon_reload($id)
	{
		echo '<i id="' . $id . '" class="bi bi-arrow-clockwise"></i>';
	}

	public static function polen_icon_share()
	{
		echo '<i class="bi bi-share-fill"></i>';
	}

	public static function polen_icon_clock()
	{
		echo '<i class="bi bi-clock"></i>';
	}

	public static function polen_icon_star($active = false)
	{
		if ($active) {
			echo '<i class="icon icon-star-fill" style="color: #FFCF34;"></i>';
		} else {
			echo '<i class="icon icon-star"></i>';
		}
	}

	public static function polen_icon_arrows()
	{
		echo '<img src="' . TEMPLATE_URI . '/assets/img/arrows.png" />';
	}

	public static function polen_icon_accept_reject($type = 'accept')
	{
		if ($type === 'reject') {
			echo '<i class="bi bi-x"></i>';
		} else {
			echo '<i class="bi bi-check"></i>';
		}
	}

	public static function polen_icon_upload()
	{
		echo '<i class="bi bi-cloud-arrow-up"></i>';
	}

	public static function polen_icon_calendar()
	{
		echo '<i class="bi bi-calendar"></i>';
	}

	public static function polen_icon_download()
	{
		echo '<i class="bi bi-download"></i>';
	}

	public static function polen_icon_copy()
	{
		echo '<i class="bi bi-clipboard"></i>';
	}

	public static function polen_icon_chevron()
	{
		echo '<i class="icon icon-down-arrow"></i>';
	}

	public static function polen_icon_chevron_down()
	{
		echo '<i class="icon icon-down-arrow"></i>';
	}

	public static function polen_icon_chevron_up()
	{
		echo '<i class="icon icon-up-arrow"></i>';
	}

	public static function polen_icon_chevron_right()
	{
		echo '<i class="icon icon-right-arrow"></i>';
	}

	public static function polen_icon_chevron_left()
	{
		echo '<i class="icon icon-left-arrow"></i>';
	}

	public static function polen_icon_close()
	{
		echo '<i class="icon icon-close"></i>';
	}

	public static function polen_icon_trash()
	{
		echo '<i class="icon icon-trash"></i>';
	}

	public static function polen_icon_donate()
	{
		echo '<i class="icon icon-donate"></i>';
	}

	public static function va_icons($ico)
	{
		echo "<i class='icon icon-{$ico}'></i>";
	}

	public static function polen_icon_social($ico)
	{
		$ret = '';
		switch ($ico) {
			case 'facebook':
				$ret = '<i class="icon icon-facebook"></i>';
				break;

			case 'instagram':
				$ret = '<i class="icon icon-instagram"></i>';
				break;

			case 'linkedin':
				$ret = '<i class="icon icon-linkedin"></i>';
				break;

			case 'twitter':
				$ret = '<i class="icon icon-twitter"></i>';
				break;

			case 'whatsapp':
				$ret = '<i class="bi bi-whatsapp"></i>';
				break;

			case 'tiktok':
				$ret = '<i class="icon icon-tiktok"></i>';
				break;

			default:
				$ret = '';
				break;
		}

		echo $ret;
	}
}
