<?php
namespace KitifyThemeBuilder\Modules\AdminApp\Modules\SiteEditor\Data\Responses;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Lock_Error_Response extends \WP_Error {
	public function __construct( $user_id ) {
		$user = get_user_by( 'ID', $user_id );

		parent::__construct(
			'post_lock',
			sprintf(
				__( '%s is currently editing this template, please try again later', 'kitify' ),
				$user->display_name
			),
			[
				'status' => 403,
				'locked_by_user_id' => $user_id,
				'locked_by_user_name' => $user->display_name,
			]
		);
	}
}
