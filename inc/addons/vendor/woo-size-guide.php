<?php
/**
 * Class: Kitify_Woo_Size_Guide
 * Name: Size Guide
 * Slug: kitify-woo-size-guide
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Kitify_Woo_Size_Guide extends Kitify_Base {

  protected function enqueue_addon_resources(){
    if(!kitify_settings()->is_combine_js_css()) {
      wp_register_style( $this->get_name(), kitify()->plugin_url( 'assets/css/addons/woo-size-guide.css' ), [ 'kitify-base','kitify-canvas' ], kitify()->get_version() );

      $this->add_style_depends( $this->get_name() );
      $this->add_script_depends( 'kitify-base' );
    }
  }
  public function get_name() {
      return 'kitify-woo-size-guide';
  }
  public function get_categories() {
      return [ 'kitify-woo-product' ];
  }
  protected function get_widget_title() {
      return esc_html__( 'Size Guide', 'kitify' );
  }
  public function get_icon() {
      return 'kitify-icon-size-guide';
  }
  protected function register_controls() {
    $css_scheme = apply_filters(
        'kitify/woo-size-guide/css-scheme',
        array(
            'panel'    => '.kitify-woo-size-guide .kitify-offcanvas',
            'toggle'   => '.kitify-woo-size-guide button.button-toogle',
            'icon'     => '.kitify-woo-size-guide button.button-toogle .kitify-offcanvas__toggle-icon',
            'label'    => '.kitify-woo-size-guide button.button-toogle .kitify-offcanvas__toggle-label',
        )
    );
    $this->start_controls_section(
        'section_toogle',
        array(
            'label' => esc_html__( 'Toogle', 'kitify' ),
        )
    );
    $this->_add_advanced_icon_control(
        'sizeguide_toggle_icon',
        array(
            'label'       => esc_html__( 'Icon', 'kitify' ),
            'type'        => Controls_Manager::ICON,
            'label_block' => false,
            'skin'        => 'inline',
            'file'        => '',
        )
    );
    $this->add_control(
    'btn_text',
        array(
            'label'   => esc_html__( 'Label', 'kitify' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Size Guide',
        )
    );
    $this->end_controls_section();

    $this->start_controls_section(
        'section_panel',
        array(
            'label' => esc_html__( 'Panel Settings', 'kitify' ),
        )
    );
    $this->add_control(
        'panel_position',
        array(
            'label'       => esc_html__( 'Position', 'kitify' ),
            'type'        => Controls_Manager::SELECT,
            'default'     => 'right',
            'options' => array(
                'right' => esc_html__( 'Right', 'kitify' ),
                'left'  => esc_html__( 'Left', 'kitify' ),
            ),
        )
    );
    $this->_add_advanced_icon_control(
        'sizeguide_close_icon',
        array(
            'label'       => esc_html__( 'Close Icon', 'kitify' ),
            'type'        => Controls_Manager::ICON,
            'label_block' => false,
            'skin'        => 'inline',
            'file'        => '',
            'default'     => 'novaicon-e-remove',
            'fa5_default' => array(
                'value'   => 'novaicon-e-remove',
                'library' => 'novaicon',
            ),
        )
    );
    $this->end_controls_section();

    $this->start_controls_section(
        'section_content',
        array(
            'label' => esc_html__( 'Size Guide Content', 'kitify' ),
        )
    );
    $this->add_control(
    'content_title',
        array(
            'label'   => esc_html__( 'Panel Title', 'kitify' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Size Guidelines',
        )
    );
    $this->add_control(
        'size_template_id',
        array(
            'label'       => esc_html__( 'Choose Template', 'kitify' ),
            'label_block' => 'true',
            'type'        => 'kitify-query',
            'object_type' => \Elementor\TemplateLibrary\Source_Local::CPT,
            'filter_type' => 'by_id',
        )
    );
    $this->end_controls_section();

    $this->_start_controls_section(
        'section_panel_style',
        array(
            'label'      => esc_html__( 'Panel', 'kitify' ),
            'tab'        => Controls_Manager::TAB_STYLE,
            'show_label' => false,
        )
    );
    $this->_add_responsive_control(
        'panel_width',
        array(
            'label'      => esc_html__( 'Panel Width', 'kitify' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => array(
                'px', '%',
            ),
            'range'      => array(
                'px' => array(
                    'min' => 250,
                    'max' => 800,
                ),
                '%' => array(
                    'min' => 10,
                    'max' => 100,
                ),
            ),
            'selectors'  => array(
                '{{WRAPPER}} ' . $css_scheme['panel'] => '--panel-width: {{SIZE}}{{UNIT}};--panel-left-width: -{{SIZE}}{{UNIT}};',
            ),
        ),
        25
    );

    $this->_add_responsive_control(
        'panel_padding',
        array(
            'label'      => __( 'Padding', 'kitify' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%' ),
            'selectors'  => array(
                '{{WRAPPER}} ' . $css_scheme['panel'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        ),
        25
    );
    $this->_end_controls_section();

    $this->_start_controls_section(
        'section_panel_toggle_style',
        array(
            'label'      => esc_html__( 'Toggle', 'kitify' ),
            'tab'        => Controls_Manager::TAB_STYLE,
            'show_label' => false,
        )
    );
    $this->_start_controls_tabs( 'toggle_styles' );

    $this->_start_controls_tab(
        'toggle_tab_normal',
        array(
            'label' => esc_html__( 'Normal', 'kitify' ),
        )
    );

    $this->_add_group_control(
        Group_Control_Background::get_type(),
        array(
            'name'     => 'toggle_background',
            'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'],
        ),
        25
    );

    $this->_end_controls_tab();

    $this->_start_controls_tab(
        'toggle_tab_hover',
        array(
            'label' => esc_html__( 'Hover', 'kitify' ),
        )
    );

    $this->_add_group_control(
        Group_Control_Background::get_type(),
        array(
            'name'     => 'toggle_background_hover',
            'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'] . ':hover',
        ),
        25
    );

    $this->_end_controls_tab();

    $this->_end_controls_tabs();

    $this->_add_responsive_control(
        'toggle_padding',
        array(
            'label'      => esc_html__( 'Padding', 'kitify' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%' ),
            'selectors'  => array(
                '{{WRAPPER}} ' . $css_scheme['toggle'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
            'separator'  => 'before',
        ),
        25
    );

    $this->_add_responsive_control(
        'toggle_margin',
        array(
            'label'      => esc_html__( 'Margin', 'kitify' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%' ),
            'selectors'  => array(
                '{{WRAPPER}} ' . $css_scheme['toggle'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        ),
        25
    );

    $this->_add_group_control(
        Group_Control_Border::get_type(),
        array(
            'name'        => 'toggle_border',
            'label'       => esc_html__( 'Border', 'kitify' ),
            'placeholder' => '1px',
            'default'     => '1px',
            'selector'    => '{{WRAPPER}} ' . $css_scheme['toggle'],
        ),
        75
    );

    $this->_add_control(
        'toggle_border_radius',
        array(
            'label'      => esc_html__( 'Border Radius', 'kitify' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%' ),
            'selectors'  => array(
                '{{WRAPPER}} ' . $css_scheme['toggle'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        ),
        75
    );

    $this->_add_group_control(
        Group_Control_Box_Shadow::get_type(),
        array(
            'name'     => 'toggle_shadow',
            'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'],
        ),
        100
    );

    $this->_add_control(
        'toggle_icon_style_heading',
        array(
            'label'     => esc_html__( 'Icon Styles', 'kitify' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ),
        25
    );

    $this->_start_controls_tabs( 'toggle_icon_styles' );

    $this->_start_controls_tab(
        'toggle_icon_normal',
        array(
            'label' => esc_html__( 'Normal', 'kitify' ),
        )
    );

    $this->_add_group_control(
        \KitifyExtensions\Elementor\Controls\Group_Control_Box_Style::get_type(),
        array(
            'label'    => esc_html__( 'Toggle Icon', 'kitify' ),
            'name'     => 'toggle_icon_box',
            'selector' => '{{WRAPPER}} ' . $css_scheme['icon'],
        ),
        25
    );

    $this->_end_controls_tab();

    $this->_start_controls_tab(
        'toggle_icon_hover',
        array(
            'label' => esc_html__( 'Hover', 'kitify' ),
        )
    );

    $this->_add_group_control(
        \KitifyExtensions\Elementor\Controls\Group_Control_Box_Style::get_type(),
        array(
            'label'    => esc_html__( 'Toggle Icon', 'kitify' ),
            'name'     => 'toggle_icon_box_hover',
            'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'] . ':hover ' . $css_scheme['icon'],
        ),
        25
    );

    $this->_end_controls_tab();

    $this->_end_controls_tabs();

    $this->_add_control(
        'toggle_label_style_heading',
        array(
            'label'     => esc_html__( 'Label Styles', 'kitify' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ),
        25
    );

    $this->_start_controls_tabs( 'toggle_label_styles' );

    $this->_start_controls_tab(
        'toggle_label_normal',
        array(
            'label' => esc_html__( 'Normal', 'kitify' ),
        )
    );

    $this->_add_control(
        'toggle_control_label_color',
        array(
            'label'     => esc_html__( 'Label Color', 'kitify' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} ' . $css_scheme['label'] => 'color: {{VALUE}}',
            ),
        ),
        25
    );

    $this->_add_group_control(
        Group_Control_Typography::get_type(),
        array(
            'name'     => 'toggle_label_typography',
            'selector' => '{{WRAPPER}} '. $css_scheme['label'],
        ),
        50
    );

    $this->_end_controls_tab();

    $this->_start_controls_tab(
        'toggle_label_hover',
        array(
            'label' => esc_html__( 'Hover', 'kitify' ),
        )
    );

    $this->_add_control(
        'toggle_control_label_color_hover',
        array(
            'label'     => esc_html__( 'Label Color', 'kitify' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} ' . $css_scheme['toggle'] . ':hover ' . $css_scheme['label'] => 'color: {{VALUE}}',
            ),
        ),
        25
    );

    $this->_add_group_control(
        Group_Control_Typography::get_type(),
        array(
            'name'     => 'toggle_label_typography_hover',
            'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'] . ':hover ' . $css_scheme['label'],
        ),
        50
    );

    $this->_end_controls_tab();

    $this->_end_controls_tabs();
    $this->end_controls_section();
  }
  protected function render() {
    $this->_context = 'render';
    $settings = $this->get_settings();
    $button_text = $settings['btn_text'];
    $toggle_icon        = $this->_get_icon( 'sizeguide_toggle_icon', '<span class="kitify-offcanvas__icon icon-normal kitify-blocks-icon">%s</span>' );
    add_action('kitify/theme/canvas_panel', [ $this, 'add_panel' ] );
    ?>
    <div class="kitify-woo-size-guide">
    <button class="button-toogle" type="button" name="button" data-toggle="SizeGuide_<?php echo $this->get_id(); ?>" aria-expanded="false" aria-controls="SizeGuide_<?php echo $sizeguide_id; ?>">
    <?php
    if ( ! empty( $toggle_icon ) ) {
        echo sprintf( '<span class="kitify-offcanvas__toggle-icon">%1$s</span>', $toggle_icon);
    }
    if ( ! empty( $button_text ) ) {
        echo sprintf( '<span class="kitify-offcanvas__toggle-label">%1$s</span>', $button_text );
    }
    ?>
    </button>
    </div>
    <?php
  }
  public function add_panel() {
    $settings = $this->get_settings();
    $template_id      = isset( $settings['size_template_id'] ) ? $settings['size_template_id'] : '0';
    $position         = isset( $settings['panel_position'] ) ? $settings['panel_position'] : 'right';
    $content_title = $settings['content_title'];
    $toggle_close_icon = $this->_get_icon( 'sizeguide_close_icon', '<span class="kitify-offcanvas__icon kitify-blocks-icon">%s</span>' );
    ?>
    <?php if( 0 != $template_id ):?>
      <div class="kitify-offcanvas sizeguide-canvas site-canvas-menu off-canvas position-<?php echo $position; ?>" id="SizeGuide_<?php echo $this->get_id(); ?>" data-off-canvas data-transition="overlap">
        <h2 class="title"><?php echo esc_html( $content_title );?></h2>
        <div class="nova-offcanvas__content nova_box_ps">
          <?php echo kitify()->elementor()->frontend->get_builder_content_for_display( $template_id ); ?>
        </div>
        <button class="close-button" aria-label="Close menu" type="button" data-close>
          <?php echo $toggle_close_icon; ?>
        </button>
      </div>
    <?php endif; ?>
    <?php
  }
}
