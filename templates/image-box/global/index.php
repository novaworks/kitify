<?php
/**
 * Image Box template
 */

$settings = $this->get_settings_for_display();
$box_style_simple = $this->get_settings_for_display('box_style_simple');
$box_border_hover_background_direction = $this->get_settings_for_display('box_border_hover_background_direction');

// Data Sanitization/Escaping
$options_box_title_size = array_keys([
    'h1'   => 'H1',
    'h2'   => 'H2',
    'h3'   => 'H3',
    'h4'   => 'H4',
    'h5'   => 'H5',
    'h6'   => 'H6',
    'div'  => 'div',
    'span' => 'span',
    'p'    => 'p',
]);

$box_content_text_align = $this->get_settings_for_display('box_content_text_align');
$box_enable_btn = $this->get_settings_for_display('box_enable_btn');
$box_icon_align = $this->get_settings_for_display('box_icon_align');
$box_front_title_icons = $this->get_settings_for_display('box_front_title_icons');
$box_front_title_icon_position = $this->get_settings_for_display('box_front_title_icon_position');
$box_title_text = $this->get_settings_for_display('box_title_text');
$box_btn_url = $this->get_settings_for_display('box_btn_url');
$box_enable_link = $this->get_settings_for_display('box_enable_link');
$box_website_link = $this->get_settings_for_display('box_website_link');
$title_tag = kitify_helper()->validate_html_tag( $this->get_settings_for_display('box_title_size') );

$box_classes = ['kitify-imagebox'];
$box_classes[] = 'text-' . $box_content_text_align;
$box_classes[] = 'kitify-imagebox__content-align-' . $box_content_text_align;
$box_classes[] = $box_style_simple;

if ($box_style_simple == 'hover-border-bottom') {
    $box_classes[] = $box_border_hover_background_direction;
}

$this->add_render_attribute('wrapper', 'class', $box_classes);


// Image  wrapper
$link_wrapper_start = '';
$link_wrapper_end = '';

if(filter_var($box_enable_btn, FILTER_VALIDATE_BOOLEAN)){
    $this->add_link_attributes('link', $box_btn_url );
    $link_wrapper_start .= '<a ' . $this->get_render_attribute_string('link') . '>';
    $link_wrapper_end .= '</a>';
}

$title_open_tag = '<'.$title_tag.' class="kitify-imagebox__title">' . $link_wrapper_start;
$title_close_tag = $link_wrapper_end . '</'.$title_tag.'>';

echo sprintf('<div %1$s>', $this->get_render_attribute_string('wrapper'));
    if(filter_var($box_enable_link, FILTER_VALIDATE_BOOLEAN)){
        $this->add_link_attributes( 'box_link', $box_website_link);
        echo sprintf('<a %1$s>', $this->get_render_attribute_string( 'box_link' ));
    }
    echo $this->get_main_image('<div class="kitify-imagebox__header">%s</div>');
    if(filter_var($box_enable_link, FILTER_VALIDATE_BOOLEAN)){
        echo '</a>';
    }
    echo '<div class="kitify-imagebox__body">';
        echo '<div class="kitify-imagebox__body_inner">';
            if($box_style_simple == 'floating-style'){
                if($box_front_title_icon_position == 'left'){
                    $title_open_tag .= $this->get_main_icon();
                }
                elseif ($box_front_title_icon_position == 'right'){
                    $title_close_tag = $this->get_main_icon() . $title_close_tag;
                }
            }

            $this->_html( 'box_title_text', $title_open_tag . '<span class="kitify-imagebox__title_text">%s</span>' . $title_close_tag );
            $this->_html( 'box_description_text', '<div class="kitify-imagebox__desc">%s</div>' );
        echo '</div>';
        if(filter_var($box_enable_btn, FILTER_VALIDATE_BOOLEAN)){
            echo '<div class="kitify-iconbox__button_wrapper">';
            $this->add_link_attributes( 'button', $box_btn_url);
            $this->add_render_attribute('button', 'class', 'elementor-button-link elementor-button');
            $btn_text = $this->get_button_icon('<span class="elementor-button-icon elementor-align-icon-'. $box_icon_align .'">%s</span>');
            $btn_text .= sprintf('<span class="elementor-button-text">%s</span>', $this->get_settings_for_display('box_btn_text'));
            echo sprintf('<a %1$s><span class="elementor-button-content-wrapper">%2$s</span></a>', $this->get_render_attribute_string('button'), $btn_text);
            echo '</div>';
        }
    echo '</div>';
echo '</div>';