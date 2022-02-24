<?php
namespace Polen\Includes\Module;

use PHPMailer\PHPMailer\Exception;
use Polen\Includes\Polen_SignInUser_Strong_Password;

class Polen_User_Module
{
    /**
     * Obj
     * Informações básicas do usuario
     */
    public $user;

    public function __construct(int $user_id)
    {
        $this->user = get_user_by('ID', $user_id);
    }

    public static function create_from_product_id($product_id)
    {
        $user = self::get_talent_from_product($product_id);

        return new self($user->ID);
    }

    /**
     * Retornar talento pelo ID do Produto
     *
     * @param int $product_id
     * @return object
     */
    private static function get_talent_from_product(int $product_id): object
    {
        global $wpdb;
        $sql = "SELECT `post_author` AS `ID`
            FROM `" . $wpdb->users . "` U
            LEFT JOIN `" . $wpdb->posts . "` P ON P.`post_author` = U.`ID`
            WHERE P.`ID`=" . $product_id;

        return $wpdb->get_results($sql)[0];
    }

    /**
     * Retornar informações básicas do talento
     *
     * @return array
     */
    public function get_info_talent(): array
    {
        global $wpdb;
        $sql = "
            SELECT `user_id`, `celular`, `telefone`, `whatsapp`, `email`, `nome_fantasia`
            FROM `" . $wpdb->base_prefix . "polen_talents`
            WHERE `user_id`=" . $this->user->ID;

        return $wpdb->get_results($sql);
    }

    /**
     * Retornar nome customizado do usuario
     *
     * @return string
     */
    public function get_display_name(): string
    {
        return get_the_author_meta('display_name', $this->user->ID);
    }

    /**
     * Atualizar senha do usuario
     */
    public function update_pass(string $current_pass, string $new_password)
    {
        $check = wp_authenticate($this->user->email, $current_pass);
        if (is_wp_error($check)) {
            throw new Exception('Senha atual incorreta', 403);
        }

        if (!$this->check_security_password(sanitize_text_field($new_password))) {
            $strong_password = new Polen_SignInUser_Strong_Password();
            throw new Exception($strong_password->get_default_message_error(), 403);
        }

        wp_set_password($new_password, $this->user->ID);
    }

    /**
     * fazer veririfação de senha
     *
     * @param $password
     * @return bool
     */
    private function check_security_password($password): bool
    {
        $strong_password = new Polen_SignInUser_Strong_Password();
        return $strong_password->verify_strong_password($password);
    }

}
