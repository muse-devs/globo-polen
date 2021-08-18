<?php

/**
 * Fired during plugin activation
 *
 * @link       https://polen.me
 * @since      1.0.0
 *
 * @package    Promotional_Event
 * @subpackage Promotional_Event/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Promotional_Event
 * @subpackage Promotional_Event/includes
 * @author     Polen.me <glaydson.queiroz@polen.me>
 */
class Promotional_Event_Activator {

    /**
     * Estartar todas as funções
     *
     * @throws Exception
     * @since    1.0.0
     */

    public static function activate()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'promotional_event';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
			  `id` INT(11) NOT NULL AUTO_INCREMENT,
			  `code` VARCHAR(255) NOT NULL,
			  `is_used` bit NOT NULL DEFAULT (0),
			  `order_id` INT(11),
			  PRIMARY KEY (`id`),
			  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
			  $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta( $sql );
	}

}
