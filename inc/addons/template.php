<?php

/**
 * Class: Kitify_Template
 * Name: Template
 * Slug: kitify-template
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}


/**
 * Template Widget
 */
class Kitify_Template extends Kitify_Base {

    public function get_name() {
        return 'kitify-template';
    }

    protected function get_widget_title() {
        return esc_html__( 'Template', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-template';
    }

    protected function register_controls() {

        $this->_start_controls_section(
            'section_template',
            array(
                'label' => esc_html__( 'Template', 'kitify' ),
            )
        );

        $this->add_control(
            'panel_template_id',
            array(
                'label'       => esc_html__( 'Choose Template', 'kitify' ),
                'label_block' => 'true',
                'type'        => 'kitify-query',
                'object_type' => \Elementor\TemplateLibrary\Source_Local::CPT,
                'filter_type' => 'by_id',
            )
        );

        $this->_end_controls_section();

    }

    protected function render() {

        $this->_context = 'render';

        $panel_settings = $this->get_settings();

        $template_id      = isset( $panel_settings['panel_template_id'] ) ? $panel_settings['panel_template_id'] : '0';

        if ( ! empty( $template_id ) ) {
            $content_html = kitify()->elementor()->frontend->get_builder_content_for_display( $template_id );
        } else {
            $content_html = $this->no_templates_message();
        }

        ?>
        <div class="kitify-template">
            <?php

            if ( ! empty( $template_id ) ) {
                $link = add_query_arg(
                    array(
                        'elementor' => '',
                    ),
                    get_permalink( $template_id )
                );

                if ( kitify_integration()->in_elementor() ) {
                    echo sprintf( '<div class="kitify-tabs__edit-cover" data-template-edit-link="%s"><i class="eicon-edit"></i><span>%s</span></div>', $link, esc_html__( 'Edit Template', 'kitify' ) );
                }
            }

            echo sprintf( '<div class="kitify-template-inner">%1$s</div>', $content_html );
            ?>
        </div>
        <?php
    }

    /**
     * Empty templates message description
     *
     * @return string
     */
    public function empty_templates_message() {
        return '<div id="elementor-widget-template-empty-templates">
				<div class="elementor-widget-template-empty-templates-icon"><i class="eicon-nerd"></i></div>
				<div class="elementor-widget-template-empty-templates-title">' . esc_html__( 'You Haven’t Saved Templates Yet.', 'kitify' ) . '</div>
				<div class="elementor-widget-template-empty-templates-footer">' . esc_html__( 'What is Library?', 'kitify' ) . ' <a class="elementor-widget-template-empty-templates-footer-url" href="https://go.elementor.com/docs-library/" target="_blank">' . esc_html__( 'Read our tutorial on using Library templates.', 'kitify' ) . '</a></div>
				</div>';
    }

    /**
     * No templates message
     *
     * @return string
     */
    public function no_templates_message() {
        $message = '<span>' . esc_html__( 'Template is not defined. ', 'kitify' ) . '</span>';

        $url = add_query_arg(
            array(
                'post_type'     => 'elementor_library',
                'action'        => 'elementor_new_post',
                '_wpnonce'      => wp_create_nonce( 'elementor_action_new_post' ),
                'template_type' => 'section',
            ),
            esc_url( admin_url( '/edit.php' ) )
        );

        $new_link = '<span>' . esc_html__( 'Select an existing template or create a ', 'kitify' ) . '</span><a class="kitify-tabs-new-template-link elementor-clickable" href="' . $url . '" target="_blank">' . esc_html__( 'new one', 'kitify' ) . '</a>' ;

        return sprintf(
            '<div class="kitify-tabs-no-template-message">%1$s%2$s</div>',
            $message,
            kitify_integration()->in_elementor() ? $new_link : ''
        );
    }

}
