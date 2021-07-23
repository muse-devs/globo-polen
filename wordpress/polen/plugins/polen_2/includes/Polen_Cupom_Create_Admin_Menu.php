<?php

namespace Polen\Includes;

use \Polen\Admin\Polen_Admin;

class Polen_Cupom_Create_Admin_Menu
{
    public function __construct($static = false)
    {
        if ($static) {
            add_action('admin_menu', [$this, 'create_menu']);
        }
    }

    public function create_menu()
    {
        add_submenu_page('woocommerce-marketing', 'Cupons em Lote', 'Cupons em Lote', 'manage_options', 'batch-coupon', [$this, 'coupon_layout'], 'none', 2);
    }

    public function coupon_layout()
    {
        wp_enqueue_script('batch-coupon', Polen_Admin::get_js_url('coupon.js'), array('jquery', 'vuejs'), DEVELOPER ?  time() : "1.0.0", false);
?>
        <h1>Cupons em Lote</h1>
        <div id="batch-coupon" class="container mt-4">
            <form>
                <div class="row mb-2">
                    <div class="col">
                        <input type="hidden" name="action" value="polen_create_cupom" />
                        <input type="text" v-model="prefix_name" required />
                        <input type="number" v-model="amount" required />
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <select v-model="discount_type" required>
                            <option v-for="type in distount_type_list">{{type}}</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <textarea cols="30" rows="10" v-model="description" required></textarea>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <input type="text" v-model="expiry_date" required />
                        <input type="text" v-model="usage_limit" required />

                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <input type="submit" value="Criar" />
                    </div>
                </div>
            </form>
        </div>
<?php
    }
}
