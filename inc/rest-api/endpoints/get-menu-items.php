<?php
namespace Kitify\Endpoints;

if ( ! defined( 'WPINC' ) ) {
    die;
}

// If this file is called directly, abort.
use Kitify\Template_Helper;

class Get_Menu_Items extends Base {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'get-menu-items';
	}

	/**
	 * Returns arguments config
	 *
	 * @return [type] [description]
	 */
	public function get_args() {
		return array(
			'menu_id' => array(
				'default'    => '',
				'required'   => false,
			),
            'sub_item_id' => array(
				'default'    => '',
				'required'   => false,
			),
			'dev' => array(
				'default'    => 'false',
				'required'   => false,
			)
		);
	}

	/**
	 * [callback description]
	 * @param  [type]   $request [description]
	 * @return void          [description]
	 */
	public function callback( $request ) {

		$args = $request->get_params();

		$menu_id = ! empty( $args['menu_id'] ) ? $args['menu_id'] : false;

		$sub_item_id = ! empty( $args['sub_item_id'] ) ? 'item-' . $args['sub_item_id'] : false;

		$dev = filter_var( $args['dev'], FILTER_VALIDATE_BOOLEAN ) ? true : false;

		$transient_key = md5( sprintf( 'kitify_menu_remote_items_data_%s_sub_%s', $menu_id, $sub_item_id ) );

		$items_data = get_transient( $transient_key );

		if ( ! empty( $items_data ) && ! $dev ) {
			return rest_ensure_response( $items_data );
		}

		$menu_data = $this->generate_menu_raw_data( $menu_id, $sub_item_id );

		$items_data = array(
			'data' => $menu_data,
		);

		set_transient( $transient_key, $items_data, 24 * HOUR_IN_SECONDS );

        Template_Helper::set_transient_key('menu', $menu_id, $transient_key);

		return rest_ensure_response( $items_data );
	}


    /**
     * [buildItemsTree description]
     * @param  array   &$items   [description]
     * @param  integer $parentId [description]
     * @return [type]            [description]
     */
    public function buildItemsTree( array &$items, $parentId = false ) {

        $branch = [];

        foreach ( $items as &$item ) {

            if ( $item['itemParent'] === $parentId ) {
                $children = $this->buildItemsTree( $items, $item['id'] );

                if ( $children ) {
                    $item['children'] = $children;
                }

                $branch[ $item['id'] ] = $item;

                unset( $item );
            }
        }

        return $branch;

    }

    /**
     * [generate_menu_raw_data description]
     * @param  string  $menu_slug [description]
     * @param  boolean $is_return [description]
     * @return [type]             [description]
     */
    public function generate_menu_raw_data( $menu_id = false, $sub_item_id = false ) {

        if ( ! $menu_id ) {
            return false;
        }

        $menu_items = $this->get_menu_items_object_data( $menu_id );

        $items = array();

        foreach ( $menu_items as $key => $item ) {

            $item_id = $item->ID;

            $menu_type = isset($item->menu_type) ? $item->menu_type : false;

            $items[] = array(
                'id'                  => 'item-' . $item_id,
                'name'                => $item->title,
                'description'         => $item->description,
                'url'                 => $item->url,
                'classes'             => $item->classes,
                'itemParent'          => '0' !== $item->menu_item_parent ? 'item-' . (int)$item->menu_item_parent : false,
                'itemId'              => $item_id,
                'elementorTemplateId' => $menu_type == 'wide' && empty($item->menu_item_parent) ? $item_id : false,
                'elementorContent'    => false,
                'open'                => false,
                'itemOnlyIcon'        => isset($item->only_icon) ? $item->only_icon : false,
                'itemIcon'            => isset($item->icon) ? $item->icon : false,
                'itemMenuType'        => $menu_type,
                'badgeText'           => isset($item->tip_label) ? $item->tip_label : false,
                'badgeColor'          => isset($item->tip_color) ? $item->tip_color : false,
                'badgeBgColor'        => isset($item->tip_background_color) ? $item->tip_background_color : false
            );
        }

        if ( ! empty( $items ) ) {
            $items = $this->buildItemsTree( $items, $sub_item_id );
        }

        $menu_data = array(
            'items' => $items,
        );

        return $menu_data;
    }

    /**
     * [get_menu_items_object_data description]
     * @param  boolean $menu_id [description]
     * @return [type]           [description]
     */
    public function get_menu_items_object_data( $menu_id = false ) {

        if ( ! $menu_id ) {
            return false;
        }

        $menu = wp_get_nav_menu_object( $menu_id );

        $menu_items = wp_get_nav_menu_items( $menu );

        if ( ! $menu_items ) {
            return false;
        }

        return $menu_items;
    }

}
