<?php

    if( ! defined( 'ABSPATH' ) ){
        exit;
    }   
    
    ?>
    <div class="wrap wp-manga-wrap">
        <h2>
            <?php echo get_admin_page_title(); ?>
        </h2>
        <form method="post">
            <h2>
                <?php esc_html_e( 'Rebuild Search Text', WP_MANGA_TEXTDOMAIN ) ?>
            </h2>
            <p><?php esc_html_e('Rebuild all search text for all series (mangas/novels) in your site. It only needs to be run once if you are from a version earlier than 1.7, or you will to rebuild all search text for some reasons. Search Text is used for keyword search, and these fields will be included:', WP_MANGA_TEXTDOMAIN);?>
                <ul style="list-style:disc; padding-left:20px">
                    <li><?php esc_html_e('Series Title',WP_MANGA_TEXTDOMAIN);?></li>
                    <li><?php esc_html_e('Series Alternative Title',WP_MANGA_TEXTDOMAIN);?></li>
                    <li><?php esc_html_e('Chapter Name & Extend Name',WP_MANGA_TEXTDOMAIN);?></li>
                </ul>
            </p>
            <p><?php esc_html_e('If you only need to search in Title, Excerpt or Description, then you don\'t need to do this.', WP_MANGA_TEXTDOMAIN);?></p>
            <p><?php esc_html_e('It will take time to rebuild the search text if your site has a lot of series and chapters, so stay tuned!', WP_MANGA_TEXTDOMAIN);?>
			<?php do_action('wp_manga_search_settings'); ?>
            </p>
            <?php
            
             $wp_manga_database = WP_MANGA_DATABASE::get_instance();;
            $wpdb = $wp_manga_database->get_wpdb();
            
            if(!$wp_manga_database->column_exists($wpdb->posts, 'wp_manga_search_text')){     
            ?>
            <p style="color:red"><?php esc_html_e('This function modify database structure. Make sure you backup your database before doing this! If your site has a lot of data, it may break in the middle', WP_MANGA_TEXTDOMAIN);?></p>
            <?php }?>
            <input type="hidden" name="wp_manga_search" value="rebuild"/>
            <p>
            <button type="submit" class="button button-primary" id="manga_rebuild_search_text"><?php esc_attr_e( 'Run', WP_MANGA_TEXTDOMAIN ) ?> <i class="fas fa-spinner fa-spin" style="display:none"></i></button>
            <button type="submit" class="button button-secondary" id="manga_cancel_rebuild_search_text" name="btnignore"><?php esc_attr_e( 'Ignore', WP_MANGA_TEXTDOMAIN ) ?></button>
            </p>
        </form>
    </div>
