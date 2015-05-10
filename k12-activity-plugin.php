<?php
/**
 * Plugin Name: K12 Activity Plguin
 * Plugin URI: https://github.com/k12newsnetwork/k12-activity-plugin
 * Description: Add awesome to the activity feed.
 * Version: 1.0.1
 * Author: Bradford Knowlton
 * Author URI: http://bradknowlton.com
 * License: GPL2
 */
 
function post_published_notification( $ID, $post ) {
    $author = $post->post_author; /* Post author ID. */
    $name = get_the_author_meta( 'display_name', $author );
    $email = get_the_author_meta( 'user_email', $author );
    // $url = get_the_author_meta( 'user_url', $author );
    $url = bp_core_get_user_domain( $author );
    $title = $post->post_title;
    $permalink = get_permalink( $ID );
    $edit = get_edit_post_link( $ID, '' );
    $to[] = sprintf( '%s <%s>', $name, $email );
    $action = sprintf( 'Published: %s', $title );
    $content = sprintf ('Congratulations, %s! Your article %s has been published.' . "\n\n", $name, $title );
    $link = $permalink;
    
    $action_String = 'publish-post';
    $activity_content = sprintf ('Congratulations, %s! Your article has been published.' . "\n\n", $name, $title );
    
    $type = 'activity';
    $component = 'custom';
    
    $args = array(
		'action' => '<a href="'.$url.'">'.$name.'</a> wrote a new blog post, <a href="'.$permalink.'">'.$title.'</a> for group <a href=""></a>',	
		'component' => 'groups',
		'type' => 'activity_update',
		'primary_link' => $url,
		'user_id' => $author,
		'item_id' => '1', // The numeric id of the group
		'secondary_item_id' => $ID,
		'content' => get_excerpt_by_id($ID),
	);
    
    $activity_id = bp_activity_add( $args );
        
}
add_action( 'publish_post', 'post_published_notification', 10, 2 );


function get_excerpt_by_id($post_id){
    $the_post = get_post($post_id); //Gets post ID
    $the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt
    $excerpt_length = 25; //Sets excerpt length by word count
    $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
    
    return wp_trim_words($the_excerpt, $excerpt_length, '');
}