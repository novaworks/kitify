<?php
/**
 * Register form template
 */
$username = ! empty( $_POST['username'] ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : '';
$email    = ! empty( $_POST['email'] ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : '';
?>
<form method="post" class="kitify-register">

	<p class="kitify-register__row">
		<label class="kitify-register__label" for="kitify_username"><?php echo $settings['label_username']; ?></label>
		<input type="text" class="kitify-register__input" name="username" id="kitify_username" value="<?php echo $username; ?>" placeholder="<?php echo $settings['placeholder_username']; ?>"/>
	</p>

	<p class="kitify-register__row">
		<label  class="kitify-register__label"  for="kitify_email"><?php echo $settings['label_email']; ?></label>
		<input type="email" class="kitify-register__input" name="email" id="kitify_email" value="<?php echo $email; ?>" placeholder="<?php echo $settings['placeholder_email']; ?>"/>
	</p>

	<p class="kitify-register__row">
		<label  class="kitify-register__label" for="kitify_password"><?php echo $settings['label_pass']; ?></label>
		<input type="password" class="kitify-register__input" name="password" id="kitify_password" placeholder="<?php echo $settings['placeholder_pass']; ?>"/>
	</p>

	<?php if ( 'yes' === $settings['confirm_password'] ) : ?>

		<p class="kitify-register__row">
			<label  class="kitify-register__label" for="kitify_password_confirm"><?php echo $settings['label_pass_confirm']; ?></label>
			<input type="password" class="kitify-register__input" name="password-confirm" id="kitify_password_confirm" placeholder="<?php echo $settings['placeholder_pass_confirm']; ?>"/>
			<?php echo '<input type="hidden" name="kitify_confirm_password" value="true">'; ?>
		</p>

	<?php endif; ?>

	<?php do_action( 'kitify_register_form' ); ?>

	<p class="kitify-register__row kitify-register-submit">
		<?php
			wp_nonce_field( 'kitify-register', 'kitify-register-nonce' );
			printf( '<input type="hidden" name="kitify_redirect" value="%s">', $redirect_url );
		?>
		<button type="submit" class="kitify-register__submit button" name="register"><?php
			echo $settings['label_submit'];
		?></button>
	</p>

</form>
<?php
include $this->_get_global_template( 'messages' );
