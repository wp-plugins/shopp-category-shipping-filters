<?php
/*
Plugin Name: Shopp Category Shipping Filters
Plugin URI: http://clifgriffin.com
Description:  Allows filtering of shipping options based on the categories cart items belong to.
Version: 1.0
Author: Clifton H. Griffin II
Author URI: http://clifgriffin.com
*/

class ShoppCategoryShippingFilters {
	function __construct() {
		add_action('admin_menu', array($this, 'admin_menu'), 11);
		add_action('init', array($this, 'handle_save'));
		add_action('shopp_calculate_shipping', array($this, 'filter_shipping_options'), 100, 2);
	}

	function admin_menu() {
		add_submenu_page( "shopp-settings", "Shopp Category Shipping Filters", "Shipping Filters", "shopp_settings_shipping", "shopp-category-shipping-filters", array($this, "admin_page") );
	}

	function admin_page()
	{
		include_once('shopp-category-shipping-filters-admin.php');
	}

	function active_shipping_modules() {
		global $Shopp;
		$Shipping = $Shopp->Shipping;

		$shipping_modules = array();

		foreach ($Shipping->active as $name => $module) {
			$shipping_modules[] = $module->methods;
		}

		return $shipping_modules;
	}

	function get_shipping_filters() {
		return get_option('shopp_category_shipping_filters');
	}

	function set_shipping_filters($object) {
		update_option('shopp_category_shipping_filters', $object);
	}

	function handle_save() {
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == "save_shopp_category_shipping_filters") {
			$shopp_category_shipping_filters = $_REQUEST['shopp_category_shipping_filters'];

			$this->set_shipping_filters($shopp_category_shipping_filters);
		}
	}

	function filter_shipping_options(&$options, $order) {
		$cart_categories = array();
		$shipping_filters = $this->get_shipping_filters();

		if(shopp('cart','has-items')) {
			while(shopp('cart','items')) {
				$categories = wp_get_post_terms(shopp('cartitem','get-product'), 'shopp_category');

				foreach($categories as $cat) {
					$cart_categories[] = $cat->term_id;
				}
			}
		}

		$remove = array();

		foreach($options as $key => $opt) {
			$intersect = array_intersect($cart_categories, $shipping_filters[$opt->slug]);

			if(count($intersect) == 0) $remove[] = $key;
		}

		if(count($remove) > 0) {
			foreach($remove as $del) {
				unset($options[$del]);
			}
		}
	}
}

$ShoppCategoryShippingFilters = new ShoppCategoryShippingFilters();