<?php
/**
 * Plugin Name: Ceremonius SEO
 * Plugin URI: http://github.com/pandeisz
 * Description: This plugin provides tag suggestions based on the URLS referenced for better SEO
 * Version: 1.0.0
 * Author: Pandelis Zembashis
 * Author URI: http://pandelis.me
 * License: GPL2
 */



 //end req

//Add content to the head
add_action( 'wp_head', 'my_ceremonius_tags' );
function my_ceremonius_tags() {
  if( is_single() ) {
  ?>
    <meta property="og:title" content="<?php the_title() ?>" />
    <meta property="og:site_name" content="<?php bloginfo( 'name' ) ?>" />
    <meta property="og:url" content="<?php the_permalink() ?>" />
    <meta property="og:description" content="<?php the_excerpt() ?>" />
    <meta property="og:type" content="article" />

    <?php
      if ( has_post_thumbnail() ) :
        $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
    ?>
      <meta property="og:image" content="<?php echo $image[0]; ?>"/>
    <?php endif; ?>

  <?php
  }
}

//hook to publish post action
// add_action( 'publish_post', 'post_published_notification', 10, 2 );
// function post_published_notification( $ID, $post ) {
//     $email = get_the_author_meta( 'user_email', $post->post_author );
//     $subject = 'Published ' . $post->post_title;
//     $message = 'We just published your post: ' . $post->post_title . ' take a look: ' . get_permalink( $ID );
//     wp_mail( $email, $subject, $message );
// }


//Settings page
add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu() {
	add_menu_page('My Plugin Settings', 'Ceremonious SEO', 'administrator', 'ceremonious-settings', 'my_plugin_settings_page', 'dashicons-admin-generic');
}

function my_plugin_settings_page() {
  include 'form.php';
}


add_action( 'admin_init', 'my_plugin_settings' );

function my_plugin_settings() {
  //API key as referenced in the settings page
	register_setting( 'my-plugin-settings-group', 'MAJESTIC_API_KEY' );
}


function majesticRequestGetRefDomains($domain ){
  //this function returns the body of the requst as sent from majestic

  $params = array('app_api_key'=> get_option('MAJESTIC_API_KEY'),
                  'cmd'=> 'GetRefDomains',
                  'item0'=>$domain,
                  'Count'=>'10',
                  'datasource'=>'fresh'
                );
  $query = http_build_query($params);

  $majestic_endpoint = 'https://api.majestic.com/api/json?' . $query;

  $majestic_response = wp_remote_get( esc_url_raw( $majestic_endpoint ) );

  $response_code = wp_remote_retrieve_response_code( $majestic_response );
  /* Will result in $api_response being an array of data,
  parsed from the JSON response of the API listed above */
  $api_response = json_decode( wp_remote_retrieve_body( $majestic_response ), true );

  $data_count = count($api_response['DataTables']['Results']['Data']);
  $api_response_dataArr = $api_response['DataTables']['Results']['Data'];

  $categories = [];

  for ($x = 0; $x <= $data_count; $x++){
    $cat = $api_response_dataArr[$x]['TopicalTrustFlow_Topic_0'];
    array_push($categories, $cat);
  }



  //return array of categories
  return $categories;
}


/**
 * Register meta box(es).
 */
function wpdocs_register_meta_boxes() {
    add_meta_box( 'meta-box-id', __( 'Ceremonious SEO', 'textdomain' ), 'wpdocs_my_display_callback', 'post' );
}
add_action( 'add_meta_boxes', 'wpdocs_register_meta_boxes' );
/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */

function wpdocs_my_display_callback( $post ) {

    ?>

    <h1>////DEBUG</h1>

      <?php var_export(majesticRequestGetRefDomains('majestic.com')); ?>

    <hr></hr>
      <button class="ceremonious_button_click">Scan for SEO</button>
    <?php

}
/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function wpdocs_save_meta_box( $post_id ) {
    // Save logic goes here. Don't forget to include nonce checks!
}
add_action( 'save_post', 'wpdocs_save_meta_box' );


//post editing here
add_filter('the_content', 'modify_content');
function modify_content($content) {
  return $content . 'fuck';
}
