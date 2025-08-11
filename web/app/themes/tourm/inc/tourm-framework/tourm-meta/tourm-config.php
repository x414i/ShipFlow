<?php

/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'yourprefix_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

 /**
 * Only return default value if we don't have a post ID (in the 'post' query variable)
 *
 * @param  bool  $default On/Off (true/false)
 * @return mixed          Returns true or '', the blank default
 */
function tourm_set_checkbox_default_for_new_post( $default ) {
	return isset( $_GET['post'] ) ? '' : ( $default ? (string) $default : '' );
}

add_action( 'cmb2_admin_init', 'tourm_register_metabox' );

/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */

function tourm_register_metabox() {

	$prefix = '_tourm_';

	$prefixpage = '_tourmpage_';
	
	$tourm_post_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'blog_post_control',
		'title'         => esc_html__( 'Post Thumb Controller', 'tourm' ),
		'object_types'  => array( 'post' ), // Post type
		'closed'        => true
	) );

    $tourm_post_meta->add_field( array(
        'name' => esc_html__( 'Post Format Video', 'tourm' ),
        'desc' => esc_html__( 'Use This Field When Post Format Video', 'tourm' ),
        'id'   => $prefix . 'post_format_video',
        'type' => 'text_url',
    ) );

	$tourm_post_meta->add_field( array(
		'name' => esc_html__( 'Post Format Audio', 'tourm' ),
		'desc' => esc_html__( 'Use This Field When Post Format Audio', 'tourm' ),
		'id'   => $prefix . 'post_format_audio',
        'type' => 'oembed',
    ) );
	$tourm_post_meta->add_field( array(
		'name' => esc_html__( 'Post Thumbnail For Slider', 'tourm' ),
		'desc' => esc_html__( 'Use This Field When You Want A Slider In Post Thumbnail', 'tourm' ),
		'id'   => $prefix . 'post_format_slider',
        'type' => 'file_list',
    ) );
	
	$tourm_page_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'page_meta_section',
		'title'         => esc_html__( 'Page Meta / Breadcrumb Settings', 'tourm' ),
		'object_types'  => array( 'page','post','trip'), // Post type
        'closed'        => true
    ) );

    $tourm_page_meta->add_field( array(
		'name' => esc_html__( 'Page Breadcrumb Area', 'tourm' ),
		'desc' => esc_html__( 'check to display page breadcrumb area.', 'tourm' ),
		'id'   => $prefix . 'page_breadcrumb_area',
        'type' => 'select',
        'default' => '1',
        'options'   => array(
            '1'   => esc_html__('Show','tourm'),
            '2'     => esc_html__('Hide','tourm'),
        )
    ) );


    $tourm_page_meta->add_field( array(
		'name' => esc_html__( 'Page Breadcrumb Settings', 'tourm' ),
		'id'   => $prefix . 'page_breadcrumb_settings',
        'type' => 'select',
        'default'   => 'global',
        'options'   => array(
            'global'   => esc_html__('Global Settings','tourm'),
            'page'     => esc_html__('Page Settings','tourm'),
        )
	) );

    $tourm_page_meta->add_field( array(
        'name'    => esc_html__( 'Breadcumb Image', 'tourm' ),
        'desc'    => esc_html__( 'Upload an image or enter an URL.', 'tourm' ),
        'id'      => $prefix . 'breadcumb_image',
        'type'    => 'file',
        // Optional:
        'options' => array(
            'url' => false, // Hide the text input for the url
        ),
        'text'    => array(
            'add_upload_file_text' => __( 'Add File', 'tourm' ) // Change upload button text. Default: "Add or Upload File"
        ),
        'preview_size' => 'large', // Image size to use when previewing in the admin.
    ) );

    $tourm_page_meta->add_field( array(
		'name' => esc_html__( 'Page Title', 'tourm' ),
		'desc' => esc_html__( 'check to display Page Title.', 'tourm' ),
		'id'   => $prefix . 'page_title',
        'type' => 'select',
        'default' => '1',
        'options'   => array(
            '1'   => esc_html__('Show','tourm'),
            '2'     => esc_html__('Hide','tourm'),
        )
	) );

    $tourm_page_meta->add_field( array(
		'name' => esc_html__( 'Page Title Settings', 'tourm' ),
		'id'   => $prefix . 'page_title_settings',
        'type' => 'select',
        'options'   => array(
            'default'  => esc_html__('Default Title','tourm'),
            'custom'  => esc_html__('Custom Title','tourm'),
        ),
        'default'   => 'default'
    ) );

    $tourm_page_meta->add_field( array(
		'name' => esc_html__( 'Custom Page Title', 'tourm' ),
		'id'   => $prefix . 'custom_page_title',
        'type' => 'text'
    ) );

    $tourm_page_meta->add_field( array(
		'name' => esc_html__( 'Breadcrumb', 'tourm' ),
		'desc' => esc_html__( 'Select Show to display breadcrumb area', 'tourm' ),
		'id'   => $prefix . 'page_breadcrumb_trigger',
        'type' => 'switch_btn',
        'default' => tourm_set_checkbox_default_for_new_post( true ),
    ) );

    $tourm_layout_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'page_layout_section',
		'title'         => esc_html__( 'Page Layout', 'tourm' ),
        'context' 		=> 'side',
        'priority' 		=> 'high',
        'object_types'  => array( 'page', ), // Post type
        'closed'        => true
	) );

	$tourm_layout_meta->add_field( array(
		'desc'       => esc_html__( 'Set page layout container,container fluid,fullwidth or both. It\'s work only in template builder page.', 'tourm' ),
		'id'         => $prefix . 'custom_page_layout',
		'type'       => 'radio',
        'options' => array(
            '1' => esc_html__( 'Container', 'tourm' ),
            '2' => esc_html__( 'Container Fluid', 'tourm' ),
            '3' => esc_html__( 'Fullwidth', 'tourm' ),
        ),
	) );

	// code for body class//

    $tourm_layout_meta->add_field( array(
    	'name' => esc_html__( 'Insert Your Body Class', 'tourm' ),
    	'id'   => $prefix . 'custom_body_class',
    	'type' => 'text'
    ) );


    $tourm_extra_listing_meta = new_cmb2_box( array(
        'id'            => $prefixpage . 'listingmeta_section',
        'title'         => esc_html__( 'Additional Informations', 'tourm' ),
        'object_types'  => array( 'at_biz_dir' ), // Post type
        'closed'        => true,
        'context'       => 'side',
        'priority'      => 'default'
    ) );

    $tourm_extra_listing_meta->add_field( array(
        'name' => esc_html__( 'Address', 'tourm' ),
        'id'   => $prefix . 'tourm_address',
        'type' => 'text'
    ) );
    $tourm_extra_listing_meta->add_field( array(
        'name' => esc_html__( 'Short Description', 'tourm' ),
        'id'   => $prefix . 'tourm_short_description',
        'type' => 'textarea'
    ) );
    $tourm_extra_listing_meta->add_field( array(
        'name' => esc_html__( 'Bad Room', 'tourm' ),
        'id'   => $prefix . 'tourm_bed_count',
        'type' => 'text'
    ) );
    $tourm_extra_listing_meta->add_field( array(
        'name' => esc_html__( 'Bath Room', 'tourm' ),
        'id'   => $prefix . 'tourm_bath_count',
        'type' => 'text'
    ) );
    $tourm_extra_listing_meta->add_field( array(
        'name' => esc_html__( 'Room Size', 'tourm' ),
        'id'   => $prefix . 'tourm_room_size',
        'type' => 'text'
    ) );

}

add_action( 'cmb2_admin_init', 'tourm_register_taxonomy_metabox' );
/**
 * Hook in and add a metabox to add fields to taxonomy terms
 */
function tourm_register_taxonomy_metabox() {

    $prefix = '_tourm_';
	/**
	 * Metabox to add fields to categories and tags
	 */
	$tourm_term_meta = new_cmb2_box( array(
		'id'               => $prefix.'term_edit',
		'title'            => esc_html__( 'Category Metabox', 'tourm' ),
		'object_types'     => array( 'term' ),
		'taxonomies'       => array( 'category'),
	) );
	$tourm_term_meta->add_field( array(
		'name'     => esc_html__( 'Extra Info', 'tourm' ),
		'id'       => $prefix.'term_extra_info',
		'type'     => 'title',
		'on_front' => false,
	) );
	$tourm_term_meta->add_field( array(
		'name' => esc_html__( 'Category Image', 'tourm' ),
		'desc' => esc_html__( 'Set Category Image', 'tourm' ),
		'id'   => $prefix.'term_avatar',
        'type' => 'file',
        'text'    => array(
			'add_upload_file_text' => esc_html__('Add Image','tourm') // Change upload button text. Default: "Add or Upload File"
		),
	) );


	/**
	 * Metabox for the user profile screen
	 */
	$tourm_user = new_cmb2_box( array(
		'id'               => $prefix.'user_edit',
		'title'            => esc_html__( 'User Profile Metabox', 'tourm' ), // Doesn't output for user boxes
		'object_types'     => array( 'user' ), // Tells CMB2 to use user_meta as post_meta
		'show_names'       => true,
		'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
	) );
    $tourm_user->add_field( array(
		'name' => esc_html__( 'Author Designation', 'tourm' ),
		'desc' => esc_html__( 'Use This Field When Author Designation', 'tourm' ),
		'id'   => $prefix . 'author_desig',
        'type' => 'text',
    ) );
	$tourm_user->add_field( array(
		'name'     => esc_html__( 'Social Profile', 'tourm' ),
		'id'       => $prefix.'user_extra_info',
		'type'     => 'title',
		'on_front' => false,
	) );

	$group_field_id = $tourm_user->add_field( array(
        'id'          => $prefix .'social_profile_group',
        'type'        => 'group',
        'description' => __( 'Social Profile', 'tourm' ),
        'options'     => array(
            'group_title'       => __( 'Social Profile {#}', 'tourm' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'        => __( 'Add Another Social Profile', 'tourm' ),
            'remove_button'     => __( 'Remove Social Profile', 'tourm' ),
            'closed'         => true
        ),
    ) );

    $tourm_user->add_group_field( $group_field_id, array(
        'name'        => __( 'Icon Class', 'tourm' ),
        'id'          => $prefix .'social_profile_icon',
        'type'        => 'text', // This field type
    ) );

    $tourm_user->add_group_field( $group_field_id, array(
        'desc'       => esc_html__( 'Set social profile link.', 'tourm' ),
        'id'         => $prefix . 'lawyer_social_profile_link',
        'name'       => esc_html__( 'Social Profile link', 'tourm' ),
        'type'       => 'text'
    ) );
}
