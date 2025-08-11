<?php
/**
 * @Packge     : Tourm
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
 */


// Block direct access
if( ! defined( 'ABSPATH' ) ){
    exit;
}

 // theme option callback
function tourm_opt( $id = null, $url = null ){
    global $tourm_opt;

    if( $id && $url ){

        if( isset( $tourm_opt[$id][$url] ) && $tourm_opt[$id][$url] ){
            return $tourm_opt[$id][$url];
        }
    }else{
        if( isset( $tourm_opt[$id] )  && $tourm_opt[$id] ){ 
            return $tourm_opt[$id];
        }
    }
}


// theme logo
function tourm_theme_logo() {
    // escaping allow html
    $allowhtml = array(
        'a'    => array(
            'href' => array()
        ),
        'span' => array(),
        'i'    => array(
            'class' => array()
        )
    );
    $siteUrl = home_url('/');
    if( has_custom_logo() ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $siteLogo = '';
        $siteLogo .= '<a class="logo" href="'.esc_url( $siteUrl ).'">';
        $siteLogo .= tourm_img_tag( array(
            "class" => "img-fluid",
            "url"   => esc_url( wp_get_attachment_image_url( $custom_logo_id, 'full') )
        ) );
        $siteLogo .= '</a>';

        return $siteLogo;
    } elseif( !tourm_opt('tourm_text_title') && tourm_opt('tourm_site_logo', 'url' )  ){

        $siteLogo = '<img class="img-fluid" src="'.esc_url( tourm_opt('tourm_site_logo', 'url' ) ).'" alt="'.esc_attr__( 'logo', 'tourm' ).'" />';
        return '<a class="logo" href="'.esc_url( $siteUrl ).'">'.$siteLogo.'</a>';


    }elseif( tourm_opt('tourm_text_title') ){
        return '<h2 class="mb-0"><a class="logo" href="'.esc_url( $siteUrl ).'">'.wp_kses( tourm_opt('tourm_text_title'), $allowhtml ).'</a></h2>';
    }else{
        return '<h2 class="mb-0"><a class="logo" href="'.esc_url( $siteUrl ).'">'.esc_html( get_bloginfo('name') ).'</a></h2>';
    }
}

// custom meta id callback
function tourm_meta( $id = '' ){
    $value = get_post_meta( get_the_ID(), '_tourm_'.$id, true );
    return $value;
}


// Blog Date Permalink
function tourm_blog_date_permalink() {
    $year  = get_the_time('Y');
    $month_link = get_the_time('m');
    $day   = get_the_time('d');
    $link = get_day_link( $year, $month_link, $day);
    return $link;
}

//audio format iframe match
function tourm_iframe_match() {
    $audio_content = tourm_embedded_media( array('audio', 'iframe') );
    $iframe_match = preg_match("/\iframe\b/i",$audio_content, $match);
    return $iframe_match;
}


//Post embedded media
function tourm_embedded_media( $type = array() ){
    $content = do_shortcode( apply_filters( 'the_content', get_the_content() ) );
    $embed   = get_media_embedded_in_content( $content, $type );


    if( in_array( 'audio' , $type) ){
        if( count( $embed ) > 0 ){
            $output = str_replace( '?visual=true', '?visual=false', $embed[0] );
        }else{
           $output = '';
        }

    }else{
        if( count( $embed ) > 0 ){
            $output = $embed[0];
        }else{
           $output = '';
        }
    }
    return $output;
}


// WP post link pages
function tourm_link_pages(){
    wp_link_pages( array(
        'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'tourm' ) . '</span>',
        'after'       => '</div>',
        'link_before' => '<span>',
        'link_after'  => '</span>',
        'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'tourm' ) . ' </span>%',
        'separator'   => '<span class="screen-reader-text">, </span>',
    ) );
}


// Data Background image attr
function tourm_data_bg_attr( $imgUrl = '' ){
    return 'data-bg-img="'.esc_url( $imgUrl ).'"';
}

// image alt tag
function tourm_image_alt( $url = '' ){
    if( $url != '' ){
        // attachment id by url
        $attachmentid = attachment_url_to_postid( esc_url( $url ) );
       // attachment alt tag
        $image_alt = get_post_meta( esc_html( $attachmentid ) , '_wp_attachment_image_alt', true );
        if( $image_alt ){
            return $image_alt ;
        }else{
            $filename = pathinfo( esc_url( $url ) );
            $alt = str_replace( '-', ' ', $filename['filename'] );
            return $alt;
        }
    }else{
       return;
    }
}


// Flat Content wysiwyg output with meta key and post id

function tourm_get_textareahtml_output( $content ) {
    global $wp_embed;

    $content = $wp_embed->autoembed( $content );
    $content = $wp_embed->run_shortcode( $content );
    $content = wpautop( $content );
    $content = do_shortcode( $content );

    return $content;
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */

function tourm_pingback_header() {
    if ( is_singular() && pings_open() ) {
        echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
    }
}
add_action( 'wp_head', 'tourm_pingback_header' );


// Excerpt More
function tourm_excerpt_more( $more ) {
    return '...';
}

add_filter( 'excerpt_more', 'tourm_excerpt_more' );


// tourm comment template callback
function tourm_comment_callback( $comment, $args, $depth ) {
        $add_below = 'comment';
    ?>
    <li <?php comment_class( array('th-comment-item') ); ?>>
        <div id="comment-<?php comment_ID() ?>" class="th-post-comment">
            <?php
                if( get_avatar( $comment, 100 )  ) :
            ?>
            <!-- Author Image -->
            <div class="comment-avater">
                <?php
                    if ( $args['avatar_size'] != 0 ) {
                        echo get_avatar( $comment, 110 );
                    }
                ?>
            </div>
            <!-- Author Image -->
            <?php endif; ?>
            <!-- Comment Content -->
            <div class="comment-content">
                <div class="">
                    <h3 class="name"><?php echo esc_html( ucwords( get_comment_author() ) ); ?></h3>
                    <span class="commented-on"><?php printf( esc_html__('%1$s - %2$s', 'tourm'), get_comment_date(), get_comment_time() ); ?></span>
                </div>
                <p class="text"><?php echo get_comment_text(); ?></p>
                <div class="reply_and_edit">
                    <?php
                        $reply_text = wp_kses_post( '<i class="fas fa-reply"></i> Reply', 'tourm' );

                        $edit_reply_text = wp_kses_post( '<i class="fas fa-pencil-alt"></i> Edit', 'tourm' );

                        comment_reply_link(array_merge( $args, array( 'add_below' => $add_below, 'depth' => 3, 'max_depth' => 5, 'reply_text' => $reply_text ) ) );
                    ?>  
                </div>
                <?php if ( $comment->comment_approved == '0' ) : ?>
                <p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'tourm' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <!-- Comment Content -->
<?php
}

//body class
add_filter( 'body_class', 'tourm_body_class' );
function tourm_body_class( $classes ) {
    if( class_exists('ReduxFramework') ) {
        $tourm_blog_single_sidebar = tourm_opt('tourm_blog_single_sidebar');
        if( ($tourm_blog_single_sidebar != '2' && $tourm_blog_single_sidebar != '3' ) || ! is_active_sidebar('tourm-blog-sidebar') ) {
            $classes[] = 'no-sidebar';
        }
        $new_class = is_page() ? tourm_meta('custom_body_class') : '';

        if ( $new_class ) {
            $classes[] = $new_class;
        }
    } else {
        if( !is_active_sidebar('tourm-blog-sidebar') ) {
            $classes[] = 'no-sidebar';
        }
        $classes[] .= 'bg-light';
    }

    return $classes;
}

//Global Footer
function tourm_footer_global_option(){
    // Tourm Widget Enable Disable
    if( class_exists( 'ReduxFramework' ) ){
        $tourm_footer_widget_enable = tourm_opt( 'tourm_footerwidget_enable' );
        $tourm_footer_bottom_active = tourm_opt( 'tourm_disable_footer_bottom' );
        $tourm_footer_social_media = tourm_opt( 'tourm_footer_social_media' );
    }else{
        $tourm_footer_widget_enable = '';
        $tourm_footer_bottom_active = '1';
        $tourm_footer_social_media = '';
    }

    if( $tourm_footer_widget_enable == '1' || $tourm_footer_bottom_active == '1' ){
        
        
        echo '<!---footer-wrapper start-->';
        echo '<footer class="footer-wrapper footer-default bg-theme prebuilt-foo">';
            if( $tourm_footer_widget_enable == '1' ){
                if( ( is_active_sidebar( 'tourm-footer-1' ) || is_active_sidebar( 'tourm-footer-2' ) || is_active_sidebar( 'tourm-footer-3' ) || is_active_sidebar( 'tourm-footer-4' ) )) {
                    echo '<div class="widget-area">';
                        echo '<div class="container">';
                                echo '<div class="row justify-content-between">';
                                    if( is_active_sidebar( 'tourm-footer-1' )){
                                    dynamic_sidebar( 'tourm-footer-1' ); 
                                    }
                                    if( is_active_sidebar( 'tourm-footer-2' )){
                                    dynamic_sidebar( 'tourm-footer-2' ); 
                                    }
                                    if( is_active_sidebar( 'tourm-footer-3' )){
                                    dynamic_sidebar( 'tourm-footer-3' ); 
                                    } 
                                    if( is_active_sidebar( 'tourm-footer-4' )){
                                    dynamic_sidebar( 'tourm-footer-4' ); 
                                    }  
                                echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
            }

            if( $tourm_footer_bottom_active == '1' ){
                echo '<div class="copyright-wrap bg-light">';

                    echo '<div class="container">';
                        echo '<div class="row gy-3 align-items-center">';
                            echo '<div class="col-lg-6">';
                                echo '<p class="copyright-text">'.wp_kses_post( tourm_opt( 'tourm_copyright_text' ) ).'</p>';
                            echo '</div>';
                            if( $tourm_footer_social_media == '1' ){
                                echo '<div class="col-lg-6">';
                                    echo '<div class="th-social justify-content-lg-end justify-content-center">';
                                        echo tourm_social_icon();
                                    echo '</div>';
                                echo '</div>';
                            }
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }

        echo '</footer>';
        echo '<!---footer-wrapper end-->';
    }
}

// Social link
function tourm_social_icon(){
    $tourm_social_icon = tourm_opt( 'tourm_social_links' );
    if( ! empty( $tourm_social_icon ) && isset( $tourm_social_icon ) ){
        $social_item = '';
        foreach( $tourm_social_icon as $social_icon ){
            // if( !empty($social_icon['title']) || $social_icon['description'] ){
                $social_item .= '<a href="'.esc_url( $social_icon['url'] ).'"><i class="'.esc_attr( $social_icon['title'] ).'"></i>'.esc_attr( $social_icon['description'] ).'</a>';
            // }
        }
        return $social_item;
    }
}

// global header
function tourm_global_header_option() {

    if( class_exists( 'ReduxFramework' ) ){ 

        echo tourm_mobile_menu();

        echo '<header class="th-header header-default header-layout1 prebuilt">';

            if(tourm_opt('tourm_header_sticky')){
                $sticky = '';
            }else{
                $sticky = '-no';
            }

            if(tourm_opt('tourm_menu_icon')){ 
                $menu_icon = '';
            }else{
                $menu_icon = 'hide-icon';
            }
            if(tourm_opt('tourm_header_topbar')){
                echo '<div class="header-top">';
                    echo '<div class="container th-container">';
                        echo '<div class="row justify-content-center justify-content-xl-between align-items-center">';
                            echo '<div class="col-auto d-none d-md-block">';
                                echo '<div class="header-links">';
                                    echo '<ul>';
                                        if( !empty( tourm_opt( 'tourm_topbar_address' ) ) ){ 
                                            echo '<li class="d-none d-xl-inline-block"><i class="fa-sharp fa-regular  fa-location-dot"></i>';
                                                echo '<span>'.esc_html( tourm_opt( 'tourm_topbar_address' ) ).'</span>';
                                            echo '</li>';
                                        }
                                        if( !empty( tourm_opt( 'tourm_topbar_office_hours' ) ) ){ 
                                            echo '<li class="d-none d-xl-inline-block"><i class="fa-regular fa-clock"></i>';
                                                echo '<span>'.esc_html( tourm_opt( 'tourm_topbar_office_hours' ) ).'</span>';
                                            echo '</li>';
                                        }
                                    echo '</ul>';
                                echo '</div>';
                            echo '</div>';
                            echo '<div class="col-auto">';
                                echo '<div class="header-right">';
                                    if(tourm_opt('tourm_header_topbar_language_switcher')){ 
                                        echo '<div class="dropdown-link">';
                                            echo '<a class="dropdown-toggle" href="'.esc_url('#').'" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false">'.esc_html__('Language', 'tourm').'</a> ';
                                                echo '<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">';
                                                    echo ' <li>';
                                                    echo do_shortcode('[gtranslate]');
                                                    echo ' </li>';
                                            echo ' </ul>';
                                        echo ' </div>';
                                    }
                                    echo '<div class="header-links">';
                                        wp_nav_menu( array(
                                            "theme_location"    => 'header-top-menu',
                                            "container"         => '',
                                            "menu_class"        => ''
                                        ) ); 
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                   echo ' </div>';
                echo '</div>';
            }
            echo '<div class="sticky-wrapper'.esc_attr($sticky).'">';
                echo '<!-- Main Menu Area -->';
                echo '<div class="menu-area">';
                    echo '<div class="container th-container">';
                        echo '<div class="row align-items-center justify-content-between">';
                            echo '<div class="col-auto">';
                                echo '<div class="header-logo">';
                                    echo tourm_theme_logo();
                                echo '</div>';
                            echo '</div>';
                            echo '<div class="col-auto me-xl-auto">';
                                echo '<nav class="main-menu d-none d-xl-inline-block '.esc_attr($menu_icon).'">';
                                    wp_nav_menu( array(
                                        "theme_location"    => 'primary-menu',
                                        "container"         => '',
                                        "menu_class"        => ''
                                    ) ); 
                                echo '</nav>';
                                echo '<button type="button" class="th-menu-toggle d-block d-xl-none"><i class="far fa-bars"></i></button>';
                            echo '</div>';
                            echo '<div class="col-auto d-none d-xl-block">';
                                echo '<div class="header-button">';

                                    if( !empty(tourm_opt( 'tourm_btn_text' ) ) ){ 
                                        echo '<a href="'.esc_url(tourm_opt( 'tourm_btn_url' )).'" class="th-btn style3 th-icon">'.wp_kses_post(tourm_opt( 'tourm_btn_text' )).'</a>';
                                    }
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                    echo '<div class="logo-bg" data-mask-src="'.get_template_directory_uri().'/assets/img/logo_bg_mask.png"></div>';
                echo '</div>';
            echo '</div>';

        echo '</header>';
    }else{
        echo tourm_global_header();
    }
}



// tourm woocommerce breadcrumb
function tourm_woo_breadcrumb( $args ) {
    return array(
        'delimiter'   => '',
        'wrap_before' => '<ul class="breadcumb-menu">',
        'wrap_after'  => '</ul>',
        'before'      => '<li>',
        'after'       => '</li>',
        'home'        => _x( 'Home', 'breadcrumb', 'tourm' ),
    );
}

add_filter( 'woocommerce_breadcrumb_defaults', 'tourm_woo_breadcrumb' );

function tourm_custom_search_form( $class ) {
    echo '<!-- Search Form -->';

    echo '<form role="search" method="get" action="'.esc_url( home_url( '/' ) ).'" class="'.esc_attr( $class ).'">';
        echo '<label class="searchIcon">';
            echo tourm_img_tag( array(
                "url"   => esc_url( get_theme_file_uri( '/assets/img/search-2.svg' ) ),
                "class" => "svg"
            ) );
            echo '<input value="'.esc_html( get_search_query() ).'" name="s" required type="search" placeholder="'.esc_attr__('What are you looking for?', 'tourm').'">';
        echo '</label>';
    echo '</form>';
    echo '<!-- End Search Form -->';
}



//Fire the wp_body_open action.
if ( ! function_exists( 'wp_body_open' ) ) {
    function wp_body_open() {
        do_action( 'wp_body_open' );
    }
}

//Remove Tag-Clouds inline style
add_filter( 'wp_generate_tag_cloud', 'tourm_remove_tagcloud_inline_style',10,1 );
function tourm_remove_tagcloud_inline_style( $input ){
   return preg_replace('/ style=("|\')(.*?)("|\')/','',$input );
}

/* This code filters the Categories archive widget to include the post count inside the link */


// Tourm Default Header
if( ! function_exists( 'tourm_global_header' ) ){
    function tourm_global_header(){ ?>

        <!--Mobile menu & Search box-->
        <?php 
        echo tourm_search_box(); 
        echo tourm_mobile_menu(); 
        
        ?>

        <!--======== Header ========-->
        <header class="th-header header-default unittest-header">
            <div class="not-sticky-wrapper"> 
                <div class="sticky-active">
                    <div class="menu-area">
                        <div class="container">
                            <div class="row gx-20 align-items-center justify-content-between">

                                <div class="col-auto">
                                    <div class="header-logo">
                                        <div class="logo-bg"></div>
                                        <?php echo tourm_theme_logo(); ?>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <?php
                                    if( has_nav_menu( 'primary-menu' ) ) { ?>
                                        <nav class="main-menu d-none d-lg-inline-block">
                                            <?php
                                            wp_nav_menu( array(
                                                "theme_location"    => 'primary-menu',
                                                "container"         => '',
                                                "menu_class"        => ''
                                            ) ); ?>
                                        </nav>
                                    <?php } ?>                                   
                                    </nav>
                                    <button type="button" class="th-menu-toggle d-inline-block d-lg-none"><i class="far fa-bars"></i></button>
                                </div>
                                <div class="col-auto d-none d-xl-block">
                                    <div class="header-button">
                                        <button type="button" class="icon-btn searchBoxToggler"><i class="far fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    <?php
    }
}

//header search box
if(! function_exists('tourm_search_box')){
    function tourm_search_box(){
        echo '<div class="popup-search-box d-none d-lg-block">';
            echo '<button class="searchClose"><i class="fal fa-times"></i></button>';
            echo '<form role="search" method="get" action="'.esc_url( home_url( '/' ) ).'">';
                echo '<input value="'.esc_html( get_search_query() ).'" name="s" required type="search" placeholder="'.esc_attr__('What are you looking for?', 'tourm').'">';
                echo '<button type="submit"><i class="fal fa-search"></i></button>';
            echo '</form>';
        echo '</div>';
    }
}

//header Offcanvas
if( ! function_exists( 'tourm_header_offcanvas' ) ){
    function tourm_header_offcanvas(){
        echo '<div class="sidemenu-wrapper sidemenu-info">';
            echo '<div class="sidemenu-content">';
                echo '<button class="closeButton sideMenuCls"><i class="far fa-times"></i></button>';
                if(is_active_sidebar('tourm-offcanvas')){
                    dynamic_sidebar( 'tourm-offcanvas' );
                }else{
                    echo '<h4 class="widget_title">No Widget Added </h4>';
                    echo '<p>Please add some widget in Offcanvs Sidebar</p>';
                }
            echo '</div>';
        echo '</div>';
    }
}

//header Cart Offcanvas
if( ! function_exists( 'tourm_header_cart_offcanvas' ) ){
    function tourm_header_cart_offcanvas(){
        echo '<div class="sidemenu-wrapper sidemenu-cart">';
            echo '<div class="sidemenu-content">';
                echo '<button class="closeButton sideMenuCls"><i class="far fa-times"></i></button>';
                echo '<div class="widget woocommerce widget_shopping_cart">';
                    echo '<h3 class="widget_title">'.esc_html__( 'Shopping cart', 'tourm' ).'</h3>';
                    echo '<div class="widget_shopping_cart_content">';
                        if( class_exists( 'woocommerce' ) ){
                            echo woocommerce_mini_cart();
                        }
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }
}

// mobile logo
function tourm_mobile_logo() {
    $logo_url = tourm_opt('tourm_mobile_logo', 'url' );
    $mobile_menu = '';
    if( !empty($logo_url )){
        $mobile_menu = '<div class="mobile-logo"><a href="'.home_url('/').'"><img src="'.esc_url($logo_url).'" alt="'.esc_attr__( 'logo', 'tourm' ).'"></a></div>';
    }else{
        $mobile_menu .= '<div class="mobile-logo">';
        $mobile_menu .= tourm_theme_logo();
        $mobile_menu .= '</div>';
    }

    return $mobile_menu;
 }

//header Mobile Menu
if( ! function_exists( 'tourm_mobile_menu' ) ){
    function tourm_mobile_menu(){
    ?>
    <div class="th-menu-wrapper">
        <div class="th-menu-area text-center">
            <button class="th-menu-toggle"><i class="fal fa-times"></i></button>
            <?php  if( class_exists('ReduxFramework') ):?>
                <?php 
                    if(!empty(tourm_opt('tourm_menu_menu_show') )){
                        echo tourm_mobile_logo(); 
                    }
                ?>
            <?php else: ?>
                <div class="mobile-logo">
                    <?php echo tourm_theme_logo(); ?>
                </div>
            <?php endif; ?>
            <div class="th-mobile-menu">
                <?php 
                    if( has_nav_menu( 'primary-menu' ) ){
                        wp_nav_menu( array(
                            "theme_location"    => 'primary-menu',
                            "container"         => '',
                            "menu_class"        => ''
                        ) );
                    }
                ?>
            </div>
        </div>
    </div>

<?php
    }
}



// Blog post views function
function tourm_setPostViews( $postID ) {
    $count_key  = 'post_views_count';
    $count      = get_post_meta( $postID, $count_key, true );
    if( $count == '' ){
        $count = 0;
        delete_post_meta( $postID, $count_key );
        add_post_meta( $postID, $count_key, '0' );
    }else{
        $count++;
        update_post_meta( $postID, $count_key, $count );
    }
}

function tourm_getPostViews( $postID ){
    $count_key  = 'post_views_count';
    $count      = get_post_meta( $postID, $count_key, true );
    if( $count == '' ){
        delete_post_meta( $postID, $count_key );
        add_post_meta( $postID, $count_key, '0' );
        return __( '0', 'tourm' );
    }
    return $count;
}

add_filter( 'wp_list_categories', 'tourm_cat_count_span' );
function tourm_cat_count_span( $links ) {
    $links = str_replace('</a> (', '</a> <span class="category-number">', $links);
    $links = str_replace(')', '</span>', $links);
    return $links;
}

add_filter( 'get_archives_link', 'tourm_archive_count_span' );
function tourm_archive_count_span( $links ) {
    $links = str_replace('</a>&nbsp;(', '</a> <span class="category-number">', $links);
    $links = str_replace(')', '</span>', $links);
    return $links;
}

// Add Extra Class On Comment Reply Button
function tourm_custom_comment_reply_link( $content ) {
    $extra_classes = 'reply-btn';
    return preg_replace( '/comment-reply-link/', 'comment-reply-link ' . $extra_classes, $content);
}

add_filter('comment_reply_link', 'tourm_custom_comment_reply_link', 99);

// Add Extra Class On Edit Comment Link
function tourm_custom_edit_comment_link( $content ) {
    $extra_classes = 'reply-btn';
    return preg_replace( '/comment-edit-link/', 'comment-edit-link ' . $extra_classes, $content);
}

add_filter('edit_comment_link', 'tourm_custom_edit_comment_link', 99);


function tourm_post_classes( $classes, $class, $post_id ) {
    if ( get_post_type() === 'post' ) {
        $classes[] = "th-blog blog-single has-post-thumbnail";
    }elseif( get_post_type() === 'product' ){
        // Return Class
    }elseif( get_post_type() === 'page' ){
        $classes[] = "page--item";
    }
    
    return $classes;
}
add_filter( 'post_class', 'tourm_post_classes', 10, 3 );

// Contact form 7
add_filter('wpcf7_autop_or_not', '__return_false');

function tourm_megamenu_add_theme_tourm_1631766318($themes) {
    $themes["tourm_1631766318"] = array(
        'title' => 'Haarmax',
        'container_background_from' => 'rgb(255, 255, 255)',
        'container_background_to' => 'rgb(255, 255, 255)',
        'arrow_up' => 'dash-f343',
        'arrow_down' => 'dash-f347',
        'arrow_left' => 'dash-f341',
        'arrow_right' => 'dash-f345',
        'menu_item_background_hover_from' => 'rgb(255, 255, 255)',
        'menu_item_background_hover_to' => 'rgb(255, 255, 255)',
        'menu_item_link_font_size' => '18px',
        'menu_item_link_height' => '110px',
        'menu_item_link_color' => 'rgb(20, 20, 20)',
        'menu_item_link_weight' => 'inherit',
        'menu_item_link_color_hover' => 'rgb(133, 133, 133)',
        'menu_item_link_weight_hover' => 'inherit',
        'menu_item_highlight_current' => 'off',
        'menu_item_divider_color' => 'rgba(0, 0, 0, 0.1)',
        'panel_background_from' => 'rgb(255, 255, 255)',
        'panel_background_to' => 'rgb(255, 255, 255)',
        'panel_width' => '.mega-menu-inner',
        'panel_inner_width' => '.container',
        'panel_border_color' => 'rgb(221, 221, 221)',
        'panel_border_radius_bottom_left' => '5px',
        'panel_border_radius_bottom_right' => '5px',
        'panel_header_color' => 'rgb(173, 136, 88)',
        'panel_header_text_transform' => 'none',
        'panel_header_font_weight' => 'inherit',
        'panel_header_border_color' => 'rgb(173, 136, 88)',
        'panel_header_border_right' => '5px',
        'panel_header_border_bottom' => '1px',
        'panel_widget_padding_left' => '20px',
        'panel_widget_padding_right' => '20px',
        'panel_widget_padding_top' => '20px',
        'panel_widget_padding_bottom' => '20px',
        'panel_font_size' => '16px',
        'panel_font_color' => 'rgb(255, 255, 255)',
        'panel_font_family' => 'inherit',
        'panel_second_level_font_color' => 'rgb(173, 136, 88)',
        'panel_second_level_font_color_hover' => 'rgb(173, 136, 88)',
        'panel_second_level_text_transform' => 'none',
        'panel_second_level_font' => 'inherit',
        'panel_second_level_font_size' => '16px',
        'panel_second_level_font_weight' => 'inherit',
        'panel_second_level_font_weight_hover' => 'inherit',
        'panel_second_level_text_decoration' => 'none',
        'panel_second_level_text_decoration_hover' => 'none',
        'panel_second_level_padding_bottom' => '10px',
        'panel_second_level_margin_bottom' => '15px',
        'panel_second_level_border_color' => 'rgb(173, 136, 88)',
        'panel_second_level_border_color_hover' => 'rgb(173, 136, 88)',
        'panel_second_level_border_bottom' => '1px',
        'panel_third_level_font_color' => 'rgb(34, 34, 34)',
        'panel_third_level_font_color_hover' => 'rgb(173, 136, 88)',
        'panel_third_level_font' => 'inherit',
        'panel_third_level_font_size' => '16px',
        'panel_third_level_font_weight' => 'inherit',
        'panel_third_level_font_weight_hover' => 'inherit',
        'panel_third_level_padding_top' => '4px',
        'panel_third_level_padding_bottom' => '3px',
        'flyout_width' => '200px',
        'flyout_menu_background_from' => 'rgb(255, 255, 255)',
        'flyout_menu_background_to' => 'rgb(255, 255, 255)',
        'flyout_border_radius_bottom_left' => '5px',
        'flyout_border_radius_bottom_right' => '5px',
        'flyout_padding_top' => '9px',
        'flyout_padding_right' => '7px',
        'flyout_padding_bottom' => '9px',
        'flyout_padding_left' => '7px',
        'flyout_link_padding_left' => '9px',
        'flyout_link_padding_right' => '9px',
        'flyout_link_padding_top' => '2px',
        'flyout_link_padding_bottom' => '2px',
        'flyout_link_weight' => 'inherit',
        'flyout_link_weight_hover' => 'inherit',
        'flyout_link_height' => '26px',
        'flyout_background_from' => 'rgb(255, 255, 255)',
        'flyout_background_to' => 'rgb(255, 255, 255)',
        'flyout_background_hover_from' => 'rgb(255, 255, 255)',
        'flyout_background_hover_to' => 'rgb(255, 255, 255)',
        'flyout_link_size' => '16px',
        'flyout_link_color' => 'rgb(34, 34, 34)',
        'flyout_link_color_hover' => 'rgb(173, 136, 88)',
        'flyout_link_family' => 'inherit',
        'responsive_breakpoint' => '991px',
        'line_height' => '26px',
        'z_index' => '9999',
        'shadow' => 'on',
        'shadow_vertical' => '5px',
        'shadow_blur' => '10px',
        'transitions' => 'on',
        'toggle_background_from' => '#222',
        'toggle_background_to' => '#222',
        'mobile_background_from' => '#222',
        'mobile_background_to' => '#222',
        'mobile_menu_item_link_font_size' => '14px',
        'mobile_menu_item_link_color' => '#ffffff',
        'mobile_menu_item_link_text_align' => 'left',
        'mobile_menu_item_link_color_hover' => '#ffffff',
        'mobile_menu_item_background_hover_from' => '#333',
        'mobile_menu_item_background_hover_to' => '#333',
        'disable_mobile_toggle' => 'on',
        'custom_css' => '/** Push menu onto new line **/
#{$wrap} {
    clear: both;
    z-index:99;
    font-family:\"Open Sans\";
}',
    );
    return $themes;
}
add_filter("megamenu_themes", "tourm_megamenu_add_theme_tourm_1631766318");


//listing functions 


function tourm_custom_directorist_container() {
    return 'container'; // Replace 'my-custom-container' with your desired container class
}

// Hook your custom function to the 'directorist_container' filter
add_filter('directorist_container_fluid', 'tourm_custom_directorist_container');
