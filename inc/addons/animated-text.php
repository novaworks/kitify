<?php

/**
 * Class: Kitify_Animated_Text
 * Name: Animated Text
 * Slug: kitify-animated-text
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Kitify_Animated_Text Widget
 */
class Kitify_Animated_Text extends Kitify_Base {

    protected function enqueue_addon_resources(){
        wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/animated-text.css'), ['kitify-base'], kitify()->get_version());
        wp_register_script( 'kitify-anime-js', kitify()->plugin_url('assets/js/lib/anime.min.js'), [], kitify()->get_version(), true);
        wp_register_script( $this->get_name(), kitify()->plugin_url('assets/js/addons/animated-text.js'), ['kitify-anime-js'], kitify()->get_version(), true);

        $this->add_style_depends( $this->get_name() );
        $this->add_script_depends( $this->get_name() );
    }

    public function get_name() {
        return 'kitify-animated-text';
    }

    protected function get_widget_title() {
        return esc_html__( 'Animated Text', 'kitify');
    }

    public function get_icon() {
        return 'kitify-icon-animated-text';
    }

    protected function register_controls() {

        $css_scheme = apply_filters(
            'kitify/animated-text/css-scheme',
            array(
                'animated_text_instance' => '.kitify-animated-text',
                'before_text'            => '.kitify-animated-text__before-text',
                'animated_text'          => '.kitify-animated-text__animated-text',
                'animated_text_item'     => '.kitify-animated-text__animated-text-item',
                'after_text'             => '.kitify-animated-text__after-text',
            )
        );

        $this->_start_controls_section(
            'section_general',
            array(
                'label' => esc_html__( 'Content', 'kitify' ),
            )
        );

        $this->_add_control(
            'before_text_content',
            array(
                'label'   => esc_html__( 'Before Text', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Let us', 'kitify' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_text',
            array(
                'label'   => esc_html__( 'Text', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Create', 'kitify' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->_add_control(
            'animated_text_list',
            array(
                'type'    => Controls_Manager::REPEATER,
                'label'   => esc_html__( 'Animated Text', 'kitify' ),
                'fields'  => $repeater->get_controls(),
                'default' => array(
                    array(
                        'item_text' => esc_html__( 'Create', 'kitify' ),
                    ),
                    array(
                        'item_text' => esc_html__( 'Animate', 'kitify' ),
                    ),
                ),
                'title_field' => '{{{ item_text }}}',
            )
        );

        $this->_add_control(
            'after_text_content',
            array(
                'label'   => esc_html__( 'After Text', 'kitify' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'your text', 'kitify' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Settings', 'kitify' ),
            )
        );

        $this->_add_control(
            'animation_effect',
            array(
                'label'   => esc_html__( 'Animation Effect', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'fx1',
                'options' => array(
                    'fx1'  => esc_html__( 'Joke', 'kitify' ),
                    'fx2'  => esc_html__( 'Kinnect', 'kitify' ),
                    'fx3'  => esc_html__( 'Circus', 'kitify' ),
                    'fx4'  => esc_html__( 'Rotation fall', 'kitify' ),
                    'fx5'  => esc_html__( 'Simple Fall', 'kitify' ),
                    'fx6'  => esc_html__( 'Rotation', 'kitify' ),
                    'fx7'  => esc_html__( 'Anime', 'kitify' ),
                    'fx8'  => esc_html__( 'Label', 'kitify' ),
                    'fx9'  => esc_html__( 'Croco', 'kitify' ),
                    'fx10' => esc_html__( 'Scaling', 'kitify' ),
                    'fx11' => esc_html__( 'Fun', 'kitify' ),
                    'fx12' => esc_html__( 'Typing', 'kitify' ),
                ),
            )
        );

        $this->_add_control(
            'animation_delay',
            array(
                'label'   => esc_html__( 'Animation Speed', 'kitify' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 3000,
                'min'     => 500,
                'step'    => 100,
            )
        );

        $this->_add_control(
            'animation_start',
            array(
                'label'   => esc_html__( 'Animation Start After', 'kitify' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 0,
                'min'     => 0,
                'step'    => 100
            )
        );

        $this->_add_control(
            'split_type',
            array(
                'label'   => esc_html__( 'Split Type', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'symbol',
                'options' => array(
                    'symbol' => esc_html__( 'Symbols', 'kitify' ),
                    'word'   => esc_html__( 'Words', 'kitify' ),
                ),
            )
        );

        $this->_add_control(
            'animated_text_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'kitify' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'kitify' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'kitify' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'kitify' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_text_instance'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_general_text_style',
            array(
                'label'      => esc_html__( 'General Text', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_control(
            'text_color',
            array(
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_text_instance'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'text_bg_color',
            array(
                'label' => esc_html__( 'Background color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_text_instance'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'text_typography',
                'label'    => esc_html__( 'Typography', 'kitify' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_text_instance'],
            )
        );

        $this->_add_responsive_control(
            'text_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['animated_text_instance'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_before_text_style',
            array(
                'label'      => esc_html__( 'Before Text', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_control(
            'before_text_color',
            array(
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['before_text'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'before_text_bg_color',
            array(
                'label' => esc_html__( 'Background color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['before_text'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'before_text_typography',
                'label'    => esc_html__( 'Typography', 'kitify' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['before_text'],
            )
        );

        $this->_add_responsive_control(
            'before_text_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['before_text'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_animated_text_style',
            array(
                'label'      => esc_html__( 'Animated Text', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_control(
            'animated_text_color',
            array(
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_text'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'animated_text_bg_color',
            array(
                'label' => esc_html__( 'Background color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_text'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'animated_text_cursor_color',
            array(
                'label' => esc_html__( 'Cursor Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['animated_text_item'] . ':after' => 'background-color: {{VALUE}}',
                ),
                'condition' => array(
                    'animation_effect' => 'fx12',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'animated_text_typography',
                'label'    => esc_html__( 'Typography', 'kitify' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['animated_text'],
            )
        );

        $this->_add_responsive_control(
            'animated_text_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['animated_text'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_after_text_style',
            array(
                'label'      => esc_html__( 'After Text', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_control(
            'after_text_color',
            array(
                'label' => esc_html__( 'Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['after_text'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'after_text_bg_color',
            array(
                'label' => esc_html__( 'Background color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['after_text'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'after_text_typography',
                'label'    => esc_html__( 'Typography', 'kitify' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['after_text'],
            )
        );

        $this->_add_responsive_control(
            'after_text_padding',
            array(
                'label'      => esc_html__( 'Padding', 'kitify' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['after_text'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_end_controls_section();

    }

    /**
     * Generate spenned html string
     *
     * @param  string $str Base text
     * @return string
     */
    public function str_to_spanned_html( $base_string, $split_type = 'symbol' ) {

        $spanned_array = array();

        $base_words = explode( ' ', $base_string );
        if ( 'symbol' === $split_type ) {
            foreach ( $base_words as $symbol ) {
                $symbols_array = $this->_string_split( $symbol );
                $tmp = [];
                foreach ($symbols_array as $item){
                    $tmp[] = sprintf( '<span class="kitify-animated-span">%s</span>', $item );
                }
                $spanned_array[] = '<span>'.join('', $tmp).'</span>';
            }

        }
        else {
            foreach ( $base_words as $symbol ) {
                $spanned_array[] = sprintf( '<span class="kitify-animated-span">%s</span>', $symbol );
            }
        }
        return join( '<span class="kitify-animated-span">&nbsp;</span>', $spanned_array );
    }

    /**
     * Split string
     *
     * @param  [type] $string [description]
     * @return [type]         [description]
     */
    public function _string_split( $string ) {

        $strlen = mb_strlen( $string );
        $result = array();

        while ( $strlen ) {

            $result[] = mb_substr( $string, 0, 1, "UTF-8" );
            $string   = mb_substr( $string, 1, $strlen, "UTF-8" );
            $strlen   = mb_strlen( $string );

        }

        return $result;
    }

    /**
     * Generate setting json
     *
     * @return string
     */
    public function generate_setting_json() {
        $module_settings = $this->get_settings_for_display();

        $settings = array(
            'effect' => $module_settings['animation_effect'],
            'start'  => isset($module_settings['animation_start']) ? $module_settings['animation_start'] : 0,
            'delay'  => $module_settings['animation_delay'],
        );

        return sprintf( 'data-settings="%1$s"', esc_attr(json_encode( $settings )) );
    }

    protected function render() {

        $this->_context = 'render';

        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();
    }

}