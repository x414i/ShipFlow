<?php
/**
 * @Packge     : Tourm
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
 */

// Block direct access
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

/**
 *
 * Define constant 
 *
 */

// Base URI
if ( ! defined( 'TOURM_DIR_URI' ) ) {
    define('TOURM_DIR_URI', get_parent_theme_file_uri().'/' );
}

// Assist URI
if ( ! defined( 'TOURM_DIR_ASSIST_URI' ) ) {
    define( 'TOURM_DIR_ASSIST_URI', get_theme_file_uri('/assets/') );
}


// Css File URI
if ( ! defined( 'TOURM_DIR_CSS_URI' ) ) {
    define( 'TOURM_DIR_CSS_URI', get_theme_file_uri('/assets/css/') );
}

// Js File URI
if (!defined('TOURM_DIR_JS_URI')) {
    define('TOURM_DIR_JS_URI', get_theme_file_uri('/assets/js/'));
}


// Base Directory
if (!defined('TOURM_DIR_PATH')) {
    define('TOURM_DIR_PATH', get_parent_theme_file_path() . '/');
}

//Inc Folder Directory
if (!defined('TOURM_DIR_PATH_INC')) {
    define('TOURM_DIR_PATH_INC', TOURM_DIR_PATH . 'inc/');
}

//TOURM framework Folder Directory
if (!defined('TOURM_DIR_PATH_FRAM')) {
    define('TOURM_DIR_PATH_FRAM', TOURM_DIR_PATH_INC . 'tourm-framework/');
}

//Hooks Folder Directory
if (!defined('TOURM_DIR_PATH_HOOKS')) {
    define('TOURM_DIR_PATH_HOOKS', TOURM_DIR_PATH_INC . 'hooks/');
}

//Demo Data Folder Directory Path
if( !defined( 'TOURM_DEMO_DIR_PATH' ) ){
    define( 'TOURM_DEMO_DIR_PATH', TOURM_DIR_PATH_INC.'demo-data/' );
}
    
//Demo Data Folder Directory URI
if( !defined( 'TOURM_DEMO_DIR_URI' ) ){
    define( 'TOURM_DEMO_DIR_URI', TOURM_DIR_URI.'inc/demo-data/' );
}