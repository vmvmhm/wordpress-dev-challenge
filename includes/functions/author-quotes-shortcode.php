<?php 
if ( ! defined('ABSPATH') ) {
    die('Direct access not permitted.');
}


function show_author_quotes($atts, $content = null) {
    $default = array(
        'post_quote_id' => '',
    );

	$post_id_quotes = shortcode_atts($default, $atts);
	$content = do_shortcode($content);

	if ($post_id_quotes["post_quote_id"] == "") {
		$content = get_post_meta(get_the_id(), 'second_content', true);
	}else{
		$content = get_post_meta($post_id_quotes["post_quote_id"], 'second_content', true);
	}
		return $content;
}


add_shortcode('mc-citacion', 'show_author_quotes');