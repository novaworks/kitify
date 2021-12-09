<?php
/**
 * Lost password link
 */

if ( ! isset( $settings['lost_password_link'] ) || ! filter_var( $settings['lost_password_link'], FILTER_VALIDATE_BOOLEAN ) ) {
	return;
}
?>

<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="kitify-login-lost-password-link"><?php
	echo esc_html( $settings['lost_password_link_text'] );
?></a>
