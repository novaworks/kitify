<?php
/**
 * Popup trigger
 */
?>
<div class="kitify-search__popup-trigger-container">
	<a id="js_header_search_modal" href="#headerSearchModal"><?php
		$this->_icon( 'search_popup_trigger_icon', '<span class="kitify-search__popup-trigger-icon kitify-blocks-icon">%s</span>' );
		$this->_html( 'search_submit_label', '<span class="kitify-search__trigger-label">%s</span>' );
	?></a>
</div>
