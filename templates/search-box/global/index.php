<?php
	$settings = $this->get_settings();
	$items = $this->get_settings_for_display('item_list');
	$data_settings = array(
		'ajax_url'             				=> class_exists( 'WC_AJAX' ) ? \WC_AJAX::get_endpoint( '%%endpoint%%' ) : '',
		'header_search_type' 				=> $settings['search_for'],
		'header_ajax_search'   				=> intval( $settings['ajax_search'] ),
		'header_search_number' 				=> $settings['ajax_search_count'],
		'post_type' 	     				=> $this->type(),
	);
	$box_classes = ['kitify-search-box','header-search--inside'];
	$this->add_render_attribute('text_list_wrapper', 'class', $box_classes);
	$this->add_render_attribute('text_list_wrapper', 'data-settings', json_encode( $data_settings ));
	$type = $settings['search_for'] == 'product' || $settings['search_for'] == 'adaptive' ? 'product_cat' : 'category_name';
	$show_categories = $settings['all_cat_text'] ;
	$taxonomy = isset( $args['taxonomy'] ) ? $args['taxonomy'] : 'category';
	$term_slug = 0;
	if ( isset( $_GET['product_cat'] ) ) {
		$term_slug = $_GET['product_cat'];
	}
	
	if ( isset( $_GET['category_name'] ) ) {
		$term_slug = $_GET['category_name'];
	}
	$term_name = get_term_by( 'slug', $term_slug, $taxonomy ) ? get_term_by( 'slug', $term_slug, $taxonomy )->name : '';
	$category_name = ! empty( $term_name ) ? $term_name : $show_categories;
 ?>
<div <?php echo $this->get_render_attribute_string('text_list_wrapper') ?>>
<form class="kitify-search-box__form kitify-search-box--form" method="get" action="<?php echo esc_url( home_url( '/' ) ) ?>">
	<div class="kitify-search-box__container">
		<input type="text" name="s" class="kitify-search-box__field" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr( $settings['search_placeholder'] ); ?>" autocomplete="off">
		<?php if (  ! empty ( $settings['show_category'] ) ) : ?>
		<div class="kitify-search-box__divider"></div>
		<div class="kitify-search-box__categories-label">
			<span class="kitify-search-box__categories-text"><?php echo esc_html( $category_name ); ?></span>
			<?php kitify_helper()->svg_icon('arrow-down'); ?>
		</div>
		<?php if( $settings['search_for'] ) : ?>
			<input class="category-name" type="hidden" name="<?php echo esc_attr( $type ); ?>" value="<?php echo isset( $term_slug ) ? $term_slug : 0; ?>">
		<?php endif; ?>
		<?php endif; ?>
		<?php
		$this->trendings();
		?>
		<?php if ( ! empty ( $settings['show_category'] ) ) :
			if ( ( $settings['search_for'] && $settings['search_for'] !== 'adaptive' ) || ( $settings['search_for'] == 'adaptive' && $this->type() == 'product' ) ) {
				$this->categories_items( $show_categories );
			}
		endif; ?>
		<?php
			if ( intval( $settings['ajax_search'] ) ) {
				echo \Kitify_SVG_Icons::get_svg( 'close', 'ui', 'class=close-search-results' );
				echo '<div class="kitify-search-box__results search-results woocommerce"></div>';
			}
		?>
	</div>
	<button class="kitify-search-box__button kitify-button" type="submit" aria-label="<?php esc_attr__('Search Button', 'kitify') ?>">
	<?php
		if ( $settings['custom_button_icon'] ) {
			$this->_icon( 'search_submit_icon', '<span class="kitify-button__icon">%s</span>' );
		} else {
			echo '<span class="kitify-button__icon">' . \Kitify_SVG_Icons::get_svg( 'search' ) . '</span>';
		}
	?>
</button>
	<input type="hidden" name="post_type" class="kitify-search-box__post-type" value="<?php echo isset( $_GET['post_type'] ) && ! empty( $_GET['post_type'] ) ? esc_attr( $_GET['post_type']) : esc_attr( $this->type() ); ?>">
</form>
</div>
