<?php
/**
 * ajax attribute action
 */

function attribute_form() {
	if ( empty( $_POST ) ) {
		die();
	}
	$attr_products   = $_POST['attr_product'];
	$args            = [
		'post_type'      => [ 'product' ],
		'post_status'    => 'publish',
		'posts_per_page' => - 1,
	];
	$attributes_slug = [];
	$taxonomies      = [];
	$attr_gender     = [];
	$attr_type       = [];
	$attr_color      = [];
	$attr_size       = [];
	if ( is_array( $attr_products ) ) {
		foreach ( $attr_products as $attr_product ) {
			array_push( $attributes_slug, $attr_product["type"] );
			array_push( $taxonomies, $attr_product["attribute"] );
			if ( $attr_product["attribute"] === 'gender' ) {
				$attr_gender [] = $attr_product["type"];
			}
			if ( $attr_product["attribute"] === 'typeproduct' ) {
				$attr_type[] = $attr_product["type"];
			}
			if ( $attr_product["attribute"] === 'color' ) {
				$attr_color[] = $attr_product["type"];
			}
			if ( $attr_product["attribute"] === 'size' ) {
				$attr_size[] = $attr_product["type"];
			}
		}
		if ( $attr_size || $attr_color || $attr_gender || $attr_type ) {
			$args = [
				'post_type'      => [ 'product' ],
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
				'tax_query'      => [
					'relation' => 'AND',
				],
			];

			if ( $attr_size ) {
				$args['tax_query'][] = [
					'taxonomy' => 'pa_size',
					'field'    => 'slug',
					'operator' => 'IN',
					'terms'    => $attr_size,
				];
			}

			if ( $attr_color ) {
				$args['tax_query'][] = [
					'taxonomy' => 'pa_color',
					'field'    => 'slug',
					'operator' => 'IN',
					'terms'    => $attr_color,
				];
			}

			if ( $attr_gender ) {
				$args['tax_query'][] = [
					'taxonomy' => 'pa_gender',
					'field'    => 'slug',
					'operator' => 'IN',
					'terms'    => $attr_gender,
				];
			}

			if ( $attr_type ) {
				$args['tax_query'][] = [
					'taxonomy' => 'pa_typeproduct',
					'field'    => 'slug',
					'operator' => 'IN',
					'terms'    => $attr_type,
				];
			}
		}
	} else {
		echo json_encode( [] );
		wp_die();
	}
	$query    = new WC_Product_Query( $args );
	$products = $query->get_products();
	ob_start();
	if ( ! empty( $products ) ) {
		// removing duplicate values of attributes
		$type   = [];
		$color  = [];
		$size   = [];
		$gender = [];
		// getting the value of attributes
		$psize   = [];
		$pcolors = [];
		$ptype   = [];
		$pgender = [];

		foreach ( $products as $product ) {
			if ( $product->is_type( 'variable' ) ) {
				if ( ! empty( $attr_gender ) ) {
					process_product_terms( $product, 'pa_gender', $attributes_slug, $type, $ptype, $color, $pcolors, $size, $psize, $gender, $pgender );
				} elseif ( ! empty( $attr_type ) ) {
					process_product_terms( $product, 'pa_typeproduct', $attributes_slug, $type, $ptype, $color, $pcolors, $size, $psize, $gender, $pgender );
				} elseif ( ! empty( $attr_color ) ) {
					process_product_terms( $product, 'pa_color', $attributes_slug, $type, $ptype, $color, $pcolors, $size, $psize, $gender, $pgender );
				} elseif ( ! empty( $attr_size ) ) {
					process_product_terms( $product, 'pa_size', $attributes_slug, $type, $ptype, $color, $pcolors, $size, $psize, $gender, $pgender );
				}
			}
		}
		$data = [];
		build_data_array( $psize, 'size', $data );
		build_data_array( $ptype, 'typeproduct', $data );
		build_data_array( $pgender, 'gender', $data );
		build_data_array( $pcolors, 'color', $data );
		echo json_encode( $data );
		echo ob_get_clean();
		die();
	}
}

add_action( 'wp_ajax_attribute_form', 'attribute_form' );
add_action( 'wp_ajax_nopriv_attribute_form', 'attribute_form' );

/**
 * @param $product
 * @param $type
 * @param $ptype
 * @param $color
 * @param $pcolors
 * @param $size
 * @param $psize
 * @param $gender
 * @param $pgender
 */
function process_product_data( $product, &$type, &$ptype, &$color, &$pcolors, &$size, &$psize, &$gender, &$pgender ) {
	$types_product   = wc_get_product_terms( $product->get_id(), 'pa_typeproduct', array( 'fields' => 'all' ) );
	$colors_product  = wc_get_product_terms( $product->get_id(), 'pa_color', array( 'fields' => 'all' ) );
	$sizes_product   = wc_get_product_terms( $product->get_id(), 'pa_size', array( 'fields' => 'all' ) );
	$genders_product = wc_get_product_terms( $product->get_id(), 'pa_gender', array( 'fields' => 'all' ) );

	foreach ( $types_product as $pa_type ) {
		if ( ! in_array( $pa_type->slug, $type ) ) {
			$type[]  = $pa_type->slug;
			$ptype[] = $pa_type;
		}
	}

	foreach ( $sizes_product as $pa_size ) {
		if ( ! in_array( $pa_size->slug, $size ) ) {
			$size[]  = $pa_size->slug;
			$psize[] = $pa_size;
		}
	}

	foreach ( $genders_product as $pa_gender ) {
		if ( ! in_array( $pa_gender->slug, $gender ) ) {
			$gender[]  = $pa_gender->slug;
			$pgender[] = $pa_gender;
		}
	}

	foreach ( $colors_product as $pa_color ) {
		if ( ! in_array( $pa_color->slug, $color ) ) {
			$color[]   = $pa_color->slug;
			$pcolors[] = $pa_color;
		}
	}
}

/**
 * @param $product
 * @param $term_taxonomy
 * @param $attribute_slug
 * @param $type
 * @param $ptype
 * @param $color
 * @param $pcolors
 * @param $size
 * @param $psize
 * @param $gender
 * @param $pgender
 */
function process_product_terms( $product, $term_taxonomy, $attribute_slug, &$type, &$ptype, &$color, &$pcolors, &$size, &$psize, &$gender, &$pgender ) {
	$terms = wc_get_product_terms( $product->get_id(), $term_taxonomy, array( 'fields' => 'all' ) );
	foreach ( $terms as $term ) {
		foreach ( $attribute_slug as $value ) {
			if ( $term->slug === $value ) {
				process_product_data( $product, $type, $ptype, $color, $pcolors, $size, $psize, $gender, $pgender );
			}
		}
	}
}

/**
 * @param $terms
 * @param $key_name
 * @param $data
 */
function build_data_array( $terms, $key_name, &$data ) {
	foreach ( $terms as $term ) {
		$value  = $term->slug;
		$data[] = [ $key_name => $value ];
	}
}
