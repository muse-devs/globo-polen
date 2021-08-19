<?php

class Coupons{
    /**
     * Inserir Cupons na tabela
     *
     * @throws Exception
     */
    public function insert_coupons($qty)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'promotional_event';

        for ($i = 0; $i < $qty; $i++) {
            $wpdb->insert(
                $table_name,
                array(
                    'code' => Promotional_Event_Generate_Coupon::generate(8),
                )
            );
        }
    }

    /**
     * Retornar todos os códigos
     *
     * @since    1.0.0
     */
    public function get_codes()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'promotional_event';

        return $wpdb->get_results( "SELECT * FROM {$table_name}");
    }

    /**
     * Retornar todos os códigos
     *
     * @since    1.0.0
     */
    public function count_rows()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'promotional_event';

        return $wpdb->get_var( "SELECT COUNT(*) FROM {$table_name}");
    }

    /**
     * Verificar se cupon existe
     *
     * @since    1.0.0
     */
    public function check_coupoun_exist($coupon)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'promotional_event';

        return $wpdb->get_results("
            SELECT id, code
            FROM {$table_name}
            WHERE code='{$coupon}'
        ");
    }

    /**
     * Atualizar status do coupoun
     *
     * @param $coupon
     * @return bool|int
     */
    public function update_coupoun($coupon, $order_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'promotional_event';

        return $wpdb->update($table_name, array('is_used' => 1, 'order_id' => $order_id), array('code' => $coupon));
    }

    /**
     * Verificar se o coupoun já foi utilizado
     *
     * @since    1.0.0
     */
    public function check_coupoun_is_used($coupon)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'promotional_event';

        return $wpdb->get_var( "
                SELECT COUNT(*) FROM {$table_name} 
                WHERE code='{$coupon}' 
                  AND 
                      is_used=1
            ");

    }
}


