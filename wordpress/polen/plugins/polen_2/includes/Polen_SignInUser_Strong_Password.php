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
        $http_referer = filter_input( INPUT_POST, '_wp_http_referer' );

        $password_lenght = 6;
        // Validate password strength
        $uppercase = true;//preg_match('@[A-Z]@', $password);
        $lowercase = true;//preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = true;//preg_match('@[^\w]@', $password);
        if( '/register/' == $http_referer ) {
            if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < $password_lenght) {
                // $errors->add( 'registration-error', "A senha deveria ter ao menos {$password_lenght} caracteres, 1 letra maiuscula, 1 numero e 1 caracter especial", 'woocommerce' );//Antiga mensagem quando ainda precisava de uppercase, lowercase e specialChars
                $errors->add( 'registration-error', "A senha deveria ter ao menos {$password_lenght} caracteres e 1 numero", 'woocommerce' );
            }
        }

        return $errors;
    }
}