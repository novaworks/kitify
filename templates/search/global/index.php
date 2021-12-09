<?php
/**
 * Main template
 */
$settings = $this->get_settings();
?>
<div class="kitify-search"><?php
	if ( 'true' === $settings['show_search_in_popup'] ) {
		include $this->_get_global_template( 'popup' );
	} else {
		include $this->_get_global_template( 'form' );
	}
?></div>