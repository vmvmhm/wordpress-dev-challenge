<?php

if ( ! defined('ABSPATH') ) {
    die('Direct access not permitted.');
}

add_action( 'edit_page_form', 'create_edit_box_author_quotes' );


function create_edit_box_author_quotes() {
    $screens = [ 'post', 'page' ];
    foreach ( $screens as $screen ) {
        add_meta_box(
            'author_quotes_plugin',
            'Author Quotes',
            'edit_box_author_quotes',
            $screen
        );
   }
}



function edit_box_author_quotes() {
    $content = get_post_meta( get_the_id(), 'author_quotes_post_content', true );
    wp_editor(
        $content,
        'author_quotes_post_content',
        array(
            'media_buttons' =>  true,
        )
    );    
}


add_action( 'save_post', 'save_author_quotes_post_content' );
function save_author_quotes_post_content( $post_id ) {
    if(isset( $_POST['author_quotes_post_content'] ) ) {
        update_post_meta( $post_id, 'author_quotes_post_content', $_POST['author_quotes_post_content'] );
    }  
}


 