<?php

/**
 * Plugin main file.
 * @wordpress-plugin
 * Plugin Name:       Kntnt Login Form Shortcode
 * Plugin URI:        https://www.kntnt.com/
 * Description:       Provides shortcode for login form
 * Version:           1.0.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Kntnt\Login_Shortcode;

defined( 'WPINC' ) && new Plugin;

class Plugin {

    private $defaults;

    public function __construct() {

        $this->defaults = [
            'label_username' => __( 'Username or Email Address' ),
            'label_password' => __( 'Password' ),
            'label_remember' => __( 'Remember Me' ),
            'label_log_in' => __( 'Log In' ),
            'form_id' => 'loginform',
            'id_username' => 'user_login',
            'id_password' => 'user_pass',
            'id_remember' => 'rememberme',
            'id_submit' => 'wp-submit',
            'remember' => true,
            'value_username' => '',
            'value_remember' => false,
            'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        ];

        add_shortcode( 'login', [ $this, 'login_shortcode' ] );

    }

    public function login_shortcode( $atts ) {
        $atts = $this->shortcode_atts( $this->$defaults, $atts );
        $atts['echo'] = false;
        return wp_login_form( $atts );
    }

    // A more forgiving version of WP's shortcode_atts().
    private function shortcode_atts( $pairs, $atts, $shortcode = '' ) {

        $atts = (array) $atts;
        $out = [];
        $pos = 0;
        while ( $name = key( $pairs ) ) {
            $default = array_shift( $pairs );
            if ( array_key_exists( $name, $atts ) ) {
                $out[ $name ] = $atts[ $name ];
            }
            else if ( array_key_exists( $pos, $atts ) && $atts[ $pos ] ) {
                $out[ $name ] = $atts[ $pos ];
                ++ $pos;
            }
            else {
                $out[ $name ] = $default;
            }
        }

        if ( $shortcode ) {
            $out = apply_filters( "shortcode_atts_{$shortcode}", $out, $pairs, $atts, $shortcode );
        }

        return $out;

    }

}
