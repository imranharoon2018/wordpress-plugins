<?php

if($k_c_a_settings["enable_vendor_name_on_shop_page"] && (is_plugin_active_byme("dokan-lite/dokan.php") || is_plugin_active_byme("dokan/dokan.php"))){
  add_action( 'woocommerce_after_shop_loop_item_title','sold_by' );
    function sold_by(){
    ?>
        </a>
        <?php
            global $product;
            $seller = get_post_field( 'post_author', $product->get_id());
             $author  = get_user_by( 'id', $seller );

            $store_info = dokan_get_store_info( $author->ID );
            if ( !empty( $store_info['store_name'] ) ) { ?>
                    <span class="details">
                        <?php printf( '<a href="%s">%s</a>', dokan_get_store_url( $author->ID ), $author->display_name ); ?>
                    </span>
            <?php 
        } 

    }
}
?>