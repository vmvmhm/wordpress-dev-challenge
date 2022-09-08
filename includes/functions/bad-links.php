<?php 

// Adding menu
function my_add_menu_items(){
      add_menu_page('BadLinks Scanner', 'BadLinks Scanner', 'activate_plugins', 'BadLinks_List_Table', 'BadLinks_list_init');
}
add_action('admin_menu', 'my_add_menu_items');


function getAllBadLinksWithPostId(){
   $posts = get_posts(
        array(
            'post_type'=> array("page", "post"),
            'posts_per_page'=> -1
        )
    );
    $all_links = array();
        foreach($posts as $post){
            $post_id_per_link = array();
            preg_match_all("/href=\"(.*?)\"/i", $post->post_content, $links);

                foreach ($links[1] as $individual_link) {
                    if (!filter_var($individual_link, FILTER_VALIDATE_URL) === true) { //Verify URL integrity 
                        $link_to_post = "<a href ='".$post->guid."'>".$post->post_title."</a>";
                        $all_links[] = array("link" => "<span class='dashicons dashicons-warning'></span> ".$individual_link, "status" => "Enlace Malformado", "post_link" => $link_to_post);
                    }else{
                        $verify_status = get_http_code($individual_link);
                            if ($verify_status < "200" or  $verify_status > "399") {//Verify http status code
                                $verify_status = "code($verify_status)";
                                $all_links[] = array("link" => "<span class='dashicons dashicons-warning'></span> ".$individual_link, "status" => $verify_status, "post_link" => $link_to_post);
                            }else{
                                if (strpos($individual_link, "http:") === 0){ //Verify secure URL
                                    $all_links[] = array("link" => "<span class='dashicons dashicons-warning'></span> ".$individual_link, "status" => "Enlace inseguro", "post_link" => $link_to_post);
                                }
                            }
                        }
                    }
        }
    return $all_links;
}


 function get_http_code($url) {
    $handle = curl_init($url);
    curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($handle);
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    curl_close($handle);
    if ($httpCode == 0) {
        $httpCode = 404;
    }
    return $httpCode;         
  }



// Plugin menu callback function
function BadLinks_list_init()
{
    wp_register_style ( 'badlinkscss',  plugins_url('/wordpress-dev-challenge/assets/css/style.css)' ));
    wp_enqueue_style('badlinkscss');
     // Creating an instance
      $table = new BadLinks_List_Table();
      echo '<div class="wrap"><h2>BadLinks Scanner</h2>';
      // Prepare table
      $table->prepare_items();
      // Display table
      $table->display();
      echo '</div>';
}




