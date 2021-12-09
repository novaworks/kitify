<?php
namespace Kitify_Dashboard;

use Kitify_Dashboard\Dashboard as Dashboard;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Data_Manager class
 */
class Data_Manager {

	/**
	 * [__construct description]
	 */
	public function __construct() {

	}

	/**
	 * [get_dashboard_config description]
	 * @return [type] [description]
	 */
	public function get_dashboard_config( $key = '' ) {

		$all_config_data = get_site_transient( 'kitify-dashboard-all-config-data' );
		//$all_config_data = false;

		if ( empty( $all_config_data ) ) {
			$all_config_data = $this->dashboard_config_remote_query( 'all-config' );

			if ( ! $all_config_data ) {
				return false;
			}

			// Expires in 1 day
			set_site_transient( 'kitify-dashboard-all-config-data', $all_config_data, DAY_IN_SECONDS );
		}

		if ( ! empty( $key ) && isset( $all_config_data[ $key ] ) ) {
			return $all_config_data[ $key ];
		}

		return $all_config_data;
	}

	/**
	 * [get_dashboard_page_config description]
	 * @param  boolean $page [description]
	 * @return [type]        [description]
	 */
	public function get_dashboard_page_config( $page = false, $subpage = false ) {

		$dashboard_config = $this->get_dashboard_config();

		if ( ! isset( $dashboard_config['pagesConfig'] ) ) {
			return false;
		}

		$page_config = false;

		if ( is_array( $dashboard_config['pagesConfig'] ) ) {

			if ( $subpage ) {

				foreach( $dashboard_config['pagesConfig'] as $page_data ) {

					if ( $subpage === $page_data['slug'] && $page === $page_data['parent-slug'] ) {
						$page_config = $page_data;

						break;
					}
				}
			}

			if ( ! $page_config ) {
				foreach( $dashboard_config['pagesConfig'] as $page_data ) {
					if ( $page === $page_data['slug'] ) {
						$page_config = $page_data;

						break;
					}
				}
			}
		}

		return $page_config;
	}

	/**
	 * [changelog_remote_query description]
	 * @param  [type] $slug [description]
	 * @return [type]       [description]
	 */
	public function dashboard_config_remote_query( $slug ) {

        return false;

		$response = wp_remote_get( sprintf( $this->kitify_dashboard_config_url, $slug ), array(
			'timeout' => 60,
		) );

		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) != '200' ) {
			return false;
		}

		$response = json_decode( $response['body'], true );

		return $response;
	}

	/**
	 * [changelog_remote_query description]
	 * @param  [type] $slug [description]
	 * @return [type]       [description]
	 */
	public function changelog_remote_query( $slug ) {

	    return false;

		$response = wp_remote_get( sprintf( $this->kitify_changelog_url, $slug ) );

		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) != '200' ) {
			return false;
		}

		$response = json_decode( $response['body'] );

		return $response;
	}

	/**
	 * [get_theme_info description]
	 * @return [type] [description]
	 */
	public function get_theme_info() {
		$style_parent_theme = wp_get_theme( get_template() );

		return apply_filters( 'kitify-dashboard/data-manager/theme-info', array(
			'name'       => $style_parent_theme->get('Name'),
			'theme'      => strtolower( preg_replace('/\s+/', '', $style_parent_theme->get('Name') ) ),
			'version'    => $style_parent_theme->get('Version'),
			'author'     => $style_parent_theme->get('Author'),
			'authorSlug' => strtolower( preg_replace('/\s+/', '', $style_parent_theme->get('Author') ) ),
		) );
	}


}
