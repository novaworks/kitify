<?php

/**
 * Class: Kitify_Post_Comment
 * Name: Post Comment
 * Slug: kitify-post-comment
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}


/**
 * Post Comment Widget
 */
class Kitify_Post_Comment extends Kitify_Base {

    protected function enqueue_addon_resources(){
        $this->add_style_depends( 'kitify-base' );
    }

    public function get_name() {
        return 'kitify-post-comment';
    }

    protected function get_widget_title() {
        return esc_html__( 'Post Comment', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-comments';
    }

    public function get_categories() {
        return [ 'kitify-builder' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Post Comments', 'kitify' ),
            ]
        );

        $this->add_control(
            'info',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( 'This widget displays the default Comments Template included in the current Theme.', 'kitify' ) .
                    '<br><br>' .
                    __( 'No custom styling can be applied as each theme uses it\'s own CSS classes and IDs.', 'kitify' ),
                'content_classes' => 'elementor-descriptor',
            ]
        );

        $this->end_controls_section();
    }

    public function render() {

        if ( ! comments_open() && ( kitify()->elementor()->preview->is_preview_mode() || kitify()->elementor()->editor->is_edit_mode() ) ) :
            ?>
            <div class="elementor-alert elementor-alert-danger" role="alert">
				<span class="elementor-alert-title">
					<?php esc_html_e( 'Comments are closed.', 'kitify' ); ?>
				</span>
                <span class="elementor-alert-description">
					<?php esc_html_e( 'Switch on comments from either the discussion box on the WordPress post edit screen or from the WordPress discussion settings.', 'kitify' ); ?>
				</span>
            </div>
        <?php
        else :
            comments_template();
        endif;

    }

}
