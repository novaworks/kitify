<?php
/**
 * Loop item template
 */
?>
<figure class="kitify-banner kitify-effect-<?php $this->_html( 'animation_effect', '%s' ); ?>"><?php
	$target = $this->_get_html( 'banner_link_target', ' target="%s"' );
	$rel = $this->_get_html( 'banner_link_rel', ' rel="%s"' );
	$href = $this->_get_html( 'banner_link', ' href="%s"' );

	$this->_html( 'banner_link', '<a href="%s" class="kitify-banner__link"' . $target . $rel . '>' );
		echo '<div class="kitify-banner__overlay"></div>';
		echo $this->_get_banner_image();
		echo '<figcaption class="kitify-banner__content">';
			echo '<div class="kitify-banner__content-wrap">';
				$title_tag = $this->_get_html( 'banner_title_html_tag', '%s' );
				$title_tag = kitify_helper()->validate_html_tag( $title_tag );

				$this->_html( 'banner_title', '<' . $title_tag  . ' class="kitify-banner__title">%s</' . $title_tag  . '>' );
				$this->_html( 'banner_text', '<div class="kitify-banner__text">%s</div>' );
				$this->_html( 'btn_text', '<a'.$href.' class="elementor-button elementor-size-md"' . $target . $rel . '>%s</a>' );
			echo '</div>';
		echo '</figcaption>';
	$this->_html( 'banner_link', '</a>' );
?></figure>
