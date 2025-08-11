<?php
/**
 * @Packge     : Tourm
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
 */

// Block direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
    
    /**
    *
    * Hook for Footer Content
    *
    * Hook tourm_footer_content
    *
    * @Hooked tourm_footer_content_cb 10
    *
    */
    do_action( 'tourm_footer_content' );


    /**
    *
    * Hook for Back to Top Button
    *
    * Hook tourm_back_to_top
    *
    * @Hooked tourm_back_to_top_cb 10
    *
    */
    do_action( 'tourm_back_to_top' );

    /**
    *
    * tourm grid lines
    *
    * Hook tourm_grid_lines
    *
    * @Hooked tourm_grid_lines_cb 10
    *
    */
    do_action( 'tourm_grid_lines' );

    wp_footer();
    ?>
</body>
</html>