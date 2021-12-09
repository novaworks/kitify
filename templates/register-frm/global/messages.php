<?php
/**
 * Registration messages
 */

$message = wp_cache_get( 'kitify-register-messages' );

if ( ! $message ) {
	return;
}

?>
<div class="kitify-register-message"><?php
	echo $message;
?></div>