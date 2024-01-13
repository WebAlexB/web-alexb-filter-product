<?php
if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
	$product_colors            = get_field( 'color_tax', 'pa_color_' . get_queried_object()->term_id );
	$product_size              = get_field( 'size_tax', 'pa_size_' . get_queried_object()->term_id );
	$product_type              = get_field( 'type_tax', 'pa_typeproduct_' . get_queried_object()->term_id );
	$product_gender            = get_field( 'gender_tax', 'pa_gender_' . get_queried_object()->term_id );
	$product_material          = get_field( 'material_tax', 'pa_material_' . get_queried_object()->term_id );
	$attribute_type_enable     = get_field( 'attribute_type_product', 'product_cat_' . get_queried_object()->term_id );
	$attribute_size_enable     = get_field( 'attribute_size', 'product_cat_' . get_queried_object()->term_id );
	$attribute_color_enable    = get_field( 'attribute_color', 'product_cat_' . get_queried_object()->term_id );
	$attribute_gender_enable   = get_field( 'attributes_gender', 'product_cat_' . get_queried_object()->term_id );
	$attribute_material_enable = get_field( 'attributes_material', 'product_cat_' . get_queried_object()->term_id );
	$category                  = get_queried_object();
	?>
	<div id="sticky-filter">
		<div class="filter-product">
			<div class="title-filters">
				<div class="title-block-filter">
					<div id="title-filter" class="filter filter-dropdown">
						<h3><?php echo esc_html( 'Фільтри' ); ?></h3>
					</div>
					<div id="title-sort" class="sorter filter-dropdown">
						<h3><?php echo esc_html( 'Сортувати' ); ?></h3>
					</div>
				</div>
			</div>
			<section class="alba-sort-item">
				<div class="sort-filter">
					<div class="section-mobile-sort">
						<div class="block-sort">
							<p class="title-sort-mobile">Сортувати</p>
							<span class="mobile-sort-close"></span>
						</div>
					</div>
					<div class="sort-action" data-category="<?= $category->term_id; ?>">
						<div class="title-sort" data-slug="one">
							<span class="square-filter"><span></span></span>
							<p class="sort"><?php echo esc_html( 'Останні надходження' ); ?></p>
						</div>
						<div class="title-sort" data-slug="two">
							<span class="square-filter"><span></span></span>
							<p class="sort"><?php echo esc_html( 'Популярні товари' ); ?></p>
						</div>
						<div class="title-sort" data-slug="three">
							<span class="square-filter"><span></span></span>
							<p class="sort"><?php echo esc_html( 'Ціна від дорогої до дешевої' ); ?></p>
						</div>
						<div class="title-sort" data-slug="four">
							<span class="square-filter"><span></span></span>
							<p class="sort"><?php echo esc_html( 'Ціна від дешевої до дорогої' ); ?></p>
						</div>
					</div>
				</div>
			</section>
			<section class="alba-filter-item">
				<div class="content-filter-product">
					<div class="alba-item">
						<div>
							<div class="remove-filters"><?php echo esc_html( 'Видалити фільтри' ); ?></div>
						</div>
						<div class="desktop-apply-filter">
							<div class="apply-filters"><?php echo esc_html( 'Застосувати фільтри' ); ?></div>
						</div>
						<div><i class="cross-filter"></i></div>
					</div>
					<div class="alba-filter-content" data-category="<?= $category->term_id; ?>">
						<?php if ( ! empty( $attribute_gender_enable ) && ! empty( $product_gender ) ) { ?>
							<div class="attributes-filter">
								<div class="alba-attribute-gender"><h4><?php echo esc_html( 'Стать' ); ?></h4><span
										class="number-attr-5">0</span></div>
								<div class="result-gender">
									<?php foreach ( $product_gender as $gender ) {
										$term_gender = get_term( $gender );
										?>
										<div class="filter-gender active-filter"
										     data-slug="<?= $term_gender->slug; ?>"><span
												class="square-gender-filter"><span></span></span>
											<p class="value-att-gender"><?php echo $term_gender->name ?></p></div>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
						<div class="border-filter-bottom"></div>
						<?php if ( ! empty( $attribute_type_enable ) && ! empty( $product_type ) ) { ?>
							<div class="attributes-filter">
								<div class="alba-attribute-type"><h4><?php echo esc_html( 'Тип' ); ?></h4><span
										class="number-attr-1">0</span></div>
								<div class="result-typeproduct">
									<?php foreach ( $product_type as $type ) {
										$term_type = get_term( $type );
										?>
										<div class="filter-typeproduct active-filter"
										     data-slug="<?= $term_type->slug; ?>"><span
												class="square-type-filter"><span></span></span>
											<p class="value-att-type"><?php echo $term_type->name ?></p>
										</div>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
						<div class="border-filter-bottom"></div>
						<?php if ( ! empty( $attribute_color_enable ) && ! empty( $product_colors ) ) { ?>
							<div class="attributes-filter">
								<div class="alba-attribute-color"><h4><?php echo esc_html( 'Колір' ); ?></h4><span
										class="number-attr-2">0</span></div>
								<div class="result-color result">
									<?php foreach ( $product_colors as $color ) {
										$term_color = get_term( $color );
										$color_code = get_term_meta( $term_color->term_id )["wpa_color"][0];
										?>
										<div class="filter-color active-filter"
										     data-slug="<?= $term_color->slug; ?>"
										     data-category="<?= $category->term_id; ?>"><span
												class="color-code"
												style="background:<?php echo $color_code; ?>"></span>
											<p class="value-att-color"><?php echo $term_color->name ?></p></div>
										<?php
									} ?>
								</div>
							</div>
						<?php } ?>
						<div class="border-filter-bottom"></div>
						<?php if ( ! empty( $attribute_size_enable ) && ! empty( $product_size ) ) { ?>
							<div class="attributes-filter">
								<div class="alba-attribute-size"><h4><?php echo esc_html( 'Розмір' ); ?></h4><span
										class="number-attr-3">0</span></div>
								<div class="result-size">
									<?php foreach ( $product_size as $size ) {
										$term_size = get_term( $size );
										?>
										<div class="filter-size active-filter" data-slug="<?= $term_size->slug; ?>"
										     data-category="<?= $category->term_id; ?>"><p
												class="value-att-size"><?php echo $term_size->name ?></p></div>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
						<?php if ( ! empty( $attribute_material_enable ) && ! empty( $product_material ) ) { ?>
							<div class="border-filter-bottom"></div>
							<div class="attributes-filter">
								<div class="alba-attribute-material"><h4><?php echo esc_html( 'Матеріал' ); ?></h4>
									<span
										class="number-attr-4">0</span></div>
								<div class="result-material">
									<?php foreach ( $product_material as $material ) {
										$term_material = get_term( $material );
										?>
										<div class="filter-material active-filter"
										     data-slug="<?= $term_material->slug; ?>"><span
												class="square-material-filter"><span></span></span>
											<p class="value-att-material"><?php echo $term_material->name ?></p>
										</div>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
					</div>
					<div class="mobile-apply-filter">
						<div class="apply-filters">
							<?php echo esc_html( 'Застосувати фільтри' ); ?>
						</div>
					</div>
			</section>
			<div class="response"></div>
		</div>
	</div>
<?php } ?>
