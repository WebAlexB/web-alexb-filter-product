<?php

//if ( ! is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
//	return;
//}

add_action( 'wp_ajax_sort_form', 'sort_form' );
add_action( 'wp_ajax_nopriv_sort_form', 'sort_form' );

/**
 * ajax sort form action
 */

function sort_form() {
	if ( empty( $_POST ) ) {
		die();
	}
	$sort = $_POST['sort'];
	if ( '' === $sort ) {
		checkHaveProduct();
	} else {
		$category_id = $_POST['category_id'];

		$args = array(
			'post_type'        => array( 'product_variation' ),
			'post_status'      => 'publish',
			'suppress_filters' => false,
			'posts_per_page'   => - 1,
			'meta_query'       => array(
				'relation' => 'AND',
				array(
					'key'          => '_price',
					'meta_value'   => 0,
					'meta_compare' => '<',
					'meta_type'    => 'NUMERIC',
				),
				array(
					'relation' => 'OR',
					array(
						'key'     => 'attribute_pa_size',
						'value'   => 'm',
						'compare' => 'IN',
					),
					array(
						'key'     => 'attribute_pa_size',
						'compare' => 'NOT EXISTS',
					),
				),
			),
			'tax_query'        => array(
				array(
					'taxonomy'         => 'product_cat',
					'field'            => 'id',
					'terms'            => $category_id,
					'include_children' => true,
				),
			),
		);
		if ( 'three' === $sort ) {
			$args['orderby'] = [ 'meta_value_num' => 'DESC' ];
		} elseif ( 'four' === $sort ) {
			$args['orderby'] = [ 'meta_value_num' => 'ASC' ];
		} elseif ( 'two' === $sort ) {
			$args['date_query'] = [ 'after' => '2 weeks ago' ];
		} elseif ( 'one' === $sort ) {
			$args['posts_per_page']      = [ 12 ];
			$args['ignore_sticky_posts'] = [ 2 ];
			$args['meta_key']            = [ 'total_sales' ];
		}
		ob_start();
		?>
		<div class="filter-result">
			<?php getQuery( $args ); ?>
		</div>
	<?php }
	echo ob_get_clean();
	die();
}
function getQuery( $args ) {
	$query = new WP_Query( $args );
	?>
	<?php if ( $query->have_posts() ) :
		while ( $query->have_posts() ) : $query->the_post(); ?>
			<?php $product = wc_get_product( get_the_ID() );
			$price         = number_format( $product->get_price(), 2, '.', '' );
			$price = rtrim( $price, '0' );
			$price = rtrim( $price, '.' );
			?>
			<div class="filter-item">
				<div class="images-filter">
					<a class="link-filter-image"
					   href="<?php echo get_permalink( $query->post->id ); ?>">
						<?php echo get_the_post_thumbnail( $query->post->id, array( 447, 596 ) ); ?>
					</a>
					<div class="filter-title">
						<a class="link-filter-title"
						   href="<?php the_permalink(); ?>"><?php the_title() ?></a>
						<p class=""><?php echo $price ?><span
								class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
						</p>
					</div>
				</div>
			</div>
		<?php
		endwhile;
		wp_reset_postdata();
		die();
	endif; ?>
	<?php
}