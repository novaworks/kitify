<?php
/**
 * Loop item template
 */

use Elementor\Icons_Manager;

$title_tag       = $this->_get_html( 'title_html_tag', '%s' );
$sub_title_tag   = $this->_get_html( 'sub_title_html_tag', '%s' );
$front_side_icon = $this->get_settings_for_display('front_side_icon');
$back_side_icon  = $this->get_settings_for_display('back_side_icon');
?>
<div class="kitify-animated-box <?php $this->_html( 'animation_effect', '%s' ); ?>">
	<div class="kitify-animated-box__front">
		<div class="kitify-animated-box__overlay"></div>
		<div class="kitify-animated-box__inner">
			<?php
                if ( !empty( $front_side_icon['value'] ) ) {
                    echo '<div class="kitify-animated-box__icon kitify-animated-box__icon--front"><div class="kitify-animated-box-icon-inner">';
                    Icons_Manager::render_icon( $front_side_icon, [ 'aria-hidden' => 'true' ] );
                    echo '</div></div>';
                }
			?>
			<div class="kitify-animated-box__content">
			<?php
				$this->_html( 'front_side_title', '<' . $title_tag . ' class="kitify-animated-box__title kitify-animated-box__title--front">%s</' . $title_tag . '>' );
				$this->_html( 'front_side_subtitle', '<' . $sub_title_tag . ' class="kitify-animated-box__subtitle kitify-animated-box__subtitle--front">%s</' . $sub_title_tag . '>' );
				$this->_html( 'front_side_description', '<p class="kitify-animated-box__description kitify-animated-box__description--front">%s</p>' );
			?>
			</div>
		</div>
	</div>
	<div class="kitify-animated-box__back">
		<div class="kitify-animated-box__overlay"></div>
		<div class="kitify-animated-box__inner">
			<?php
                if ( !empty( $back_side_icon['value'] ) ) {
                    echo '<div class="kitify-animated-box__icon kitify-animated-box__icon--back"><div class="kitify-animated-box-icon-inner">';
                    Icons_Manager::render_icon( $back_side_icon, [ 'aria-hidden' => 'true' ] );
                    echo '</div></div>';
                }
			?>
			<div class="kitify-animated-box__content">
			<?php
				$this->_html( 'back_side_title', '<' . $title_tag . ' class="kitify-animated-box__title kitify-animated-box__title--back">%s</' . $title_tag . '>' );
				$this->_html( 'back_side_subtitle', '<' . $sub_title_tag . ' class="kitify-animated-box__subtitle kitify-animated-box__subtitle--back">%s</' . $sub_title_tag . '>' );
				$this->_html( 'back_side_description', '<p class="kitify-animated-box__description kitify-animated-box__description--back">%s</p>' );
				$this->_glob_inc_if( 'action-button', array( 'back_side_button_link', 'back_side_button_text' ) );
			?>
			</div>
		</div>
	</div>
</div>