<?php
/*
Plugin Name: Products Variation Limit
Plugin URI:  http://thenextsupportal.com/ to your plugin homepage
Description: This plugin replaces words with your own choice of words.
Version:     1.0
Author:      Sourav Sobti
Author URI:  http://thenextsupportal.com/ to your website
License:     GPL2 etc

Copyright 2020 Sourav Sobti (email : souravsobti@gmail.com)
(Products Variation Limit) is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
(Products Variation Limit) is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with (Plugin Name). If not, see (http://link to your plugin license).
*/

class WVL_Plugin {

    public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_wvl_page' ),99 );
		add_action( 'admin_init', array( $this, 'setup_sections' ) );
    	add_action( 'admin_init', array( $this, 'setup_fields' ) );
		add_action( 'admin_init', array( $this, 'set_variation_limit' ) );
		
    }
	
	public function register_wvl_page() {
		add_submenu_page( 'woocommerce', 'Variations Limit', 'Variations Limit', 'manage_options', 'wvl-page', array( $this, 'wvl_content' ) ); 
	}
	
	public function set_variation_limit(){
		$limit = get_option( 'wvl_field' );
		if( ! $limit ) {
            $limit = 100;
        }		
		define( 'WC_MAX_LINKED_VARIATIONS', $limit );
	}

    public function wvl_content() {?>
    	<div class="wrap">
    		<h2>Products Variation Limit</h2><?php
            if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ){
                  $this->admin_notice();
            } ?>
    		<form method="POST" action="options.php">
                <?php
                    settings_fields( 'smashing_fields' );
                    do_settings_sections( 'smashing_fields' );
                    submit_button();
                ?>
    		</form>
    	</div> <?php
    }
    
    public function admin_notice() { ?>
        <div class="notice notice-success is-dismissible">
            <p>Your settings have been updated!</p>
        </div><?php
    }

    public function setup_sections() {
        add_settings_section( 'wvl_section', 'Variation Limit Setting', '', 'smashing_fields' );
    }
    

    public function setup_fields() {
        $fields = array(
        	
        	array(
        		'uid' => 'wvl_field',
        		'label' => 'Limit',
        		'section' => 'wvl_section',
        		'type' => 'number',
				'placeholder' => '100',
        		'helper' => 'Default limit is 100',
        		'supplimental' => '',
        	)
        );
    	foreach( $fields as $field ){

        	add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), 'smashing_fields', $field['section'], $field );
            register_setting( 'smashing_fields', $field['uid'] );
    	}
    }

    public function field_callback( $arguments ) {

        $value = get_option( $arguments['uid'] );

        if( ! $value ) {
            $value = $arguments['default'];
        }

        switch( $arguments['type'] ){
            case 'text':
            case 'password':
            case 'number':
                printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
                break;            
        }

        if( $helper = $arguments['helper'] ){
            printf( '<span class="helper"> %s</span>', $helper );
        }

        if( $supplimental = $arguments['supplimental'] ){
            printf( '<p class="description">%s</p>', $supplimental );
        }

    }

}
new WVL_Plugin();
?>