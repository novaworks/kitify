<?php
/**
 * Search form template
 */
$settings = $this->get_settings();
?>
<form role="search" method="get" class="kitify-search__form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="kitify-search__label">
		<input type="search" class="kitify-search__field" placeholder="<?php echo esc_attr( $settings['search_placeholder'] ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	</label>
	<?php if ( 'true' ===  $settings['show_search_submit'] ) : ?>
	<button type="submit" class="kitify-search__submit"><?php
		$this->_icon( 'search_submit_icon', '<span class="kitify-search__submit-icon kitify-blocks-icon">%s</span>' );
		$this->_html( 'search_submit_label', '<div class="kitify-search__submit-label">%s</div>' );
	?></button>
	<?php endif; ?>
	<?php if ( isset( $settings['is_product_search'] ) && 'true' === $settings['is_product_search'] ) : ?>
		<input type="hidden" name="post_type" value="product" />
	<?php endif; ?>
</form>