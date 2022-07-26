<?php
/**
 * Class: Kitify_Scroll_Image
 * Name: Scroll Image
 * Slug: kitify-scroll-image
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Kitify_Scroll_Image extends Kitify_Base {

    protected function enqueue_addon_resources(){
      wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/scroll-image.css'), ['kitify-base'], kitify()->get_version());

      $this->add_style_depends( $this->get_name() );
      $this->add_script_depends( 'kitify-base' );
    }

	public function get_name() {
		return 'kitify-scroll-image';
	}

	public function get_widget_title() {
		return esc_html__( 'Scroll Image', 'kitify' );
	}

	public function get_icon() {
		return 'kitify-icon-scroll-image';
	}

	protected function register_controls() {
    /* Content Tab */
		$this->register_content_image_controls();
		$this->register_content_settings_controls();

		/* Style Tab */
		$this->register_style_image_controls();
		$this->register_style_overlay_controls();
	}
  protected function register_content_image_controls() {
    /**
     * Content Tab: Image
     */
    $this->start_controls_section('image_settings',
      [
        'label'                 => __( 'Image', 'kitify' ),
      ]
    );

    $this->add_control('image',
      [
        'label'                 => __( 'Image', 'kitify' ),
        'type'                  => Controls_Manager::MEDIA,
        'dynamic'               => [ 'active' => true ],
        'default'               => [
          'url'   => Utils::get_placeholder_image_src(),
        ],
        'label_block'           => true,
      ]
    );

    $this->add_group_control(
      Group_Control_Image_Size::get_type(),
      [
        'name'                  => 'image',
        'label'                 => __( 'Image Size', 'kitify' ),
        'default'               => 'full',
      ]
    );

    $this->add_responsive_control('image_height',
      [
        'label'                 => __( 'Image Height', 'kitify' ),
        'type'                  => Controls_Manager::SLIDER,
        'size_units'            => [ 'px', 'em', 'vh' ],
        'default'               => [
          'unit'  => 'px',
          'size'  => 300,
        ],
        'range'                 => [
          'px'    => [
            'min'   => 200,
            'max'   => 800,
          ],
          'em'    => [
            'min'   => 1,
            'max'   => 50,
          ],
        ],
        'selectors'             => [
          '{{WRAPPER}} .kitify-image-scroll-container' => 'height: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->add_control(
      'link',
      [
        'label'                 => __( 'URL', 'kitify' ),
        'type'                  => Controls_Manager::URL,
        'dynamic'               => [
          'active' => true,
        ],
        'placeholder'           => 'https://novaworks.net/',
        'label_block'           => true,
      ]
    );

    $this->add_control(
      'icon_heading',
      [
        'label'                 => __( 'Icon', 'kitify' ),
        'type'                  => Controls_Manager::HEADING,
        'separator'             => 'before',
      ]
    );

    $this->add_control(
      'selected_icon',
      [
        'label'                 => __( 'Cover', 'kitify' ) . ' ' . __( 'Icon', 'kitify' ),
        'type'                  => Controls_Manager::ICONS,
        'fa4compatibility'      => 'icon',
      ]
    );

    $this->add_control('icon_size',
      [
        'label'                 => __( 'Icon Size', 'kitify' ),
        'type'                  => Controls_Manager::SLIDER,
        'size_units'            => [ 'px', 'em' ],
        'default'               => [
          'size'  => 30,
        ],
        'range'                 => [
          'px'    => [
            'min' => 5,
            'max' => 100,
          ],
        ],
        'selectors'             => [
          '{{WRAPPER}} .kitify-image-scroll-icon' => 'font-size: {{SIZE}}{{UNIT}};',
        ],
        'condition'             => [
          'selected_icon[value]!' => '',
        ],
      ]
    );

    $this->end_controls_section();
  }
  protected function register_content_settings_controls() {
    /**
     * Content Tab: Settings
     */
    $this->start_controls_section('settings',
      [
        'label'                 => __( 'Settings', 'kitify' ),
      ]
    );

    $this->add_control('trigger_type',
      [
        'label'                 => __( 'Trigger', 'kitify' ),
        'type'                  => Controls_Manager::SELECT,
        'options'               => [
          'hover'   => __( 'Hover', 'kitify' ),
          'scroll'  => __( 'Mouse Scroll', 'kitify' ),
        ],
        'default'               => 'hover',
        'frontend_available'    => true,
      ]
    );

    $this->add_control('duration_speed',
      [
        'label'                 => __( 'Scroll Speed', 'kitify' ),
        'title'                 => __( 'In seconds', 'kitify' ),
        'type'                  => Controls_Manager::NUMBER,
        'default'               => 3,
        'selectors' => [
          '{{WRAPPER}} .kitify-image-scroll-container .kitify-image-scroll-image img'   => 'transition: all {{Value}}s; -webkit-transition: all {{Value}}s;',
        ],
        'condition'             => [
          'trigger_type' => 'hover',
        ],
      ]
    );

    $this->add_control('direction_type',
      [
        'label'                 => __( 'Scroll Direction', 'kitify' ),
        'type'                  => Controls_Manager::SELECT,
        'options'               => [
          'horizontal' => __( 'Horizontal', 'kitify' ),
          'vertical'   => __( 'Vertical', 'kitify' ),
        ],
        'default'               => 'vertical',
        'frontend_available'    => true,
      ]
    );

    $this->add_control('reverse',
      [
        'label'                 => __( 'Reverse Direction', 'kitify' ),
        'type'                  => Controls_Manager::SWITCHER,
        'frontend_available'    => true,
        'condition'             => [
          'trigger_type' => 'hover',
        ],
      ]
    );

    $this->end_controls_section();
  }
  protected function register_style_image_controls() {
		/**
		 * Style Tab: Image
		 */
		$this->start_controls_section('image_style',
			[
				'label'                 => __( 'Image', 'kitify' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control('icon_color',
			[
				'label'                 => __( 'Icon Color', 'kitify' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .kitify-image-scroll-icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .kitify-image-scroll-icon svg' => 'fill: {{VALUE}};',
				],
				'condition'             => [
					'selected_icon[value]!'     => '',
				],
			]
		);

		$this->start_controls_tabs( 'image_style_tabs' );

		$this->start_controls_tab('image_style_tab_normal',
			[
				'label'                 => __( 'Normal', 'kitify' ),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'container_border',
				'selector'              => '{{WRAPPER}} .kitify-image-scroll-wrap',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label'                 => __( 'Border Radius', 'kitify' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em' ],
				'selectors'             => [
					'{{WRAPPER}} .kitify-image-scroll-wrap, {{WRAPPER}} .kitify-container-scroll' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'container_box_shadow',
				'selector'              => '{{WRAPPER}} .kitify-image-scroll-wrap',
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'                  => 'css_filters',
				'selector'              => '{{WRAPPER}} .kitify-image-scroll-container .kitify-image-scroll-image img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab('image_style_tab_hover',
			[
				'label'                 => __( 'Hover', 'kitify' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'container_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .kitify-image-scroll-wrap:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'                  => 'css_filters_hover',
				'selector'              => '{{WRAPPER}} .kitify-image-scroll-container .kitify-image-scroll-image img:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_overlay_controls() {
		/**
		 * Style Tab: Overlay
		 */
		$this->start_controls_section('overlay_style',
			[
				'label'                 => __( 'Overlay', 'kitify' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control('overlay',
			[
				'label'                 => __( 'Overlay', 'kitify' ),
				'type'                  => Controls_Manager::SWITCHER,
				'label_on'              => __( 'Show', 'kitify' ),
				'label_off'             => __( 'Hide', 'kitify' ),

			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'overlay_background',
				'types'                 => [ 'classic', 'gradient' ],
				'selector'              => '{{WRAPPER}} .kitify-image-scroll-overlay',
				'exclude'               => [
					'image',
				],
				'condition'             => [
					'overlay'  => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}
  protected function render() {

		$settings = $this->get_settings_for_display();

		if ( empty( $settings['image']['url'] ) ) {
			return;
		}

		$link_url = $settings['link']['url'];

		if ( '' !== $settings['link']['url'] ) {
			$this->add_render_attribute( 'link', 'class', 'kitify-image-scroll-link kitify-media-content' );

			$this->add_link_attributes( 'link', $settings['link'] );
		}

		$this->add_render_attribute( 'icon', 'class', [
			'kitify-image-scroll-icon',
			'kitify-icon',
			'kitify-mouse-scroll-' . $settings['direction_type'],
		] );

		if ( ! isset( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default
			$settings['icon'] = 'eicon-star';
		}

		$has_icon = ! empty( $settings['icon'] );

		if ( $has_icon ) {
			$this->add_render_attribute( 'i', 'class', $settings['icon'] );
			$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
		}

		$icon_attributes = $this->get_render_attribute_string( 'icon' );

		if ( ! $has_icon && ! empty( $settings['selected_icon']['value'] ) ) {
			$has_icon = true;
		}
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new = ! isset( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		$this->add_render_attribute( [
			'container' => [
				'class' => 'kitify-image-scroll-container',
			],
			'direction_type' => [
				'class' => [ 'kitify-image-scroll-image', 'kitify-image-scroll-' . $settings['direction_type'] ],
			],
		] );
		?>
		<div class="kitify-image-scroll-wrap">
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'container' ) ); ?>>
				<?php if ( ! empty( $settings['icon'] ) || ( ! empty( $settings['selected_icon']['value'] ) && $is_new ) ) { ?>
					<div class="kitify-image-scroll-content">
						<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'icon' ) ); ?>>
							<?php
							if ( $is_new || $migrated ) {
								Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
							} elseif ( ! empty( $settings['icon'] ) ) {
								?><i <?php echo wp_kses_post( $this->get_render_attribute_string( 'i' ) ); ?>></i><?php
							}
							?>
						</span>
					</div>
				<?php } ?>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'direction_type' ) ); ?>>
					<?php if ( 'yes' === $settings['overlay'] ) { ?>
						<div class="kitify-image-scroll-overlay kitify-media-overlay">
					<?php } ?>
					<?php if ( ! empty( $link_url ) ) { ?>
							<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'link' ) ); ?>></a>
					<?php } ?>
					<?php if ( 'yes' === $settings['overlay'] ) { ?>
						</div>
					<?php } ?>

					<?php echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings ) ); ?>
				</div>
			</div>
		</div>
		<?php
	}
  /**
	 * Render scroll image widgets output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.0.3
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
			var direction = settings.direction_type,
				reverse = settings.reverse,
				url,
				iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
				migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' );

			if ( settings.icon || settings.selected_icon.value ) {
				view.addRenderAttribute( 'icon', 'class', [
					'kitify-image-scroll-icon',
					'kitify-icon',
					'kitify-mouse-scroll-' + settings.direction_type,
				] );
			}

			if ( settings.link.url ) {
				view.addRenderAttribute( 'link', 'class', 'kitify-image-scroll-link kitify-media-content' );
				url = settings.link.url;
				view.addRenderAttribute( 'link', 'href',  url );
			}

			view.addRenderAttribute( 'container', 'class', 'kitify-image-scroll-container' );

			view.addRenderAttribute( 'direction_type', 'class', 'kitify-image-scroll-image kitify-image-scroll-' + direction );
		#>
		<div class="kitify-image-scroll-wrap">
			<div {{{ view.getRenderAttributeString('container') }}}>
				<# if ( settings.icon || settings.selected_icon ) { #>
					<div class="kitify-image-scroll-content">
						<span {{{ view.getRenderAttributeString('icon') }}}>
							<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
							{{{ iconHTML.value }}}
							<# } else { #>
								<i class="{{ settings.icon }}" aria-hidden="true"></i>
							<# } #>
						</span>
					</div>
				<# } #>
				<div {{{ view.getRenderAttributeString('direction_type') }}}>
					<# if( 'yes' == settings.overlay ) { #>
						<div class="kitify-image-scroll-overlay kitify-media-overlay">
					<# }
					if ( settings.link.url ) { #>
						<a {{{ view.getRenderAttributeString('link') }}}></a>
					<# }
					if( 'yes' == settings.overlay ) { #>
						</div>
					<# }

					var image = {
						id: settings.image.id,
						url: settings.image.url,
						size: settings.image_size,
						dimension: settings.image_custom_dimension,
						model: view.getEditModel()
					};
					var image_url = elementor.imagesManager.getImageUrl( image );
					#>
					<img src="{{{ image_url }}}" />
				</div>
			</div>
		</div>
		<?php
	}
}
