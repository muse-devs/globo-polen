<?php

namespace Polen\Includes;

class Polen_SignInUser_Strong_Password
{
    public function __construct( bool $static = false )
    {
        if( $static ) {
            $this->init();
        }
    }

    
    public function init()
    {
        add_action( 'polen_register_form', array( $this, 'enqueue_scripts' ) );
        add_filter( 'woocommerce_registration_errors', array( $this, 'validate_strong_password' ), 11, 3 );
    }


    public function enqueue_scripts()
    {
        $min = get_assets_folder();
        wp_register_script( 'user-register-js', TEMPLATE_URI . '/assets/js/' . $min . 'user-register.js', array("global-js"), _S_VERSION, true );
        wp_enqueue_script( 'user-register-js' );
    }


    /**
     * Valida se a senha é forte
     * @param WP_Error
     * @param string
     * @param string
     */
    public function validate_strong_password( $errors, $username, $email )
    {
        // Given password
        $password = filter_input( INPUT_POST, 'password' );

        $password_lenght = 6;
        // Validate password strength
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < $password_lenght) {
            $errors->add( 'registration-error', "A senha deveria ter ao menos {$password_lenght} caracteres, 1 letra maiuscula, 1 numero e 1 caracter especial", 'woocommerce' );
        }

        return $errors;
    }
}