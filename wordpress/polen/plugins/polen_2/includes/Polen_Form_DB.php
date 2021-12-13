<?php

namespace Polen\Includes;

class Polen_Form_DB{

    private $wpdb;
    private $table_name;

    public function __construct()
    {
        global $wpdb;

        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->base_prefix . 'polen_forms';
    }

    public function insert($args)
    {
        if (isset($args['action'])) {
            unset($args['action']);
        }

        $this->wpdb->insert($this->table_name, $args);
    }

    public function getLeads($form_id = 1)
    {
        return $this->wpdb->get_results("
            SELECT * FROM {$this->table_name} 
            WHERE `form_id` = {$form_id}
            ",
        );
    }
}


