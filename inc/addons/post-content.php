<?php

/**
 * Class: Kitify_Post_Content
 * Name: Post Content
 * Slug: kitify-post-content
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Post Content Widget
 */
class Kitify_Post_Content extends Kitify_Base {

    protected function enqueue_addon_resources(){
        $this->add_style_depends( 'kitify-base' );
    }

    public function get_name() {
        return 'kitify-post-content';
    }

    protected function get_html_wrapper_class()
    {
        return 'kitify elementor-widget-theme-post-content elementor-' . $this->get_name();
    }

    protected function get_widget_title() {
        return esc_html__( 'Full Content', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-post-excerpt';
    }

    public function get_categories() {
        return [ 'kitify-builder' ];
    }

    public function show_in_panel() {
        // By default don't show.
        return false;
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Style', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __( 'Alignment', 'kitify' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'kitify' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'kitify' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'kitify' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justified', 'kitify' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_TEXT,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {

        static $did_posts = [];
        $post = get_post();

        if(is_null($post)){
            return;
        }

        if ( post_password_required( $post->ID ) ) {
            echo get_the_password_form( $post->ID );
            return;
        }

        // Avoid recursion
        if ( isset( $did_posts[ $post->ID ] ) ) {
            return;
        }
        $did_posts[ $post->ID ] = true;

        if($post->post_type === 'envato_tk_templates'){
            echo '<div class="kitify-post-content elementor-post__content">' . esc_html__('Post Content', 'kitify') . '</div>';  // XSS ok.
            return;
        }

        // End avoid recursion

        $editor = kitify()->elementor()->editor;

        $is_edit_mode = $editor->is_edit_mode();

        if ( kitify()->elementor()->preview->is_preview_mode( $post->ID ) ) {
            $content = kitify()->elementor()->preview->builder_wrapper( '' ); // XSS ok
        }
        else {
            $document = kitify()->elementor()->documents->get( $post->ID );
            // On view theme document show it's preview content.
            if ( $document ) {
                $preview_type = $document->get_settings( 'preview_type' );
                $preview_id = $document->get_settings( 'preview_id' );

                if ( 0 === strpos( $preview_type, 'single' ) && ! empty( $preview_id ) ) {
                    $post = get_post( $preview_id );

                    if ( ! $post ) {
                        return;
                    }
                }
            }

            // Set edit mode as false, so don't render settings and etc. use the $is_edit_mode to indicate if we need the CSS inline
            $editor->set_edit_mode( false );

            // Print manually (and don't use `the_content()`) because it's within another `the_content` filter, and the Elementor filter has been removed to avoid recursion.
            $content = kitify()->elementor()->frontend->get_builder_content( $post->ID, true );

            if ( empty( $content ) ) {
                kitify()->elementor()->frontend->remove_content_filter();

                // Split to pages.
                setup_postdata( $post );

                /** This filter is documented in wp-inc/post-template.php */
                echo apply_filters( 'the_content', get_the_content() );

                wp_link_pages( [
                    'before' => '<div class="page-links elementor-page-links"><span class="page-links-title elementor-page-links-title">' . __( 'Pages:', 'kitify' ) . '</span>',
                    'after' => '</div>',
                    'link_before' => '<span>',
                    'link_after' => '</span>',
                    'pagelink' => '<span class="screen-reader-text">' . __( 'Page', 'kitify' ) . ' </span>%',
                    'separator' => '<span class="screen-reader-text">, </span>',
                ] );

                kitify()->elementor()->frontend->add_content_filter();

                return;
            } else {
                $content = apply_filters( 'the_content', $content );
            }
        } // End if().

        // Restore edit mode state
        kitify()->elementor()->editor->set_edit_mode( $is_edit_mode );

        echo '<div class="kitify-post-content elementor-post__content">' . balanceTags( $content, true ) . '</div>';  // XSS ok.
    }

    public function render_plain_content() {}

}
