<?php

function web_alexb_enqueue_scripts() {
	if ( is_product_category() || is_shop() ) {
		// Путь к папке вашего плагина
		$plugin_path = plugin_dir_url( __FILE__ );

		// Подключаем и локализуем catalog.js
		wp_register_script( 'catalog',  $plugin_path . 'assets/js/catalog.js', array(), null, true );
		wp_enqueue_script( 'catalog' );
		wp_localize_script( 'catalog', 'filter_form',
			array(
				'url' => admin_url( 'admin-ajax.php' )
			)
		);

		// Подключаем и локализуем attribute.js
		wp_register_script( 'attribute', $plugin_path . 'assets/js/attribute.js', array(), null, true );
		wp_enqueue_script( 'attribute' );
		wp_localize_script( 'attribute', 'attribute_form',
			array(
				'url' => admin_url( 'admin-ajax.php' )
			)
		);

		// Подключаем и локализуем sort.js
		wp_register_script( 'sort', $plugin_path . 'assets/js/sort.js', array(), null, true );
		wp_enqueue_script( 'sort' );
		wp_localize_script( 'sort', 'sort_form',
			array(
				'url' => admin_url( 'admin-ajax.php' )
			)
		);

		wp_register_style( 'web-alexb-style', $plugin_path . 'assets/css/web-alexb.css', array(), null, 'all' );
		wp_enqueue_style( 'web-alexb-style' );
	}
}

add_action( 'wp_enqueue_scripts', 'web_alexb_enqueue_scripts' );


function add_color_field_to_attribute( $taxonomy ) {
	$color_value = get_term_meta( $taxonomy->term_id, 'wpa_color', true );
	?>
	<tr class="form-field wpa-color-wrap">
		<th scope="row" valign="top">
			<label for="wpa-color"><?php _e( 'Цвет', 'web-alexb' ); ?></label>
		</th>
		<td>Цвет
			<input type="text" class="color-field" id="wpa-color" name="attribute_wpa_color" value="<?php echo esc_attr( $color_value ); ?>" />
			<p class="description"><?php _e( 'Выберите цвет для этого атрибута.', 'web-alexb' ); ?></p>
		</td>
		<script>
            jQuery(function($){
                $('.color-field').wpColorPicker();
            });
		</script>
	</tr>
	<?php
}
add_action( 'pa_color_add_form_fields', 'add_color_field_to_attribute', 10, 1 );
add_action( 'pa_color_edit_form_fields', 'add_color_field_to_attribute', 10, 1 );

function save_color_field_for_attribute( $term_id, $tt_id, $taxonomy ) {
	if ( isset( $_POST['attribute_wpa_color'] ) ) {
		$color = sanitize_hex_color( $_POST['attribute_wpa_color'] );
		update_term_meta( $term_id, 'wpa_color', $color );
	}
}
add_action( 'created_pa_color', 'save_color_field_for_attribute', 10, 3 );
add_action( 'edited_pa_color', 'save_color_field_for_attribute', 10, 3 );

