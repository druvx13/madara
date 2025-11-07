<?php

use App\Madara;

add_filter('wp_manga_chapters_sort', 'madara_wp_manga_chapters_sort');

function madara_wp_manga_chapters_sort( $sort_option ){
	$sort_option = App\Madara::getOption('manga_chapters_order', 'name_desc');

	if( in_array( $sort_option, array( 'name_desc', 'name_asc' ) ) ){
		$sort_option = array(
			'sortBy' => 'name',
			'sort'   => $sort_option == 'name_desc' ? 'desc' : 'asc'
		);
	} elseif(in_array( $sort_option, array( 'date_desc', 'date_asc' ) )){
		$sort_option = array(
			'sortBy' => 'date',
			'sort'   => $sort_option == 'date_desc' ? 'desc' : 'asc',
		);
	} else {
		$sort_option = array(
			'sortBy' => 'index',
			'sort'   => $sort_option == 'index_desc' ? 'desc' : 'asc',
		);
	}
	
	$reading_manga_chapters_order = App\Madara::getOption('manga_chapters_select_order', 'default');
	if((is_manga_reading_page() || (wp_doing_ajax() && isset($_GET['postID']) && $_GET['postID'] != '' && isset($_GET['chapter']) && $_GET['chapter'] != '')) && $reading_manga_chapters_order == 'reverse'){
		$sort_option['sort'] = ($sort_option['sort'] == 'desc' ? 'asc' : 'desc');
	}
    
    $sort_option['vol_order'] = App\Madara::getOption('manga_volumes_order', 'desc');

	return $sort_option;
}

// support Imagify
add_filter('imagify_picture_img_attributes', 'madara_imagify_picture_img_attributes', 10, 2);
function madara_imagify_picture_img_attributes($attributes, $image ){
	$lazyload  = Madara::getOption( 'lazyload', 'off' );
	if($lazyload == 'on'){
		$attributes['class'] = 'img-responsive lazyload effect-fade';
	}
	return $attributes;
}

add_filter('imagify_picture_attributes', 'madara_imagify_picture_attributes', 10, 2);
function madara_imagify_picture_attributes($attributes, $image ){
	$lazyload  = Madara::getOption( 'lazyload', 'off' );
	if($lazyload == 'on'){
		$attributes['class'] = '';
	}
	return $attributes;
}

add_filter('wp_manga_latest_chaptes_count', 'madara_change_latest_chaptes_count');
function madara_change_latest_chaptes_count( $count ){
    $count = Madara::getOption('manga_archive_latest_chapters_visible', $count);
    if($count == 0) return 1; // not allow 0 as it will show all chapters. Filter 'wp_manga_meta_visibility' will do the trick to hide latest chapters
    return $count;
}

add_filter('wp_manga_meta_visibility', 'madara_wp_manga_meta_visibility');
function madara_wp_manga_meta_visibility( $visibility ){
    $count = Madara::getOption('manga_archive_latest_chapters_visible', 1);
    if($count == 0) return false; // hide if number of visibile latest chapters is 0
    
    return $visibility;
}

add_filter( 'body_class', 'madara_speaker_body_classes' );
function madara_speaker_body_classes( $classes ){
    $speaker_sized = Madara::getOption('speaker_sized', '');
    if($speaker_sized){
        $classes[] = 'speaker-sized';
    }
    
    $speaker_position = Madara::getOption('speaker_position', '');
    if($speaker_position){
        $classes[] = 'speaker-floating';
    }
    
    return $classes;
}

add_filter('madara_page_custom_css_class', 'madara_page_custom_css_class');
function madara_page_custom_css_class($page_custom_css){
	if(function_exists('is_manga') && is_manga()){
		if(is_active_sidebar('manga_main_top_sidebar') || is_active_sidebar('manga_main_top_second_sidebar')){
			$page_custom_css .= ' top0 ';
		} else {
			if(is_active_sidebar('top_sidebar') || is_active_sidebar('top_second_sidebar')){
				$page_custom_css .= ' top0 ';
			}
		}
	} else {
		if(is_active_sidebar('top_sidebar') || is_active_sidebar('top_second_sidebar')){
			$page_custom_css .= ' top0 ';
		}
	}

	return $page_custom_css;
}

add_action('madara_ajax_query_loadmore', 'madara_ajax_query_loadmore');
function madara_ajax_query_loadmore($args){
	$manga_archives_item_layout = $args['manga_archives_item_layout'];
	
	if($manga_archives_item_layout == 'big_thumbnail_2'){
		$manga_archives_item_layout = 'big_thumbnail';
		$bigthumbnail_layout2 = 'overlay';

		set_query_var('manga_archives_item_layout', $manga_archives_item_layout);
		set_query_var('manga_archives_item_bigthumbnail', $bigthumbnail_layout2);
	}

	$manga_archives_item_columns = Madara::getOption( 'manga_archives_item_columns', 1 );
	set_query_var('manga_archives_item_columns', 0);
}