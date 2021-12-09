<?php
/**
 * Login messages
 */

$message = wp_cache_get( 'kitify-login-messages' );

if ( ! $message ) {
	return;
}

?>
<div class="kitify-login-message"><?php
	echo $message;
?></div>