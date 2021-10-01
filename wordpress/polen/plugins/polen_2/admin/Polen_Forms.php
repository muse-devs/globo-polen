<?php

namespace Polen\Admin;

use Exception;
use Polen\Includes\Polen_Form_DB;

class Polen_Forms {

    public function __construct()
    {
        add_action('admin_menu', array($this, 'addMenu'));
        add_action('wp_ajax_submit_form', array($this, 'submitForm'));
        add_action('wp_ajax_nopriv_submit_form', array($this, 'submitForm'));
    }

    /**
     * Adiciona os menus no dashboard do wordpress
     *
     * @since    1.0.0
     */
    public function addMenu()
    {
        add_menu_page('Formulários',
            'Formulários',
            'manage_options',
            'forms',
            array($this, 'showForms'),
            'dashicons-email-alt'
        );
    }

    /**
     * View página principal
     *
     * @since    1.0.0
     */
    public function showForms()
    {
        $form_db = new Polen_Form_DB();
        $leads = $form_db->getLeads();
        require 'partials/forms-enterprise.php';
    }

    /**
     * Salvar formulários no banco
     */
    public function submitForm()
    {
        try{
            $fields = $_POST;
            $requiredFields = $this->requiredFields();
            $data = array();

            foreach ($fields as $key => $field) {
                if (key_exists($key, $requiredFields)) {
                    unset($requiredFields[$key]);
                }

                $data[$key] = sanitize_text_field($field);
            }

            if (!empty($requiredFields)) {
                foreach ($requiredFields as $key => $requiredField) {
                    throw new Exception("O campo {$requiredField} é obrigatório", 422);
                }
            }

            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email Inválido', 422);
            }

//            if(!wp_verify_nonce($data['nonce'], self::NONCE_ACTION )) {
//                throw new Exception('Erro na verificação de segurança', 422);
//            }

            $form_db = new Polen_Form_DB();
            $form_db->insert($data);

            wp_send_json_success('ok', 200);
            wp_die();

        } catch (\Exception $e) {
            wp_send_json_error(array('Error' => $e->getMessage()), 422);
            wp_die();
        }
    }

    /**
     * Retorna todos os campos do formulário que são obrigatórios
     */
    private function requiredFields(): array
    {
        return [
            'name' => 'Nome',
            'email' => 'E-mail',
            'terms' => 'Termos',
        ];
    }
}