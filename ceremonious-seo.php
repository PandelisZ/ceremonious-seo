<?php
/**
 * Plugin Name: Ceremonius SEO
 * Plugin URI: http://github.com/pandeisz
 * Description: This plugin adds some Facebook Open Graph tags to our single posts.
 * Version: 1.0.0
 * Author: Pandelis Zembashis
 * Author URI: http://pandelis.me
 * License: GPL2
 */



 //end req

//Add content to the head
add_action( 'wp_head', 'ceremonius_tags' );
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
add_action( 'publish_post', 'post_published_notification', 10, 2 );
function post_published_notification( $ID, $post ) {
    $email = get_the_author_meta( 'user_email', $post->post_author );
    $subject = 'Published ' . $post->post_title;
    $message = 'We just published your post: ' . $post->post_title . ' take a look: ' . get_permalink( $ID );
    wp_mail( $email, $subject, $message );
}


//Settings page
add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu() {
	add_menu_page('My Plugin Settings', 'Ceremonious SEO', 'administrator', 'my-plugin-settings', 'my_plugin_settings_page', 'dashicons-admin-generic');
}

function my_plugin_settings_page() {
  include 'form.php';
}


add_action( 'admin_init', 'my_plugin_settings' );

function my_plugin_settings() {
  //API key as referenced in the settings page
	register_setting( 'my-plugin-settings-group', 'MAJESTIC_API_KEY' );
}
