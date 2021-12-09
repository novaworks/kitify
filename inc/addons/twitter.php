<?php
/**
 * Class: Kitify_Twitter
 * Name: Twitter Feed
 * Slug: kitify-twitter
 */

namespace Elementor;

use Elementor\Core\Schemes\Typography as Scheme_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Kitify_Twitter extends Kitify_Base {

    protected function enqueue_addon_resources(){
        wp_register_style( $this->get_name(), kitify()->plugin_url('assets/css/addons/twitter.css'), ['kitify-base'], kitify()->get_version());
        wp_register_script( $this->get_name(), kitify()->plugin_url('assets/js/addons/twitter.js'), ['kitify-base'], kitify()->get_version(), true);
        $this->add_style_depends( $this->get_name() );
        $this->add_script_depends( $this->get_name() );
    }

	public function get_name() {
		return 'kitify-twitter';
	}

	public function get_widget_title() {
		return esc_html__( 'Twitter Feed', 'kitify' );
	}

	public function get_icon() {
		return 'kitify-icon-twitter';
	}


	protected function register_controls() {

        $css_scheme = apply_filters(
            'kitify/twitter/css-scheme',
            array(
                'wrap_outer'    => '.kitify-twitter-feed',
                'wrap'          => '.kitify-twitter-feed .kitify-twitter_feed__wrapper',
                'column'        => '.kitify-twitter-feed .kitify-twitter_feed__item',
                'inner-box'     => '.kitify-twitter-feed .kitify-twitter_feed__item_inner',
                'content'       => '.kitify-twitter-feed .kitify-twitter_feed__content',
                'author'        => '.kitify-twitter-feed .kitify-twitter_feed__author',
                'link'          => '.kitify-twitter-feed .kitify-twitter_feed__links',
                'logo'          => '.kitify-twitter-feed .kitify-twitter_feed__logo',
                'action'        => '.kitify-twitter-feed .kitify-twitter_feed__interact',
            )
        );

		$this->_start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Setting', 'kitify' ),
			)
		);

		$this->_add_control(
			'screen_name',
			array(
				'label'   => esc_html__( 'Screen Name', 'kitify' ),
				'type'    => Controls_Manager::TEXT,
			)
		);

        $this->_add_responsive_control(
            'columns',
            array(
                'label'   => esc_html__( 'Columns', 'kitify' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 1,
                'options' => kitify_helper()->get_select_range( 6 )
            )
        );

        $this->_add_control(
            'limit',
            array(
                'label' => __( 'Limit', 'kitify' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
            )
        );

        $this->_add_control(
            'show_twitter_icon',
            array(
                'label'        => esc_html__( 'Show Twitter Icon', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );
        $this->_add_control(
            'show_author_box',
            array(
                'label'        => esc_html__( 'Show Author Box', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );
        $this->_add_control(
            'show_posted_date',
            array(
                'label'        => esc_html__( 'Show Posting Date', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );
        $this->_add_control(
            'show_action',
            array(
                'label'        => esc_html__( 'Show Action', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );
        $this->_add_control(
            'show_link',
            array(
                'label'        => esc_html__( 'Show Link in Content', 'kitify' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'kitify' ),
                'label_off'    => esc_html__( 'No', 'kitify' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

		$this->_end_controls_section();

        $this->register_carousel_section( [], 'columns');

        /** Style section */
        $this->_start_controls_section(
            'section_column_style',
            array(
                'label'      => esc_html__( 'Column', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
            )
        );
        $this->_add_responsive_control(
            'column_padding',
            array(
                'label'       => esc_html__( 'Column Padding', 'kitify' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'render_type' => 'template',
                'selectors'   => array(
                    '{{WRAPPER}} ' . $css_scheme['column'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' => '--kitify-carousel-item-top-space: {{TOP}}{{UNIT}}; --kitify-carousel-item-right-space: {{RIGHT}}{{UNIT}};--kitify-carousel-item-bottom-space: {{BOTTOM}}{{UNIT}};--kitify-carousel-item-left-space: {{LEFT}}{{UNIT}};--kitify-gcol-top-space: {{TOP}}{{UNIT}}; --kitify-gcol-right-space: {{RIGHT}}{{UNIT}};--kitify-gcol-bottom-space: {{BOTTOM}}{{UNIT}};--kitify-gcol-left-space: {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_box_style',
            array(
                'label'      => esc_html__( 'Box', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE,
            )
        );

        $this->_add_control(
            'box_bg',
            array(
                'label' => esc_html__( 'Background Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'box_padding',
            array(
                'label'       => esc_html__( 'Box Padding', 'kitify' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            'box_margin',
            array(
                'label'       => esc_html__( 'Box Margin', 'kitify' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_content_style',
            array(
                'label'      => esc_html__( 'Content', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
            )
        );

        $this->_add_control(
            'content_color',
            array(
                'label' => esc_html__( 'Text Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['wrap_outer'] => '--kitify-twitter-feed-text: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'content_link_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content'] . ' a',
            )
        );

        $this->_add_control(
            'link_color',
            array(
                'label' => esc_html__( 'Link Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['wrap_outer'] => '--kitify-twitter-feed-linkcolor: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'link_hover_color',
            array(
                'label' => esc_html__( 'Link Hover Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['wrap_outer'] => '--kitify-twitter-feed-linkhovercolor: {{VALUE}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'content_margin',
            array(
                'label'       => esc_html__( 'Content Margin', 'kitify' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} ' . $css_scheme['content'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_author_style',
            array(
                'label'      => esc_html__( 'Author', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE
            )
        );
        $this->_add_responsive_control(
            'avatar_size',
            array(
                'label'      => esc_html__( 'Avatar Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-twitter_feed__author .TweetAuthor-avatar' => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            'avatar_spacing',
            array(
                'label'       => esc_html__( 'Avatar Spacing', 'kitify' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} .kitify-twitter_feed__author .TweetAuthor-avatar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'author_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['author'] . ' .TweetAuthor-decoratedName',
            )
        );
        $this->_add_control(
            'author_color',
            array(
                'label' => esc_html__( 'Author Text Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['wrap_outer'] => '--kitify-twitter-feed-authorname: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'author_margin',
            array(
                'label'       => esc_html__( 'Author Spacing', 'kitify' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} ' . $css_scheme['content'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'screenname_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['author'] . ' .TweetAuthor-screenName',
            )
        );
        $this->_add_control(
            'screenname_color',
            array(
                'label' => esc_html__( 'ScreenName Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['wrap_outer'] => '--kitify-twitter-feed-screename: {{VALUE}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'authorbox_margin',
            array(
                'label'       => esc_html__( 'Author Box Margin', 'kitify' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} ' . $css_scheme['author'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_interact_style',
            array(
                'label'      => esc_html__( 'Interact/Action', 'kitify' ),
                'tab'        => Controls_Manager::TAB_STYLE
            )
        );
        $this->_add_responsive_control(
            'logo_size',
            array(
                'label'      => esc_html__( 'Logo Size', 'kitify' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'selectors' => array(
                    '{{WRAPPER}} .kitify-twitter_feed__logo' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );
        $this->_add_control(
            'logo_color',
            array(
                'label' => esc_html__( 'Logo Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .kitify-twitter_feed__logo' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'logo_margin',
            array(
                'label'       => esc_html__( 'Logo Spacing', 'kitify' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} .kitify-twitter_feed__logo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'time_typography',
                'selector' => '{{WRAPPER}} .kitify-twitter_feed__links a',
            )
        );
        $this->_add_control(
            'time_color',
            array(
                'label' => esc_html__( 'Posting Time Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['wrap_outer'] => '--kitify-twitter-feed-posted: {{VALUE}}',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'action_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['action'] . ' a',
            )
        );
        $this->_add_control(
            'action_color',
            array(
                'label' => esc_html__( 'Action Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['wrap_outer'] => '--kitify-twitter-feed-action: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            'action_hover_color',
            array(
                'label' => esc_html__( 'Action Hover Color', 'kitify' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['wrap_outer'] => '--kitify-twitter-feed-actionhover: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            'action_margin',
            array(
                'label'       => esc_html__( 'Action Spacing', 'kitify' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} ' . $css_scheme['action'] . ' a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_end_controls_section();

        $this->register_carousel_arrows_dots_style_section([ 'enable_carousel' => 'yes' ]);
	}

	protected function render() {

        $this->add_render_attribute( 'main-container', 'class', 'kitify-twitter-feed' );
        $this->add_render_attribute( 'list-wrapper', 'class', 'kitify-twitter_feed__wrapper' );
        $this->add_render_attribute( 'list-container', 'class', 'kitify-twitter_feed__list' );

        $post_classes = ['kitify-twitter_feed__item'];

        $feed_config = [
            'screen_name' => $this->get_settings_for_display('screen_name'),
            'limit' => $this->get_settings_for_display('limit'),
            'item_class' => '',
            'show_twitter_icon' => $this->get_settings_for_display('show_twitter_icon'),
            'show_author_box' => $this->get_settings_for_display('show_author_box'),
            'show_posted_date' => $this->get_settings_for_display('show_posted_date'),
            'show_action' => $this->get_settings_for_display('show_action'),
            'show_link' => $this->get_settings_for_display('show_link'),
            'uniqueid' => 'twitter_feed_' . $this->get_id()
        ];

        $is_carousel = false;

        if(filter_var($this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN)){
            $slider_options = $this->get_advanced_carousel_options('columns');
            if(!empty($slider_options)){

                $is_carousel = true;

                $this->add_render_attribute( 'main-container', 'data-slider_options', json_encode($slider_options) );
                $this->add_render_attribute( 'main-container', 'dir', is_rtl() ? 'rtl' : 'ltr' );
                $this->add_render_attribute( 'list-wrapper', 'class', 'swiper-container');
                $this->add_render_attribute( 'list-container', 'class', 'swiper-wrapper' );
                $this->add_render_attribute( 'main-container', 'class', 'kitify-carousel' );
                $carousel_id = $this->get_settings_for_display('carousel_id');
                if(empty($carousel_id)){
                    $carousel_id = 'kitify_carousel_' . $this->get_id();
                }
                $this->add_render_attribute( 'list-wrapper', 'id', $carousel_id );

                $post_classes[] = 'swiper-slide';
            }
        }
        else{
            $this->add_render_attribute( 'list-container', 'class', 'col-row' );
            $post_classes[] = kitify_helper()->col_classes([
                'desk' => $this->get_settings_for_display( 'columns' ),
                'tab'  => $this->get_settings_for_display( 'columns_tablet' ),
                'mob'  => $this->get_settings_for_display( 'columns_mobile' ),
            ]);
        }

        $this->add_render_attribute('list-container', 'id', 'twitter_feed_' . $this->get_id());

        $feed_config['item_class'] = join(' ', $post_classes);

        $this->add_render_attribute( 'main-container', 'data-feed_config', json_encode($feed_config) );

        ?>
        <div <?php echo $this->get_render_attribute_string( 'main-container' ); ?>>
            <?php
            if($is_carousel){
                echo '<div class="kitify-carousel-inner">';
            }
            ?>
            <div <?php echo $this->get_render_attribute_string( 'list-wrapper' ); ?>>
                <div <?php echo $this->get_render_attribute_string( 'list-container' ); ?>><div class="loading"><?php esc_html_e('Loading...', 'kitify'); ?></div></div>
            </div>
            <?php
            if($is_carousel){
                echo '</div>';
                if (filter_var($this->get_settings_for_display('carousel_dots'), FILTER_VALIDATE_BOOLEAN)) {
                    echo '<div class="kitify-carousel__dots kitify-carousel__dots_'.$this->get_id().' swiper-pagination"></div>';
                }
                if (filter_var($this->get_settings_for_display('carousel_arrows'), FILTER_VALIDATE_BOOLEAN)) {
                    echo sprintf('<div class="kitify-carousel__prev-arrow-%s kitify-arrow prev-arrow">%s</div>', $this->get_id(), $this->_render_icon('carousel_prev_arrow', '%s', '', false));
                    echo sprintf('<div class="kitify-carousel__next-arrow-%s kitify-arrow next-arrow">%s</div>', $this->get_id(), $this->_render_icon('carousel_next_arrow', '%s', '', false));
                }
                if (filter_var($this->get_settings_for_display('carousel_scrollbar'), FILTER_VALIDATE_BOOLEAN)) {
                    echo '<div class="kitify-carousel__scrollbar swiper-scrollbar"></div>';
                }
            }
            ?>
        </div>
        <?php
	}

}
