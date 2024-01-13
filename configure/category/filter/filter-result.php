<?php


/**
 * ajax filter form action
 */

function filter_form() {
	if ( empty( $_POST ) ) {
		die();
	}
	$type                 = $_POST['data_slug_type'];
	$color                = $_POST['data_slug_color'];
	$size                 = $_POST['data_slug_size'];
	$gender               = $_POST['data_slug_gender'];
	$category_id          = $_POST['category_id'];
	$colums_two_result    = get_field( 'add_class_in_images', 'option' );
	$is_not_show_category = [ '303', '304', '305', '192' ];
	$type_attr            = [];
	$arg                  = array(
		'post_type'      => array( 'product_variation', 'product' ),
		'post_status'    => 'publish',
		'posts_per_page' => - 1,
	);
	if ( $size || $color || $gender || $type ) {
		$arg = [
			'post_type'      => [ 'product' ],
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'tax_query'      => [
				'relation' => 'AND',
			],
		];
		if ( $size ) {
			$arg['tax_query'][] = [
				'taxonomy' => 'pa_size',
				'field'    => 'slug',
				'operator' => 'IN',
				'terms'    => $size,
			];
		}
		if ( $color ) {
			$arg['tax_query'][] = [
				'taxonomy' => 'pa_color',
				'field'    => 'slug',
				'operator' => 'IN',
				'terms'    => $color,
			];
		}
		if ( $gender ) {
			$arg['tax_query'][] = [
				'taxonomy' => 'pa_gender',
				'field'    => 'slug',
				'operator' => 'IN',
				'terms'    => $gender,
			];
		}
		if ( $type ) {
			$arg['tax_query'][] = [
				'taxonomy' => 'pa_typeproduct',
				'field'    => 'slug',
				'operator' => 'IN',
				'terms'    => $type,
			];
		}
	}
	$query    = new WC_Product_Query( $arg );
	$products = $query->get_products();
	ob_start();
	if ( ! empty( $products ) && ! empty( $type ) || ! empty( $gender ) || ! empty( $color ) || ! empty( $size ) ) : ?>
		<div class="filter-result">
			<?php foreach ( $products as $product ):
				$product_variable = new WC_Product_Variable( $product->get_id() );
				$variations = $product_variable->get_available_variations();
				if ( ! empty( $type ) && $size == '' && $color == '' || ! empty( $gender ) && $size == '' && $color == '' ) :
					if ( $product->is_type( 'variable' ) ) :
						$attribute_pa_color = [];
						$images = [];
						$genders_product = wc_get_product_terms( $product->get_id(), 'pa_gender', array( 'fields' => 'all' ) );
						foreach ( $variations as $variation ) :
							if ( ! empty( $type ) && empty( $gender ) ) {
								if ( ! empty( $variation['attributes']["attribute_pa_size"] ) ) {
									if ( $variation['attributes']["attribute_pa_size"] == 'm' ) :
										$images []             = $variation['image']['thumb_src'];
										$attribute_pa_color [] = $variation['attributes']["attribute_pa_color"] == $color;
									endif;
								} elseif ( empty( $variation['attributes']["attribute_pa_size"] ) ) {
									$images []             = $variation['image']['thumb_src'];
									$attribute_pa_color [] = $variation['attributes']["attribute_pa_color"] == $color;
								}
							}
							if ( ! empty( $gender ) && empty( $type ) ) {
								foreach ( $genders_product as $gender_product ) {
									foreach ( $gender as $value ) {
										if ( $gender_product->slug === $value ) {
											if ( ! empty( $variation['attributes']["attribute_pa_size"] ) ) {
												if ( $variation['attributes']["attribute_pa_size"] == 'm' ) :
													$images []             = $variation['image']['thumb_src'];
													$attribute_pa_color [] = $variation['attributes']["attribute_pa_color"] == $color;
												endif;
											} elseif ( empty( $variation['attributes']["attribute_pa_size"] ) && $gender_product->slug === 'unisex' ) {
												$images []             = $variation['image']['thumb_src'];
												$attribute_pa_color [] = $variation['attributes']["attribute_pa_color"] == $color;
											} elseif ( empty( $variation['attributes']["attribute_pa_size"] ) ) {
												foreach ( $is_not_show_category as $category ) {
													if ( $category_id !== $category ) {
														$attribute_pa_color [] = $variation['attributes']["attribute_pa_color"] == $color;
													} else {
														$products = new WP_Query( array(
															'post_type'      => array( 'product' ),
															'post_status'    => 'publish',
															'posts_per_page' => - 1,
															'tax_query'      => array(
																'relation' => 'AND',
																array(
																	'taxonomy' => 'pa_typeproduct',
																	'field'    => 'slug',
																	'terms'    => 'bags',
																	'operator' => 'IN',
																),
															),
														) );
														if ( $products->have_posts() ): while ( $products->have_posts() ):
															$products->the_post();
															$price = get_post_meta( get_the_ID(), '_price', true ); ?>
															<div class="filter-item">
																<div
																	class="images-filter '<?php echo $colums_two_result; ?> '">
																	<a class="link-filter-image"
																	   href="<?php echo get_permalink( $products->post->id ); ?>">
																		<?php echo get_the_post_thumbnail( $products->post->id, array(
																			447,
																			596
																		) ); ?>
																	</a>
																	<div class="filter-title">
																		<a class="link-filter-title"
																		   href="<?php echo get_permalink( $products->post->id ); ?>"><?php echo get_the_title( $products->post->id ); ?></a>
																		<p class=""><?php echo $price ?></p>
																	</div>
																</div>
															</div>
														<?php endwhile;
															wp_reset_postdata();
															die();
														endif;
													}
												}
											}
										}
									}
								}
							} elseif ( ! empty( $gender ) && ! empty( $type ) ) {
								foreach ( $genders_product as $gender_product ) {
									foreach ( $gender as $value ) {
										foreach ( $type as $type_value ) {
											if ( $gender_product->slug === $value ) {
												if ( $value === 'unisex' ) {
													$images []             = $variation['image']['thumb_src'];
													$attribute_pa_color [] = $variation['attributes']["attribute_pa_color"] == $color;
												} elseif ( $value === 'female' ) {
													if ( $type_value === 'bags' ) {
														$images []             = $variation['image']['thumb_src'];
														$attribute_pa_color [] = $variation['attributes']["attribute_pa_color"] == $color;

													} else {
														if ( $variation['attributes']["attribute_pa_size"] == 'm' ) :
															$images []             = $variation['image']['thumb_src'];
															$attribute_pa_color [] = $variation['attributes']["attribute_pa_color"] == $color;
														endif;
													}
												} elseif ( $value === 'male' ) {
													if ( $variation['attributes']["attribute_pa_size"] == 'm' ) :
														$images []             = $variation['image']['thumb_src'];
														$attribute_pa_color [] = $variation['attributes']["attribute_pa_color"] == $color;
													endif;
												}
											}
										}
									}
								}
							}
						endforeach;
						if ( ! empty( $images ) ) :
							foreach ( array_unique( $images ) as $image ) :
								$product_size      = get_field( 'add_size_product', $product->get_id() );
								$colums_two_result = get_field( 'add_class_in_images', 'option', $product->get_id() );
								$price             = number_format( $product->get_price(), 2, '.', '' );
								$price             = rtrim( $price, '0' );
								$price             = rtrim( $price, '.' );
								if ( $product_size ) {
									echo '<div class="images-filter ' . $colums_two_result . '">
		                              <a class="link-filter-image" href="' . get_permalink( $product->get_id() ) . '">' .
									     "<img  width='447' height='596' src=" . $image . ">" . '<p class="size-product">' . $product_size . '</p>' .
									     '</a>' .
									     '<div class="filter-title">
			                          <a class="link-filter-title" href="' . get_permalink( $product->get_id() ) . '">' . get_the_title( $product->get_id() ) . '</a>' .
									     '<p class="">' . $price . ' ' . '<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol() . '</span></p>
			                      </div>
		                         </div>';
								} else {
									echo '<div class="images-filter ' . $colums_two_result . '">
		                                   <a class="link-filter-image" href="' . get_permalink( $product->get_id() ) . '">' .
									     "<img  width='447' height='596' src=" . $image . ">" .
									     '</a>' .
									     '<div class="filter-title">
			                              <a class="link-filter-title" href="' . get_permalink( $product->get_id() ) . '">' . get_the_title( $product->get_id() ) . '</a>' .
									     '<p class="">' . $price . ' ' . '<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol() . '</span></p>
			                              </div>
		                              </div>';
								}
							endforeach;
						endif;
					endif;
				endif;
				if ( ! empty( $size ) && ! empty( $color ) && $type == '' || ! empty( $size ) && ! empty( $color ) && ! empty( $type ) ) :
					if ( $product->is_type( 'variable' ) ) :
						$images          = [];
						$size_attr_list  = [];
						$genders_product = wc_get_product_terms( $product->get_id(), 'pa_gender', array( 'fields' => 'all' ) );
						foreach ( $variations as $variation ) :
							foreach ( $color as $color_attr ) :
								foreach ( $size as $size_attr ) :
									if ( $gender ) {
										foreach ( $genders_product as $gender_product ) :
											foreach ( $gender as $item ) {
												if ( $gender_product->slug === $item ) {
													if ( $variation['attributes']["attribute_pa_size"] == $size_attr && $variation['attributes']["attribute_pa_color"] == $color_attr ) :
														$images []         = $variation['image']['thumb_src'];
														$size_attr_list [] = $size_attr;
													endif;
												} elseif ( $gender_product->slug === $item ) {
													if ( $variation['attributes']["attribute_pa_size"] == $size_attr && $variation['attributes']["attribute_pa_color"] == $color_attr ) :
														$images []         = $variation['image']['thumb_src'];
														$size_attr_list [] = $size_attr;
													endif;
												}
											}
										endforeach;
									} else {
										if ( $variation['attributes']["attribute_pa_size"] == $size_attr && $variation['attributes']["attribute_pa_color"] == $color_attr ) :
											$images []         = $variation['image']['thumb_src'];
											$size_attr_list [] = $size_attr;
										endif;
									}
								endforeach;
							endforeach;
						endforeach;
						if ( ! empty( $images ) ) :
							foreach ( array_unique( $size_attr_list ) as $size_list ) :
								foreach ( array_unique( $images ) as $image ) :
									$price = number_format( $product->get_price(), 2, '.', '' );
									$price = rtrim( $price, '0' );
									$price = rtrim( $price, '.' );
									echo '<div class="images-filter ' . $colums_two_result . '">
									<a class="link-filter-image" href="' . get_permalink( $product->get_id() ) . '">' .
									     "<img  width='447' height='596' src=" . $image . ">" . '<p class="size-product">' . $size_list . '</p>' .
									     '</a>' .
									     '<div class="filter-title">
										<a class="link-filter-title" href="' . get_permalink( $product->get_id() ) . '">' . get_the_title( $product->get_id() ) . '</a>' .
									     '<p class="">' . $price . ' ' . '<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol() . '</span></p>
									</div>
								</div>';
								endforeach;
							endforeach;
						endif;
					endif;
					checkProductType( $product );
				endif;
				if ( ! empty( $color ) && $size == '' && $type == '' || ! empty( $color ) && ! empty( $type ) && $size == '' ) {
					if ( $product->is_type( 'variable' ) ) :
						$attribute_pa_color = [];
						$images             = [];
						$genders_product    = wc_get_product_terms( $product->get_id(), 'pa_gender', array( 'fields' => 'all' ) );
						foreach ( $variations as $variation ) :
							foreach ( $color as $color_attr ) :
								if ( $gender ) {
									foreach ( $genders_product as $gender_product ) :
										foreach ( $gender as $item ) {
											if ( $gender_product->slug === $item ) {
												if ( ! empty( $variation['attributes']["attribute_pa_size"] ) ) :
													if ( $variation['attributes']["attribute_pa_color"] == $color_attr && $variation['attributes']["attribute_pa_size"] == 'm' ) :
														$images []             = $variation['image']['thumb_src'];
														$attribute_pa_color [] = $variation['attributes']["attribute_pa_color"] == $color_attr;
													endif;
												else :
													if ( $variation['attributes']["attribute_pa_color"] == $color_attr ) :
														$images []             = $variation['image']['thumb_src'];
														$attribute_pa_color [] = $variation['attributes']["attribute_pa_color"] == $color_attr;
													endif;
												endif;
											} elseif ( $gender_product->slug === $item ) {
												if ( ! empty( $variation['attributes']["attribute_pa_size"] ) ) :
													if ( $variation['attributes']["attribute_pa_color"] == $color_attr && $variation['attributes']["attribute_pa_size"] == 'm' ) :
														$images []             = $variation['image']['thumb_src'];
														$attribute_pa_color [] = $variation['attributes']["attribute_pa_color"] == $color_attr;
													endif;
												else :
													if ( $variation['attributes']["attribute_pa_color"] == $color_attr ) :
														$images []             = $variation['image']['thumb_src'];
														$attribute_pa_color [] = $variation['attributes']["attribute_pa_color"] == $color_attr;
													endif;
												endif;
											}
										}
									endforeach;
								} else {
									if ( ! empty( $variation['attributes']["attribute_pa_size"] ) ) :
										if ( $variation['attributes']["attribute_pa_color"] == $color_attr && $variation['attributes']["attribute_pa_size"] == 'm' ) :
											$images []             = $variation['image']['thumb_src'];
											$attribute_pa_color [] = $variation['attributes']["attribute_pa_color"] == $color_attr;
										endif;
									else :
										if ( $variation['attributes']["attribute_pa_color"] == $color_attr ) :
											$images []             = $variation['image']['thumb_src'];
											$attribute_pa_color [] = $variation['attributes']["attribute_pa_color"] == $color_attr;
										endif;
									endif;
								}
							endforeach;
						endforeach;
						if ( ! empty( $images ) ) :
							foreach ( $images as $image ) :
								if ( ! empty( $attribute_pa_color ) ) : ?>
									<?php getImagesVariationFilter( $product, $image );
								endif;
							endforeach;
						endif;
					endif;
					checkProductType( $product );
				}
				if ( ! empty( $size ) && $color == '' && $type == '' || ! empty( $size ) && ! empty( $type ) && $color == '' ) :
					if ( $product->is_type( 'variable' ) ) :
						$attribute_pa_size = [];
						$images            = [];
						$size_attr_list    = [];
						$genders_product   = wc_get_product_terms( $product->get_id(), 'pa_gender', array( 'fields' => 'all' ) );
						foreach ( $variations as $variation ) :
							foreach ( $size as $size_attr ) :
								if ( $gender ) {
									foreach ( $genders_product as $gender_product ) :
										foreach ( $gender as $item ) {
											if ( $gender_product->slug === $item ) {
												if ( $variation['attributes']["attribute_pa_size"] == $size_attr ) :
													$images []            = $variation['image']['thumb_src'];
													$attribute_pa_size [] = $variation['attributes']["attribute_pa_size"] == $size_attr;
													$size_attr_list []    = $size_attr;
												endif;
											} elseif ( $gender_product->slug === $item ) {
												if ( $variation['attributes']["attribute_pa_size"] == $size_attr ) :
													$images []            = $variation['image']['thumb_src'];
													$attribute_pa_size [] = $variation['attributes']["attribute_pa_size"] == $size_attr;
													$size_attr_list []    = $size_attr;
												endif;
											}
										}
									endforeach;
								} else {
									if ( $variation['attributes']["attribute_pa_size"] == $size_attr ) :
										$images []            = $variation['image']['thumb_src'];
										$attribute_pa_size [] = $variation['attributes']["attribute_pa_size"] == $size_attr;
										$size_attr_list []    = $size_attr;
									endif;
								}
							endforeach;
						endforeach;
						if ( ! empty( $images ) && ! empty( $attribute_pa_size ) && ! empty( $gender ) ) :
							foreach ( array_unique( $size_attr_list ) as $size_list ) :
								foreach ( array_unique( $images ) as $image ) :
									$price = number_format( $product->get_price(), 2, '.', '' );
									$price = rtrim( $price, '0' );
									$price = rtrim( $price, '.' );
									echo '<div class="images-filter ' . $colums_two_result . '">
									<a class="link-filter-image" href="' . get_permalink( $product->get_id() ) . '">' .
									     "<img  width='447' height='596' src=" . $image . ">" . '<p class="size-product">' . $size_list . '</p>' .
									     '</a>' .
									     '<div class="filter-title">
										<a class="link-filter-title" href="' . get_permalink( $product->get_id() ) . '">' . get_the_title( $product->get_id() ) . '</a>' .
									     '<p class="">' . $price . ' ' . '<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol() . '</span></p>
									</div>
								</div>';
								endforeach;
							endforeach;
						endif;
						if ( ! empty( $images ) && empty( $gender ) && ! empty( $attribute_pa_size ) ) :
							foreach ( array_unique( $size_attr_list ) as $size_list ) :
								foreach ( array_unique( $images ) as $image ) :
									$price = number_format( $product->get_price(), 2, '.', '' );
									$price = rtrim( $price, '0' );
									$price = rtrim( $price, '.' );
									echo '<div class="images-filter ' . $colums_two_result . '">
									<a class="link-filter-image" href="' . get_permalink( $product->get_id() ) . '">' .
									     "<img  width='447' height='596' src=" . $image . ">" . '<p class="size-product">' . $size_list . '</p>' .
									     '</a>' .
									     '<div class="filter-title">
										<a class="link-filter-title" href="' . get_permalink( $product->get_id() ) . '">' . get_the_title( $product->get_id() ) . '</a>' .
									     '<p class="">' . $price . ' ' . '<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol() . '</span></p>
									</div>
								</div>';
								endforeach;
							endforeach;
						endif;
					endif;
					checkProductType( $product );
				endif;
			endforeach; ?>
		</div>
		<?php wp_reset_postdata();
	else : ?>
		<?php checkHaveProduct() ?>
	<?php endif;
	echo ob_get_clean();
	die();
}

add_action( 'wp_ajax_filter_form', 'filter_form' );
add_action( 'wp_ajax_nopriv_filter_form', 'filter_form' );

/**
 * getting variable pictures from filter
 */

function getImagesVariationFilter( $product, $image ) {
	$product_size      = get_field( 'add_size_product', $product->get_id() );
	$colums_two_result = get_field( 'add_class_in_images', 'option', $product->get_id() );
	$price             = number_format( $product->get_price(), 2, '.', '' );
	$price             = rtrim( $price, '0' );
	$price             = rtrim( $price, '.' );
	if ( $product_size ) {
		echo '<div class="images-filter ' . $colums_two_result . '">
		        <a class="link-filter-image" href="' . get_permalink( $product->get_id() ) . '">' .
		     "<img  width='447' height='596' src=" . $image . ">" . '<p class="size-product">' . $product_size . '</p>' .
		     '</a>' .
		     '<div class="filter-title">
			    <a class="link-filter-title" href="' . get_permalink( $product->get_id() ) . '">' . get_the_title( $product->get_id() ) . '</a>' .
		     '<p class="">' . $price . ' ' . '<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol() . '</span></p>
			 </div>
		  </div>';
	} else {
		echo '<div class="images-filter ' . $colums_two_result . '">
		        <a class="link-filter-image" href="' . get_permalink( $product->get_id() ) . '">' .
		     "<img  width='447' height='596' src=" . $image . ">" .
		     '</a>' .
		     '<div class="filter-title">
			    <a class="link-filter-title" href="' . get_permalink( $product->get_id() ) . '">' . get_the_title( $product->get_id() ) . '</a>' .
		     '<p class="">' . $price . ' ' . '<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol() . '</span></p>
			 </div>
		  </div>';
	}


}

/**
 * getting simple pictures from filter
 */

function getImagesSimpleFilter( $product ) {
	$colums_two_result = get_field( 'add_class_in_images', 'option', $product->get_id() );
	$price             = number_format( $product->get_price(), 2, '.', '' );
	$price             = rtrim( $price, '0' );
	$price             = rtrim( $price, '.' );
	echo '<div class="images-filter ' . $colums_two_result . '">' .
	     '<a class="link-filter-image" href="' . get_permalink( $product->get_id() ) . ' ">' .
	     get_the_post_thumbnail( $product->get_id(), array( 447, 596 ) ) . '</a>' .
	     '<div class="filter-title">' .
	     '<a class="link-filter-title"  href="' . get_permalink( $product->get_id() ) . '">' . get_the_title( $product->get_id() ) . '</a>' .
	     '<p class="">' . $price . ' ' . '<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol() . '</span></p>
			</div>
		</div>';
}

function checkProductType( $product ) {
	if ( $product->is_type( 'simple' ) ) :
		getImagesSimpleFilter( $product );
	endif;
}

function checkHaveProduct() { ?>
	<script>
        jQuery(document).ready(function ($) {
            $(".products").removeClass('filter-resual-products');
        });
	</script>
	<?php
}




