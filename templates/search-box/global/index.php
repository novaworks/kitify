<?php
	$settings = $this->get_settings();
	$items = $this->get_settings_for_display('item_list');
	$box_classes = ['kitify-search-box'];
	$this->add_render_attribute('text_list_wrapper', 'class', $box_classes);
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
<form class="header-search__form" method="get" action="<?php echo esc_url( home_url( '/' ) ) ?>">
	<div class="header-search__container">
		<input type="text" name="s" class="header-search__field" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr( $settings['search_placeholder'] ); ?>" autocomplete="off">
		<div class="header-search__divider"></div>
		<?php if (  ! empty ( $settings['show_category'] ) ) : ?>
		<div class="header-search__categories-label">
			<span class="header-search__categories-text"><?php echo esc_html( $category_name ); ?></span>arrow-icon
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
				echo 'icon_close';
				echo '<div class="header-search__results search-results woocommerce"></div>';
			}
		?>
	</div>
	<input type="hidden" name="post_type" class="header-search__post-type" value="<?php echo isset( $_GET['post_type'] ) && ! empty( $_GET['post_type'] ) ? esc_attr( $_GET['post_type']) : esc_attr( $this->type() ); ?>">
</form>
</div>
