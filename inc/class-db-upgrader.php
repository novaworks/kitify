<?php
/**
 * DB Upgrader class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Kitify_DB_Upgrader' ) ) {

	/**
	 * Define Kitify_DB_Upgrader class
	 */
	class Kitify_DB_Upgrader {

		/**
		 * Setting key
		 *
		 * @var string
		 */
		public $key = null;

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			$this->key = kitify_settings()->key;

			/**
			 * Plugin initialized on new Kitify_DB_Upgrader call.
			 * Please ensure, that it called only on admin context
			 */
			$this->init_upgrader();
		}

		/**
		 * Initialize upgrader module
		 *
		 * @return void
		 */
		public function init_upgrader() {

		    return;

			$db_updater_data = kitify()->module_loader->get_included_module_data( 'cx-db-updater.php' );

			new CX_DB_Updater(
				array(
					'path'      => $db_updater_data['path'],
					'url'       => $db_updater_data['url'],
					'slug'      => 'nova-element-kit',
					'version'   => '1.0.1',
					'callbacks' => array(
						'1.0.1' => array(
							array( $this, 'update_db_1_0_1' ),
						),
					),
					'labels'    => array(
						'start_update' => esc_html__( 'Start Update', 'kitify' ),
						'data_update'  => esc_html__( 'Data Update', 'kitify' ),
						'messages'     => array(
							'error'   => esc_html__( 'Module DB Updater init error in %s - version and slug is required arguments', 'kitify' ),
							'update'  => esc_html__( 'We need to update your database to the latest version.', 'kitify' ),
							'updated' => esc_html__( 'Update complete, thank you for updating to the latest version!', 'kitify' ),
						),
					),
				)
			);
		}

		/**
		 * Update db updater 1.0.1
		 *
		 * @return void
		 */
		public function update_db_1_0_1() {
			if ( class_exists( 'Elementor\Plugin' ) ) {
				kitify()->elementor()->files_manager->clear_cache();
			}
		}
	}

}
