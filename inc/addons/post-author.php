<?php

/**
 * Class: Kitify_Post_Author
 * Name: Post Author
 * Slug: kitify-post-author
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}


/**
 * Post Author Widget
 */
class Kitify_Post_Author extends Kitify_Base {

    protected function enqueue_addon_resources(){
        $this->add_style_depends( 'kitify-base' );
    }

    public function get_name() {
        return 'kitify-post-author';
    }

    protected function get_widget_title() {
        return esc_html__( 'Post Author', 'kitify' );
    }

    public function get_icon() {
  		return 'kitify-icon-author-box';
  	}

    public function get_categories() {
        return [ 'kitify-builder' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Post Author', 'kitify' ),
            ]
        );

        $this->add_control(
            'author',
            [
                'label' => __( 'Author', 'kitify' ),
                'type' => Controls_Manager::SELECT,
                'options' => $this->user_fields_labels(),
                'default' => 'display_name',
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
                'default' => 'p',
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
                    'post' => esc_html__('Post URL', 'kitify'),
                    'author' => __( 'Author URL', 'kitify' ),
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
                'label' => esc_html__('Post Author', 'kitify'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => __( 'Text Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .kitify-post-author' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .kitify-post-author a' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'author!' => 'image',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .kitify-post-author',
                'condition' => [
                    'author!' => 'image',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .kitify-post-author',
                'condition' => [
                    'author!' => 'image',
                ],
            ]
        );

        $this->add_responsive_control(
            'space',
            [
                'label' => __( 'Size (%)', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-post-author img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'author' => 'image',
                ],
            ]
        );

        $this->add_responsive_control(
            'opacity',
            [
                'label' => __( 'Opacity (%)', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-post-author img' => 'opacity: {{SIZE}};',
                ],
                'condition' => [
                    'author' => 'image',
                ],
            ]
        );

        $this->add_control(
            'angle',
            [
                'label' => __( 'Angle (deg)', 'kitify' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'default' => [
                    'unit' => 'deg',
                    'size' => 0,
                ],
                'range' => [
                    'deg' => [
                        'max' => 360,
                        'min' => -360,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-post-author img' => '-webkit-transform: rotate({{SIZE}}deg); -moz-transform: rotate({{SIZE}}deg); -ms-transform: rotate({{SIZE}}deg); -o-transform: rotate({{SIZE}}deg); transform: rotate({{SIZE}}deg);',
                ],
                'condition' => [
                    'author' => 'image',
                ],
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => __( 'Hover Animation', 'kitify' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => __( 'Image Border', 'kitify' ),
                'selector' => '{{WRAPPER}} .kitify-post-author img',
                'condition' => [
                    'author' => 'image',
                ],
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'kitify' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .kitify-post-author img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'author' => 'image',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .kitify-post-author img',
                'condition' => [
                    'author' => 'image',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings();

        if ( empty( $settings['author'] ) )
            return;

        $author = $this->user_data( $settings['author'] );

        switch ( $settings['link_to'] ) {
            case 'custom' :
                if ( ! empty( $settings['link']['url'] ) ) {
                    $link = esc_url( $settings['link']['url'] );
                } else {
                    $link = false;
                }
                break;

            case 'author' :
                $link = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
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

        $html = sprintf( '<%1$s class="kitify-post-author %2$s">', $settings['html_tag'], $animation_class );
        if ( $link ) {
            $html .= sprintf( '<a href="%1$s" %2$s>%3$s</a>', $link, $target, $author );
        } else {
            $html .= $author;
        }
        $html .= sprintf( '</%s>', $settings['html_tag'] );

        echo $html;
    }

    protected function content_template() {
        ?>
        <#
        var author_data = [];
        <?php
        foreach ( $this->user_data() as $key => $value ) {
            printf( 'author_data[ "%1$s" ] = "%2$s";', $key, $value );
        }
        ?>
        var author = author_data[ settings.author ];

        var link_url;
        switch( settings.link_to ) {
        case 'custom':
        link_url = settings.link.url;
        break;
        case 'author':
        link_url = '<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>';
        break;
        case 'post':
        link_url = '<?php echo esc_url( get_the_permalink() ); ?>';
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

        var html = '<' + settings.html_tag + ' class="kitify-post-author ' + animation_class + '">';
        if ( link_url ) {
        html += '<a href="' + link_url + '" ' + target + '>' + author + '</a>';
        } else {
        html += author;
        }
        html += '</' + settings.html_tag + '>';

        print( html );
        #>
        <?php
    }

    protected function user_fields_labels() {

        $fields = [
            'first_name'   => __( 'First Name', 'kitify' ),
            'last_name'    => __( 'Last Name', 'kitify' ),
            'first_last'   => __( 'First Name + Last Name', 'kitify' ),
            'last_first'   => __( 'Last Name + First Name', 'kitify' ),
            'nickname'     => __( 'Nick Name', 'kitify' ),
            'display_name' => __( 'Display Name', 'kitify' ),
            'user_login'   => __( 'User Name', 'kitify' ),
            'description'  => __( 'User Bio', 'kitify' ),
            'image'        => __( 'User Image', 'kitify' ),
        ];

        return $fields;

    }

    protected function user_data( $selected = '' ) {

        global $post;

        $author_id = $post->post_author;

        $fields = [
            'first_name'   => get_the_author_meta( 'first_name', $author_id ),
            'last_name'    => get_the_author_meta( 'last_name', $author_id ),
            'first_last'   => sprintf( '%s %s', get_the_author_meta( 'first_name', $author_id ), get_the_author_meta( 'last_name', $author_id ) ),
            'last_first'   => sprintf( '%s %s', get_the_author_meta( 'last_name', $author_id ), get_the_author_meta( 'first_name', $author_id ) ),
            'nickname'     => get_the_author_meta( 'nickname', $author_id ),
            'display_name' => get_the_author_meta( 'display_name', $author_id ),
            'user_login'   => get_the_author_meta( 'user_login', $author_id ),
            'description'  => get_the_author_meta( 'description', $author_id ),
            'image'        => get_avatar( get_the_author_meta( 'email', $author_id ), 256 ),
        ];

        if ( empty( $selected ) ) {
            // Return the entire array
            return $fields;
        } else {
            // Return only the selected field
            return $fields[ $selected ];
        }

    }

}
