<?php

/**
 * Class: Kitify_Information_List
 * Name: Information List
 * Slug: kitify-information-list
 */
 namespace Elementor;
 use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
 use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

 if (!defined('WPINC')) {
     die;
 }

 class Kitify_Information_List extends Kitify_Base {

   protected function enqueue_addon_resources(){
     if(!kitify_settings()->is_combine_js_css()) {
       wp_register_style( $this->get_name(), kitify()->plugin_url( 'assets/css/addons/information-list.css' ), [ 'kitify-base','kitify-canvas' ], kitify()->get_version() );

       $this->add_style_depends( $this->get_name() );
       $this->add_script_depends( 'kitify-base' );
     }
   }
   public function get_name() {
       return 'kitify-information-list';
   }
   public function get_categories() {
       return [ 'kitify-woo-product' ];
   }
   protected function get_widget_title() {
       return esc_html__( 'Information List', 'kitify' );
   }
   public function get_icon() {
       return 'kitify-icon-infor-list';
   }
   protected function register_controls() {
     $repeater = new Repeater();
     $this->_start_controls_section(
         'section_items_data',
         array(
             'label' => esc_html__( 'Items', 'kitify' ),
         )
     );
     $this->add_control(
       'view',
       [
         'label' => esc_html__( 'Layout', 'kitify' ),
         'type' => Controls_Manager::CHOOSE,
         'default' => 'traditional',
         'options' => [
           'traditional' => [
             'title' => esc_html__( 'Default', 'kitify' ),
             'icon' => 'eicon-editor-list-ul',
           ],
           'inline' => [
             'title' => esc_html__( 'Inline', 'kitify' ),
             'icon' => 'eicon-ellipsis-h',
           ],
         ],
         'render_type' => 'template',
         'classes' => 'elementor-control-start-end',
         'style_transfer' => true,
         'prefix_class' => 'elementor-icon-list--layout-',
       ]
     );
     $repeater->add_control(
         'item_icon',
         array(
             'label'       => esc_html__( 'Icon', 'kitify' ),
             'type' => Controls_Manager::ICONS,
             'fa4compatibility' => 'icon'
         )
     );
     $repeater->add_control(
         'item_title',
         array(
             'label'   => esc_html__( 'Title', 'kitify' ),
             'type'    => Controls_Manager::TEXT,
             'dynamic' => [
                 'active' => true,
             ],
         )
     );
     $repeater->add_control(
         'content_type',
         [
             'label'       => esc_html__( 'Content Type', 'kitify' ),
             'type'        => Controls_Manager::SELECT,
             'default'     => 'editor',
             'options'     => [
                 'template' => esc_html__( 'Template', 'kitify' ),
                 'editor'   => esc_html__( 'Editor', 'kitify' ),
             ],
             'label_block' => 'true',
         ]
     );
     $repeater->add_control(
         'item_template_id',
         [
             'label'       => esc_html__( 'Choose Template', 'kitify' ),
             'label_block' => 'true',
             'type'        => 'kitify-query',
             'object_type' => \Elementor\TemplateLibrary\Source_Local::CPT,
             'filter_type' => 'by_id',
             'condition'   => array(
                 'content_type' => 'template',
             ),
         ]
     );
     $repeater->add_control(
         'item_editor_content',
         [
             'label'      => __( 'Content', 'kitify' ),
             'type'       => Controls_Manager::WYSIWYG,
             'default'    => __( 'Tab Item Content', 'kitify' ),
             'dynamic' => [
                 'active' => true,
             ],
             'condition'   => [
                 'content_type' => 'editor',
             ]
         ]
     );
     $this->_add_control(
         'items',
         array(
             'type'        => Controls_Manager::REPEATER,
             'fields'      => $repeater->get_controls(),
             'default'     => array(
                 array(
                     'item_title'  => esc_html__( 'Information #1', 'kitify' ),
                 ),
                 array(
                     'item_title'  => esc_html__( 'Information #2', 'kitify' ),
                 ),
                 array(
                     'item_title'  => esc_html__( 'Information #3', 'kitify' ),
                 ),
             ),
             'title_field' => '{{{ item_title }}}',
         )
     );
     $this->_end_controls_section();

     $this->start_controls_section(
       'section_icon_list',
       [
         'label' => esc_html__( 'List', 'kitify' ),
         'tab' => Controls_Manager::TAB_STYLE,
       ]
     );
     $this->add_responsive_control(
       'space_between',
       [
         'label' => esc_html__( 'Space Between', 'kitify' ),
         'type' => Controls_Manager::SLIDER,
         'range' => [
           'px' => [
             'max' => 50,
           ],
         ],
         'selectors' => [
           '{{WRAPPER}} .kitify-information-list__items:not(.elementor-inline-items) .kitify-information-list__item:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
           '{{WRAPPER}} .kitify-information-list__items:not(.elementor-inline-items) .kitify-information-list__item:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
           '{{WRAPPER}} .kitify-information-list__items.elementor-inline-items .kitify-information-list__item' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2)',
           '{{WRAPPER}} .kitify-information-list__items.elementor-inline-items' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
           'body.rtl {{WRAPPER}} .kitify-information-list__items.elementor-inline-items .kitify-information-list__item:after' => 'left: calc(-{{SIZE}}{{UNIT}}/2)',
           'body:not(.rtl) {{WRAPPER}} .kitify-information-list__items.elementor-inline-items .kitify-information-list__item:after' => 'right: calc(-{{SIZE}}{{UNIT}}/2)',
         ],
       ]
     );
     $this->add_responsive_control(
       'icon_align',
       [
         'label' => esc_html__( 'Alignment', 'kitify' ),
         'type' => Controls_Manager::CHOOSE,
         'options' => [
           'left' => [
             'title' => esc_html__( 'Left', 'kitify' ),
             'icon' => 'eicon-h-align-left',
           ],
           'center' => [
             'title' => esc_html__( 'Center', 'kitify' ),
             'icon' => 'eicon-h-align-center',
           ],
           'right' => [
             'title' => esc_html__( 'Right', 'kitify' ),
             'icon' => 'eicon-h-align-right',
           ],
         ],
         'prefix_class' => 'elementor%s-align-',
       ]
     );
     $this->add_control(
       'divider',
       [
         'label' => esc_html__( 'Divider', 'kitify' ),
         'type' => Controls_Manager::SWITCHER,
         'label_off' => esc_html__( 'Off', 'kitify' ),
         'label_on' => esc_html__( 'On', 'kitify' ),
         'selectors' => [
           '{{WRAPPER}} .kitify-information-list__item:not(:last-child):after' => 'content: ""',
         ],
         'separator' => 'before',
       ]
     );

     $this->add_control(
       'divider_style',
       [
         'label' => esc_html__( 'Style', 'kitify' ),
         'type' => Controls_Manager::SELECT,
         'options' => [
           'solid' => esc_html__( 'Solid', 'kitify' ),
           'double' => esc_html__( 'Double', 'kitify' ),
           'dotted' => esc_html__( 'Dotted', 'kitify' ),
           'dashed' => esc_html__( 'Dashed', 'kitify' ),
         ],
         'default' => 'solid',
         'condition' => [
           'divider' => 'yes',
         ],
         'selectors' => [
           '{{WRAPPER}} .kitify-information-list__items:not(.elementor-inline-items) .kitify-information-list__item:not(:last-child):after' => 'border-top-style: {{VALUE}}',
           '{{WRAPPER}} .kitify-information-list__items.elementor-inline-items .kitify-information-list__item:not(:last-child):after' => 'border-left-style: {{VALUE}}',
         ],
       ]
     );

     $this->add_control(
       'divider_weight',
       [
         'label' => esc_html__( 'Weight', 'kitify' ),
         'type' => Controls_Manager::SLIDER,
         'default' => [
           'size' => 1,
         ],
         'range' => [
           'px' => [
             'min' => 1,
             'max' => 20,
           ],
         ],
         'condition' => [
           'divider' => 'yes',
         ],
         'selectors' => [
           '{{WRAPPER}} .kitify-information-list__items:not(.elementor-inline-items) .kitify-information-list__item:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}',
           '{{WRAPPER}} .elementor-inline-items .kitify-information-list__item:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}',
         ],
       ]
     );

     $this->add_control(
       'divider_width',
       [
         'label' => esc_html__( 'Width', 'kitify' ),
         'type' => Controls_Manager::SLIDER,
         'default' => [
           'unit' => '%',
         ],
         'condition' => [
           'divider' => 'yes',
           'view!' => 'inline',
         ],
         'selectors' => [
           '{{WRAPPER}} .kitify-information-list__item:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}',
         ],
       ]
     );

     $this->add_control(
       'divider_height',
       [
         'label' => esc_html__( 'Height', 'kitify' ),
         'type' => Controls_Manager::SLIDER,
         'size_units' => [ '%', 'px' ],
         'default' => [
           'unit' => '%',
         ],
         'range' => [
           'px' => [
             'min' => 1,
             'max' => 100,
           ],
           '%' => [
             'min' => 1,
             'max' => 100,
           ],
         ],
         'condition' => [
           'divider' => 'yes',
           'view' => 'inline',
         ],
         'selectors' => [
           '{{WRAPPER}} .kitify-information-list__item:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}',
         ],
       ]
     );

     $this->add_control(
       'divider_color',
       [
         'label' => esc_html__( 'Color', 'kitify' ),
         'type' => Controls_Manager::COLOR,
         'default' => '#ddd',
         'global' => [
           'default' => Global_Colors::COLOR_TEXT,
         ],
         'condition' => [
           'divider' => 'yes',
         ],
         'selectors' => [
           '{{WRAPPER}} .kitify-information-list__item:not(:last-child):after' => 'border-color: {{VALUE}}',
         ],
       ]
     );

     $this->_end_controls_section();
     $this->start_controls_section(
       'section_icon_style',
       [
         'label' => esc_html__( 'Icon', 'kitify' ),
         'tab' => Controls_Manager::TAB_STYLE,
       ]
     );

     $this->add_control(
       'icon_color',
       [
         'label' => esc_html__( 'Color', 'kitify' ),
         'type' => Controls_Manager::COLOR,
         'default' => '',
         'selectors' => [
           '{{WRAPPER}} .kitify-information-list__item-icon i' => 'color: {{VALUE}};',
           '{{WRAPPER}} .kitify-information-list__item-icon svg' => 'fill: {{VALUE}};',
         ],
         'global' => [
           'default' => Global_Colors::COLOR_PRIMARY,
         ],
       ]
     );

     $this->add_control(
       'icon_color_hover',
       [
         'label' => esc_html__( 'Hover', 'kitify' ),
         'type' => Controls_Manager::COLOR,
         'default' => '',
         'selectors' => [
           '{{WRAPPER}} .kitify-information-list__item:hover .kitify-information-list__item-icon i' => 'color: {{VALUE}};',
           '{{WRAPPER}} .kitify-information-list__item:hover .kitify-information-list__item-icon svg' => 'fill: {{VALUE}};',
         ],
       ]
     );

     $this->add_responsive_control(
       'icon_size',
       [
         'label' => esc_html__( 'Size', 'kitify' ),
         'type' => Controls_Manager::SLIDER,
         'default' => [
           'size' => 14,
         ],
         'range' => [
           'px' => [
             'min' => 6,
           ],
         ],
         'selectors' => [
           '{{WRAPPER}}' => '--kitify-information-list-icon-size: {{SIZE}}{{UNIT}};',
         ],
       ]
     );

     $e_icon_list_icon_css_var = 'var(--kitify-information-list-icon-size, 1em)';
     $e_icon_list_icon_align_left = sprintf( '0 calc(%s * 0.25) 0 0', $e_icon_list_icon_css_var );
     $e_icon_list_icon_align_center = sprintf( '0 calc(%s * 0.125)', $e_icon_list_icon_css_var );
     $e_icon_list_icon_align_right = sprintf( '0 0 0 calc(%s * 0.25)', $e_icon_list_icon_css_var );

     $this->add_responsive_control(
       'icon_self_align',
       [
         'label' => esc_html__( 'Alignment', 'kitify' ),
         'type' => Controls_Manager::CHOOSE,
         'options' => [
           'left' => [
             'title' => esc_html__( 'Left', 'kitify' ),
             'icon' => 'eicon-h-align-left',
           ],
           'center' => [
             'title' => esc_html__( 'Center', 'kitify' ),
             'icon' => 'eicon-h-align-center',
           ],
           'right' => [
             'title' => esc_html__( 'Right', 'kitify' ),
             'icon' => 'eicon-h-align-right',
           ],
         ],
         'default' => '',
         'selectors_dictionary' => [
           'left' => sprintf( '--kitify-information-list-icon-align: left; --kitify-information-list-icon-margin: %s;', $e_icon_list_icon_align_left ),
           'center' => sprintf( '--kitify-information-list-icon-align: center; --kitify-information-list-icon-margin: %s;', $e_icon_list_icon_align_center ),
           'right' => sprintf( '--kitify-information-list-icon-align: right; --kitify-information-list-icon-margin: %s;', $e_icon_list_icon_align_right ),
         ],
         'selectors' => [
           '{{WRAPPER}}' => '{{VALUE}}',
         ],
       ]
     );

     $this->end_controls_section();

     $this->start_controls_section(
       'section_text_style',
       [
         'label' => esc_html__( 'Text', 'kitify' ),
         'tab' => Controls_Manager::TAB_STYLE,
       ]
     );

     $this->add_control(
       'text_color',
       [
         'label' => esc_html__( 'Text Color', 'kitify' ),
         'type' => Controls_Manager::COLOR,
         'default' => '',
         'selectors' => [
           '{{WRAPPER}} .kitify-information-list__item-label' => 'color: {{VALUE}};',
         ],
         'global' => [
           'default' => Global_Colors::COLOR_SECONDARY,
         ],
       ]
     );

     $this->add_control(
       'text_color_hover',
       [
         'label' => esc_html__( 'Hover', 'kitify' ),
         'type' => Controls_Manager::COLOR,
         'default' => '',
         'selectors' => [
           '{{WRAPPER}} .kitify-information-list__item:hover .kitify-information-list__item-label' => 'color: {{VALUE}};',
         ],
       ]
     );

     $this->add_control(
       'text_indent',
       [
         'label' => esc_html__( 'Text Indent', 'kitify' ),
         'type' => Controls_Manager::SLIDER,
         'range' => [
           'px' => [
             'max' => 50,
           ],
         ],
         'selectors' => [
           '{{WRAPPER}} .kitify-information-list__item-label' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
         ],
       ]
     );

     $this->add_group_control(
       Group_Control_Typography::get_type(),
       [
         'name' => 'icon_typography',
         'selector' => '{{WRAPPER}} .kitify-information-list__item > .kitify-information-list__item-label, {{WRAPPER}} .kitify-information-list__item > a',
         'global' => [
           'default' => Global_Typography::TYPOGRAPHY_TEXT,
         ],
       ]
     );

     $this->add_group_control(
       Group_Control_Text_Shadow::get_type(),
       [
         'name' => 'text_shadow',
         'selector' => '{{WRAPPER}} .kitify-information-list__item-label',
       ]
     );

     $this->end_controls_section();
   }
   protected function render() {
     $items= $this->get_settings_for_display( 'items' );

     if ( ! $items || empty( $items ) ) {
         return false;
     }
     $id_int = substr( $this->get_id_int(), 0, 3 );

     $this->add_render_attribute( 'information_list', 'class', 'kitify-information-list__items' );
     $this->add_render_attribute( 'information_item', 'class', 'kitify-information-list__item' );
     if ( 'inline' === $this->get_settings_for_display('view') ) {
       $this->add_render_attribute( 'information_list', 'class', 'elementor-inline-items' );
       $this->add_render_attribute( 'information_item', 'class', 'elementor-inline-item' );
     }
     add_action('kitify/theme/canvas_panel', [ $this, 'add_panel' ] );
    ?>
    <ul <?php $this->print_render_attribute_string( 'information_list' ); ?>>
      <?php
      foreach ( $items as $index => $item ) {
        $item_count = $index + 1;
        $item_id = $id_int . $item_count;
        $title_icon_html = '';
        $title_label_html = '';
        if ( ! empty( $item['item_icon'] ) ) {
            ob_start();
            Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] );
            $icon_html = ob_get_clean();
            if(!empty($icon_html)){
                $title_icon_html = sprintf( '<span class="kitify-information-list__item-icon">%1$s</span>', $icon_html );
            }
        }
        if ( ! empty( $item['item_title'] ) ) {
            $title_label_html .= sprintf( '<span class="kitify-information-list__item-label">%1$s</span>', $item['item_title'] );
        }
        echo sprintf('<li data-toggle="kitify_infor_%1$s" aria-controls="kitify_infor_%1$s"%4$s>%2$s %3$s</li>',$item_id, $title_icon_html, $title_label_html, $this->get_render_attribute_string( 'information_item' ));
      }
      ?>
    </ul>
    <?php
   }
   public function add_panel() {
     $template_processed = array();
     $id_int = substr( $this->get_id_int(), 0, 3 );
     $items = $this->get_settings_for_display( 'items' );
     foreach ( $items as $index => $item ) {
       $content_html = '';
       $item_count = $index + 1;
       $item_id = $id_int . $item_count;
       switch ( $item[ 'content_type' ] ) {
         case 'template':
         if ( '0' !== $item['item_template_id'] ) {
           $content_html = kitify()->elementor()->frontend->get_builder_content_for_display( $item['item_template_id'] );
         }
         break;

         case 'editor':
          $content_html = $this->parse_text_editor( $item['item_editor_content'] );
         break;
       }
       ?>
       <div class="kitify-offcanvas site-canvas-menu off-canvas position-right" id="kitify_infor_<?php echo $item_id; ?>" data-off-canvas data-transition="overlap">
         <h2 class="title"><?php echo esc_html( $item['item_title'] );?></h2>
         <div class="nova-offcanvas__content nova_box_ps">
           <?php echo $content_html; ?>
         </div>
         <button class="close-button" aria-label="Close menu" type="button" data-close>
           <svg class="nova-close-canvas">
             <use xlink:href="#nova-close-canvas"></use>
           </svg>
         </button>
       </div>
       <?php
     }
   }
 }
