<?php
/*
Plugin Name: Web Alexb Filter Product
Description: filter products
Version: 1.0
Author: webalexb
*/

/**
 * filter Result
 */
require_once 'configure/category/filter/filter-result.php';

/**
 * filter Attribute
 */
require_once 'configure/category/filter/filter-attribute.php';

/**
 * sort
 */
require_once 'configure/category/sort.php';

/**
 * connection script
 */
require_once 'configure/connection/script.php';


add_action('woocommerce_before_shop_loop', 'web_alexb_product_filter');

function web_alexb_product_filter() {
	$filter_template_path = plugin_dir_path(__FILE__) . 'templates/section/filter/filter.php';
	if (file_exists($filter_template_path)) {
		include $filter_template_path;
	}
}