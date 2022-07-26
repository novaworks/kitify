<?php
/**
 * Class: Kitify_Table_Of_Contents
 * Name: Table of Contents
 * Slug: kitify-table-of-contents
 */

namespace Elementor;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Kitify_Table_Of_Contents extends Kitify_Base {

    protected function enqueue_addon_resources(){
        wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/tablet-contents.css'), ['kitify-base'], kitify()->get_version());
        wp_register_script( $this->get_name(), kitify()->plugin_url('assets/js/addons/tablet-contents.js'), ['kitify-base'], kitify()->get_version(), true);
        $this->add_style_depends( $this->get_name() );
        $this->add_script_depends( $this->get_name() );
    }

	public function get_name() {
		return 'kitify-table-of-contents';
	}

	public function get_widget_title() {
		return esc_html__( 'Tablet of Contents', 'kitify' );
	}

	public function get_icon() {
		return 'kitify-icon-table-of-contents';
	}

	public function get_keywords() {
		return [ 'toc' ];
	}

	public function get_frontend_settings() {
		$frontend_settings = parent::get_frontend_settings();

		if ( Plugin::instance()->experiments->is_feature_active( 'e_font_icon_svg' ) && ! empty( $frontend_settings['icon']['value'] ) ) {
			$frontend_settings['icon']['rendered_tag'] = Icons_Manager::render_font_icon( $frontend_settings['icon'] );
		}

		return $frontend_settings;
	}

	protected function register_controls() {
		$this->start_controls_section(
			'table_of_contents',
			[
				'label' => __( 'Table of Contents', 'kitify' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'kitify' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'default' => __( 'Table of Contents', 'kitify' ),
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label' => __( 'HTML Tag', 'kitify' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
				],
				'default' => 'h4',
			]
		);

		$this->start_controls_tabs( 'include_exclude_tags', [ 'separator' => 'before' ] );

		$this->start_controls_tab( 'include',
			[
				'label' => __( 'Include', 'kitify' ),
			]
		);

		$this->add_control(
			'headings_by_tags',
			[
				'label' => __( 'Anchors By Tags', 'kitify' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [ 'h2', 'h3', 'h4', 'h5', 'h6' ],
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'label_block' => true,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'container',
			[
				'label' => __( 'Container', 'kitify' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => __( 'This control confines the Table of Contents to heading elements under a specific container', 'kitify' ),
				'frontend_available' => true,
			]
		);

		$this->end_controls_tab(); // include

		$this->start_controls_tab( 'exclude',
			[
				'label' => __( 'Exclude', 'kitify' ),
			]
		);

		$this->add_control(
			'exclude_headings_by_selector',
			[
				'label' => __( 'Anchors By Selector', 'kitify' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'CSS selectors, in a comma-separated list', 'kitify' ),
				'default' => [],
				'label_block' => true,
				'frontend_available' => true,
			]
		);

		$this->end_controls_tab(); // exclude

		$this->end_controls_tabs(); // include_exclude_tags

		$this->add_control(
			'marker_view',
			[
				'label' => __( 'Marker View', 'kitify' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'numbers',
				'options' => [
					'numbers' => __( 'Numbers', 'kitify' ),
					'bullets' => __( 'Bullets', 'kitify' ),
				],
				'separator' => 'before',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'kitify' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-circle',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'circle',
						'dot-circle',
						'square-full',
					],
					'fa-regular' => [
						'circle',
						'dot-circle',
						'square-full',
					],
				],
				'condition' => [
					'marker_view' => 'bullets',
				],
				'skin' => 'inline',
				'label_block' => false,
				'exclude_inline_options' => [ 'svg' ],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section(); // table_of_contents

		$this->start_controls_section(
			'additional_options',
			[
				'label' => __( 'Additional Options', 'kitify' ),
			]
		);

		$this->add_control(
			'word_wrap',
			[
				'label' => __( 'Word Wrap', 'kitify' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'ellipsis',
				'prefix_class' => 'kitify-toc--content-',
			]
		);

		$this->add_control(
			'minimize_box',
			[
				'label' => __( 'Minimize Box', 'kitify' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'expand_icon',
			[
				'label' => __( 'Icon', 'kitify' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-down',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'chevron-down',
						'angle-down',
						'angle-double-down',
						'caret-down',
						'caret-square-down',
					],
					'fa-regular' => [
						'caret-square-down',
					],
				],
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'minimize_box' => 'yes',
				],
			]
		);

		$this->add_control(
			'collapse_icon',
			[
				'label' => __( 'Minimize Icon', 'kitify' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-up',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'chevron-up',
						'angle-up',
						'angle-double-up',
						'caret-up',
						'caret-square-up',
					],
					'fa-regular' => [
						'caret-square-up',
					],
				],
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'minimize_box' => 'yes',
				],
			]
		);

		$breakpoints = kitify_helper()->get_active_breakpoints();

		$this->add_control(
			'minimized_on',
			[
				'label' => __( 'Minimized On', 'kitify' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'tablet',
				'options' => [
					/* translators: %d: Breakpoint number. */
					'mobile' => sprintf( __( 'Mobile (< %dpx)', 'kitify' ), $breakpoints['mobile'] ),
					/* translators: %d: Breakpoint number. */
					'tablet' => sprintf( __( 'Tablet (< %dpx)', 'kitify' ), $breakpoints['tablet'] ),
					'none' => __( 'None', 'kitify' ),
				],
				'prefix_class' => 'kitify-toc--minimized-on-',
				'condition' => [
					'minimize_box!' => '',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'hierarchical_view',
			[
				'label' => __( 'Hierarchical View', 'kitify' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'collapse_subitems',
			[
				'label' => __( 'Collapse Subitems', 'kitify' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => __( 'The "Collapse" option should only be used if the Table of Contents is made sticky', 'kitify' ),
				'condition' => [
					'hierarchical_view' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section(); // settings

		$this->start_controls_section(
			'box_style',
			[
				'label' => __( 'Box', 'kitify' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'kitify' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => '--box-background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => __( 'Border Color', 'kitify' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--box-border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'loader_color',
			[
				'label' => __( 'Loader Color', 'kitify' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					// Not using CSS var for BC, when not configured: the loader should get the color from the body tag.
					'{{WRAPPER}} .kitify-toc__spinner' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'border_width',
			[
				'label' => __( 'Border Width', 'kitify' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}' => '--box-border-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'kitify' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}' => '--box-border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => __( 'Padding', 'kitify' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}' => '--box-padding: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'min_height',
			[
				'label' => __( 'Min Height', 'kitify' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--box-min-height: {{SIZE}}{{UNIT}}',
				],
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}}',
			]
		);

		$this->end_controls_section(); // box_style

		$this->start_controls_section(
			'header_style',
			[
				'label' => __( 'Header', 'kitify' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'header_background_color',
			[
				'label' => __( 'Background Color', 'kitify' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--header-background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'header_text_color',
			[
				'label' => __( 'Text Color', 'kitify' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--header-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'header_typography',
				'selector' => '{{WRAPPER}} .kitify-toc__header, {{WRAPPER}} .kitify-toc__header-title',
			]
		);

		$this->add_control(
			'toggle_button_color',
			[
				'label' => __( 'Icon Color', 'kitify' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'minimize_box' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--toggle-button-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'header_separator_width',
			[
				'label' => __( 'Separator Width', 'kitify' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}' => '--separator-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section(); // header_style

		$this->start_controls_section(
			'list_style',
			[
				'label' => __( 'List', 'kitify' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'max_height',
			[
				'label' => __( 'Max Height', 'kitify' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--toc-body-max-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'list_typography',
				'selector' => '{{WRAPPER}} .kitify-toc__list-item',
			]
		);

		$this->add_control(
			'list_indent',
			[
				'label' => __( 'Indent', 'kitify' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'unit' => 'em',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--nested-list-indent: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs( 'item_text_style' );

		$this->start_controls_tab( 'normal',
			[
				'label' => __( 'Normal', 'kitify' ),
			]
		);

		$this->add_control(
			'item_text_color_normal',
			[
				'label' => __( 'Text Color', 'kitify' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_text_underline_normal',
			[
				'label' => __( 'Underline', 'kitify' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-decoration: underline',
				],
			]
		);

		$this->end_controls_tab(); // normal

		$this->start_controls_tab( 'hover',
			[
				'label' => __( 'Hover', 'kitify' ),
			]
		);

		$this->add_control(
			'item_text_color_hover',
			[
				'label' => __( 'Text Color', 'kitify' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-hover-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_text_underline_hover',
			[
				'label' => __( 'Underline', 'kitify' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-hover-decoration: underline',
				],
			]
		);

		$this->end_controls_tab(); // hover

		$this->start_controls_tab( 'active',
			[
				'label' => __( 'Active', 'kitify' ),
			]
		);

		$this->add_control(
			'item_text_color_active',
			[
				'label' => __( 'Text Color', 'kitify' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-active-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_text_underline_active',
			[
				'label' => __( 'Underline', 'kitify' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-active-decoration: underline',
				],
			]
		);

		$this->end_controls_tab(); // active

		$this->end_controls_tabs(); // item_text_style

		$this->add_control(
			'heading_marker',
			[
				'label' => __( 'Marker', 'kitify' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'marker_color',
			[
				'label' => __( 'Color', 'kitify' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--marker-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'marker_size',
			[
				'label' => __( 'Size', 'kitify' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}}' => '--marker-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section(); // list_style
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'body', 'class', 'kitify-toc__body' );

		if ( $settings['collapse_subitems'] ) {
			$this->add_render_attribute( 'body', 'class', 'kitify-toc__list-items--collapsible' );
		}

		$html_tag = Utils::validate_html_tag( $settings['html_tag'] );
		?>
        <div class="kitify-toc__header">
			<?php echo '<' . $html_tag . ' class="kitify-toc__header-title">' . $settings['title'] . '</' . $html_tag . '>'; ?>
			<?php if ( 'yes' === $settings['minimize_box'] ) : ?>
                <div class="kitify-toc__toggle-button kitify-toc__toggle-button--expand"><?php Icons_Manager::render_icon( $settings['expand_icon'] ); ?></div>
                <div class="kitify-toc__toggle-button kitify-toc__toggle-button--collapse"><?php Icons_Manager::render_icon( $settings['collapse_icon'] ); ?></div>
			<?php endif; ?>
        </div>
        <div <?php echo $this->get_render_attribute_string( 'body' ); ?>>
            <div class="kitify-toc__spinner-container">
				<?php
				Icons_Manager::render_icon(
					[
						'library' => 'eicons',
						'value' => 'eicon-loading',
					],
					[
						'class' => [
							'kitify-toc__spinner',
							'eicon-animation-spin',
						],
						'aria-hidden' => 'true',
					]
				); ?>
            </div>
        </div>
		<?php
	}

}
