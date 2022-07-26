<?php

/**
 * Class: Kitify_Archive_Description
 * Name: Archive Description
 * Slug: kitify-archive-description
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}


/**
 * Post Excerpt Widget
 */
class Kitify_Archive_Description extends Kitify_Base {

    protected function enqueue_addon_resources(){
        $this->add_style_depends( 'kitify-base' );
    }

    public function get_name() {
        return 'kitify-archive-description';
    }

    protected function get_widget_title() {
        return esc_html__( 'Archive Description', 'kitify' );
    }

    public function get_icon() {
        return 'kitify-icon-cat-description';
    }

    public function get_categories() {
        return [ 'kitify-builder' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Archive Description', 'kitify' ),
            ]
        );

        $this->add_control(
            'html_tag',
            [
                'label' => __( 'HTML Tag', 'kitify' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p' => 'p',
                    'div' => 'div',
                    'span' => 'span',
                ],
                'default' => 'div',
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
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_to',
            [
                'label' => __( 'Link to', 'kitify' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __( 'None', 'kitify' ),
                    'home' => __( 'Home URL', 'kitify' ),
                    'custom' => __( 'Custom URL', 'kitify' ),
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __( 'Link', 'kitify' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'kitify' ),
                'condition' => [
                    'link_to' => 'custom',
                ],
                'default' => [
                    'url' => '',
                ],
                'show_label' => false,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Post Excerpt', 'kitify' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => __( 'Text Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .kitify-archive-description' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .kitify-archive-description a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .kitify-archive-description',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .kitify-archive-description',
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => __( 'Hover Animation', 'kitify' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {

        if ( is_search() ) {
            global $wp_query;

            if ( $wp_query->found_posts ) {
                $excerpt = sprintf(
                /* translators: %s: Number of search results. */
                    _n(
                        'We found %s result for your search.',
                        'We found %s results for your search.',
                        $wp_query->found_posts,
                        'kitify'
                    ),
                    number_format_i18n( $wp_query->found_posts )
                );
            } else {
                $excerpt = __( 'We could not find any results for your search.', 'kitify' );
            }
        }
        elseif ( is_archive() && ! have_posts() ) {
            $excerpt = __( 'Nothing Found', 'kitify' );
        }
        elseif ( ! is_home() ) {
            $excerpt = get_the_archive_description();
        }

        if ( empty( $excerpt ) ){
            return;
        }

        $settings = $this->get_settings();

        switch ( $settings['link_to'] ) {
            case 'custom' :
                if ( ! empty( $settings['link']['url'] ) ) {
                    $link = esc_url( $settings['link']['url'] );
                } else {
                    $link = false;
                }
                break;

            case 'post' :
                $link = esc_url( get_the_permalink() );
                break;

            case 'home' :
                $link = esc_url( get_home_url() );
                break;

            case 'none' :
            default:
                $link = false;
                break;
        }
        $target = $settings['link']['is_external'] ? 'target="_blank"' : '';

        $animation_class = ! empty( $settings['hover_animation'] ) ? 'elementor-animation-' . $settings['hover_animation'] : '';

        $html = sprintf( '<%1$s class="kitify-archive-description %2$s">', $settings['html_tag'], $animation_class );
        if ( $link ) {
            $html .= sprintf( '<a href="%1$s" %2$s>%3$s</a>', $link, $target, $excerpt );
        } else {
            $html .= $excerpt;
        }
        $html .= sprintf( '</%s>', $settings['html_tag'] );

        echo $html;
    }

    protected function content_template() {
        ?>
        <#
        var excerpt = "<?php echo htmlspecialchars( get_the_archive_description() ); ?>";

        var link_url;
        switch( settings.link_to ) {
        case 'custom':
        link_url = settings.link.url;
        break;
        case 'home':
        link_url = '<?php echo esc_url( get_home_url() ); ?>';
        break;
        case 'none':
        default:
        link_url = false;
        }
        var target = settings.link.is_external ? 'target="_blank"' : '';

        var animation_class = '';
        if ( '' !== settings.hover_animation ) {
        animation_class = 'elementor-animation-' + settings.hover_animation;
        }

        var html = '<' + settings.html_tag + ' class="kitify-archive-description ' + animation_class + '">';
        if ( link_url ) {
        html += '<a href="' + link_url + '" ' + target + '>' + excerpt + '</a>';
        } else {
        html += excerpt;
        }
        html += '</' + settings.html_tag + '>';

        print( html );
        #>
        <?php
    }

}
