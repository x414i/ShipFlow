<?php
// Block direct access
if( !defined( 'ABSPATH' ) ){
    exit();
}
/**
 * @Packge     : Tourm
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
 */

// enqueue css
function tourm_common_custom_css(){
	wp_enqueue_style( 'tourm-color-schemes', get_template_directory_uri().'/assets/css/color.schemes.css' );

    $CustomCssOpt  = tourm_opt( 'tourm_css_editor' );
	if( $CustomCssOpt ){
		$CustomCssOpt = $CustomCssOpt;
	}else{
		$CustomCssOpt = '';
	}

    $customcss = "";
    
    if( get_header_image() ){
        $tourm_header_bg =  get_header_image();
    }else{
        if( tourm_meta( 'page_breadcrumb_settings' ) == 'page' ){
            if( ! empty( tourm_meta( 'breadcumb_image' ) ) ){
                $tourm_header_bg = tourm_meta( 'breadcumb_image' );
            }
        }
    }
    
    if( !empty( $tourm_header_bg ) ){
        $customcss .= ".breadcumb-wrapper{
            background-image:url('{$tourm_header_bg}')!important;
        }";
    }
    
	// Theme color
	$tourmthemecolor = tourm_opt('tourm_theme_color'); 
    if( !empty( $tourmthemecolor ) ){
        list($r, $g, $b) = sscanf( $tourmthemecolor, "#%02x%02x%02x");

        $tourm_real_color = $r.','.$g.','.$b;
        if( !empty( $tourmthemecolor ) ) {
            $customcss .= ":root {
            --theme-color: rgb({$tourm_real_color});
            }";
        }
    }
    // Heading  color
	$tourmheadingcolor = tourm_opt('tourm_heading_color');
    if( !empty( $tourmheadingcolor ) ){
        list($r, $g, $b) = sscanf( $tourmheadingcolor, "#%02x%02x%02x");

        $tourm_real_color = $r.','.$g.','.$b;
        if( !empty( $tourmheadingcolor ) ) {
            $customcss .= ":root {
                --title-color: rgb({$tourm_real_color});
            }";
        }
    }
    // Body color
	$tourmbodycolor = tourm_opt('tourm_body_color');
    if( !empty( $tourmbodycolor ) ){
        list($r, $g, $b) = sscanf( $tourmbodycolor, "#%02x%02x%02x");

        $tourm_real_color = $r.','.$g.','.$b;
        if( !empty( $tourmbodycolor ) ) {
            $customcss .= ":root {
                --body-color: rgb({$tourm_real_color});
            }";
        }
    }

     // Body font
     $tourmbodyfont = tourm_opt('tourm_theme_body_font', 'font-family');
     if( !empty( $tourmbodyfont ) ) {
         $customcss .= ":root {
             --body-font: $tourmbodyfont ;
         }";
     }
 
     // Heading font
     $tourmheadingfont = tourm_opt('tourm_theme_heading_font', 'font-family');
     if( !empty( $tourmheadingfont ) ) {
         $customcss .= ":root {
             --title-font: $tourmheadingfont ;
         }";
     }


    if(tourm_opt('tourm_menu_icon_class')){
        $menu_icon_class = tourm_opt( 'tourm_menu_icon_class' );
    }else{
        $menu_icon_class = 'e00d';
    }

    if( !empty( $menu_icon_class ) ) {
        $customcss .= ":root {
            .main-menu ul.sub-menu li a:before {
                content: \"\\$menu_icon_class\";
            }
        }";
    }

	if( !empty( $CustomCssOpt ) ){
		$customcss .= $CustomCssOpt;
	}

    wp_add_inline_style( 'tourm-color-schemes', $customcss );
}
add_action( 'wp_enqueue_scripts', 'tourm_common_custom_css', 100 );