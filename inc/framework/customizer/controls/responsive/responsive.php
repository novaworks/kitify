<?php
/**
 * Module to work with standard WordPress customizer
 *
 * Version: 1.0.3
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! class_exists( 'WP_Customize_Control' ) ) {
    require_once ABSPATH . WPINC . '/class-wp-customize-control.php';
}

if ( ! class_exists( 'CX_Customizer_Responsive_control' ) ) {

    class CX_Customizer_Responsive_control extends WP_Customize_Control {

        public $type = 'kitify_responsive';

        /**
         * Default.
         *
         * It will hold default value.
         *
         * @since 1.0.0
         * @access public
         *
         * @var mix
         */
        public $default = null;

        /**
         * Default Laptop.
         *
         * It will hold default value for laptop.
         *
         * @since 1.0.0
         * @access public
         *
         * @var mix
         */
        public $default_laptop = null;

        /**
         * Default Tablet.
         *
         * It will hold default value for tablet.
         *
         * @since 1.0.0
         * @access public
         *
         * @var mix
         */
        public $default_tablet = null;

        /**
         * Default Mobile Landscape.
         *
         * It will hold default value for mobile extra mode.
         *
         * @since 1.0.0
         * @access public
         *
         * @var mix
         */
        public $default_mobile_extra = null;

        /**
         * Default Mobile.
         *
         * It will hold default value for mobile.
         *
         * @since 1.0.0
         * @access public
         *
         * @var mix
         */
        public $default_mobile = null;

        /**
         * Depends.
         *
         * It will hold depends of control.
         *
         * @since 1.0.0
         * @access public
         *
         * @var array
         */
        public $depends = null;

        /**
         * Choices.
         *
         * It will hold choices for choices supported control.
         *
         * @since 1.0.0
         * @access public
         *
         * @var array
         */
        public $choices = null;

        /**
         * Placeholder.
         *
         * It will hold placeholder text for control.
         *
         * @since 1.0.0
         * @access public
         *
         * @var string
         */
        public $placeholder = null;

        /**
         * Responsive.
         *
         * It will hold that input will be responsive supported or not.
         *
         * @since 1.0.0
         * @access public
         *
         * @var bool
         */
        public $responsive = false;

        /**
         * CSS.
         *
         * It will hold css array.
         *
         * @since 1.0.0
         * @access public
         *
         * @var bool
         */
        public $css = array();

        /**
         * Constructor Method for control
         *
         * @since 1.0.0
         * @access public
         *
         * @param WP_Customize_Manager $manager Customizer bootstrap instance.
         * @param string               $id      Control ID.
         * @param array                $args    Argument of control.
         *
         * @return void
         */

        /**
         * Minimum
         *
         * Holds minimum value of number input.
         *
         * @since 1.0.0
         * @access public
         *
         * @var int
         */
        public $min = 0;

        /**
         * Maximum
         *
         * Holds maximum value of number input.
         *
         * @since 1.0.0
         * @access public
         *
         * @var int
         */
        public $max = 100;

        /**
         * Step
         *
         * Holds step value of number input.
         *
         * @since 1.0.0
         * @access public
         *
         * @var int
         */
        public $step = 1;

        /**
         * No Unit
         *
         * Holds is unit will work or not work with number input.
         *
         * @since 1.0.0
         * @access public
         *
         * @var bool
         */
        public $no_unit = false;

        /**
         * Default Unit
         *
         * Holds default unit for number input.
         *
         * @since 1.0.0
         * @access public
         *
         * @var int
         */
        public $unit = 'px';

        /**
         * Default Unit
         *
         * Holds supported units for number input.
         * Supported units should be: 'px', '%', 'em', 'rem', 'ex', 'ch', 'vw', 'vh', 'vmin', 'vmax', 'cm', 'mm', 'in', 'pt', 'pc'
         *
         * @since 1.0.0
         * @access public
         *
         * @var array
         */
        public $units = array( 'px', '%', 'em', 'rem' );

        /**
         * @param WP_Customize_Manager $manager the customize manager class.
         * @param string               $id      id.
         * @param array                $args    customizer manager parameters.
         */
        public function __construct( $manager, $id, $args = array() ) {

            $manager->register_control_type( 'CX_Customizer_Responsive_control' );

            parent::__construct( $manager, $id, $args );

            if(!empty($args['responsive']) && $args['responsive']){
                $this->responsive = true;
            }

            if ( $this->responsive && isset( $args['settings'] ) && is_array( $args['settings'] ) ) {
                $this->settings = array();
                foreach ( $args['settings'] as $setting_key => $setting ) {
                    $this->settings[ $setting_key ] = $this->manager->get_setting( $setting );
                }
            }

        }

        public function enqueue(){

            $version = '1.0.0';

            $dependency = array(
                'jquery',
                'customize-base',
                'customize-controls',
            );

            wp_enqueue_style( 'kitify-customizer-responsive-control', plugin_dir_url(__FILE__ ) . 'responsive.css', null, $version );
            wp_enqueue_script( 'kitify-customizer-responsive-control', plugin_dir_url(__FILE__ ) . 'responsive.js', $dependency, $version, true );
        }

        /**
         * To JSON.
         *
         * @since 1.0.0
         * @access public
         *
         * @return void
         */
        public function to_json() {
            parent::to_json();

            if ( $this->responsive ) {
                $this->json['default']                  = $this->settings['default']->default;
                $this->json['default_laptop']           = $this->settings['laptop']->default;
                $this->json['default_tablet_extra']     = $this->settings['tablet_extra']->default;
                $this->json['default_tablet']           = $this->settings['tablet']->default;
                $this->json['default_mobile_extra']     = $this->settings['mobile_extra']->default;
                $this->json['default_mobile']           = $this->settings['mobile']->default;
            }
            else {
                $this->json['default'] = $this->setting->default;
            }

            if ( isset( $this->default ) ) {
                $this->json['default'] = $this->default;
            }

            $this->json['value']       = $this->value();
            $this->json['link']        = $this->get_link();
            $this->json['id']          = $this->id;
            $this->json['depends']     = $this->depends;
            $this->json['choices']     = $this->choices;
            $this->json['placeholder'] = $this->placeholder;
            $this->json['responsive']  = $this->responsive;
            $this->json['css']         = $this->css;

            $this->json['min']  = $this->min;
            $this->json['max']  = $this->max;
            $this->json['step'] = $this->step;

            $this->json['no_unit'] = $this->no_unit;
            $this->json['unit']    = $this->unit;
            $this->json['units']   = $this->units;
        }

        /**
         * Content Template
         *
         * Main template for Control.
         *
         * @since 1.0.0
         * @access public
         *
         * @return void
         */
        public function content_template() {
            ?>
            <div class="kitifycustomizer-control-wrap kitifycustomizer-inline-control kitifycustomizer-control-<?php echo esc_attr( $this->type ); ?>">
                <div class="kitifycustomizer-control-title">
                    <# if ( data.label ) { #>
                    <label class="customize-control-title">{{{ data.label }}} <# if ( data.description ) { #><span class="kitifycustomizer-toggle-desc"><i class="dashicons dashicons-editor-help"></i></span><# } #></label>
                    <# } #>
                    <# if ( data.responsive ) { #>
                    <ul class="kitifycustomizer-control-responsive">
                        <li>
                            <button class="kitifycustomizer-device-desktop" type="button" data-device="desktop">
                                <span class="dashicons dashicons-desktop"></span>
                            </button>
                        </li>
                        <li>
                            <button class="kitifycustomizer-device-laptop" type="button" data-device="laptop">
                                <span class="dashicons dashicons-laptop"></span>
                            </button>
                        </li>
                        <li>
                            <button class="kitifycustomizer-device-tablet" type="button" data-device="tablet">
                                <span class="dashicons dashicons-tablet"></span>
                            </button>
                        </li>
                        <li>
                            <button class="kitifycustomizer-device-mobile_extra" type="button" data-device="mobile_extra">
                                <span class="dashicons dashicons-smartphone"></span>
                            </button>
                        </li>
                        <li>
                            <button class="kitifycustomizer-device-mobile" type="button" data-device="mobile">
                                <span class="dashicons dashicons-smartphone"></span>
                            </button>
                        </li>
                    </ul>
                    <# } #>
                </div>
                <div class="kitifycustomizer-control-input">
                    <?php $this->input_template(); ?>
                </div>
                <# if ( data.description ) { #>
                <div class="description customize-control-description">{{{ data.description }}}</div>
                <# } #>
            </div>
            <?php
        }

        /**
         * Render Content
         *
         * Render content function for php render. But it will not use.
         *
         * @since 1.0.0
         * @access public
         *
         * @return void
         */
        public function render_content() {}

        /**
         * Input Template
         *
         * Tempate for main input to show at customizer.
         *
         * @since 1.0.0
         * @access public
         *
         * @return  void
         */
        public function input_template() {
            ?>
            <#
            var numberInputType = data.no_unit ? 'number' : 'text';
            #>
            <div class="kitifycustomizer-input-number">
                <input class="kitifycustomizer-number-input" type="{{{ numberInputType }}}" min="{{{ data.min }}}" max="{{{ data.max }}}" step="{{{ data.step }}}" placeholder="{{{ data.placeholder }}}" value="{{{ data.value }}}" />
                <button type="button" class="kitifycustomizer-number-input-up"><span class="dashicons dashicons-arrow-up"></span></button>
                <button type="button" class="kitifycustomizer-number-input-down"><span class="dashicons dashicons-arrow-down"></span></button>
            </div>
            <?php
        }

    }
}


if(!class_exists('CX_Customizer_Checkbox_Multiple_control')){

    class CX_Customizer_Checkbox_Multiple_control extends WP_Customize_Control{
	    /**
	     * The type of customize control being rendered.
	     *
	     * @since  1.0.0
	     * @access public
	     * @var    string
	     */
	    public $type = 'checkbox-multiple';

	    /**
	     * Enqueue scripts/styles.
	     *
	     * @since  1.0.0
	     * @access public
	     * @return void
	     */
	    public function enqueue() {
		    $version = '1.0.0';

		    $dependency = array(
			    'jquery',
			    'customize-base',
			    'customize-controls',
		    );
		    wp_enqueue_script( 'kitify-customizer-checkbox-multiple-control', plugin_dir_url(__FILE__ ) . 'checkbox-multiple.js', $dependency, $version, true );
	    }

	    /**
	     * Displays the control content.
	     *
	     * @since  1.0.0
	     * @access public
	     * @return void
	     */
	    public function render_content() {

		    if ( empty( $this->choices ) )
			    return; ?>

		    <?php if ( !empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		    <?php endif; ?>

		    <?php if ( !empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
		    <?php endif; ?>

		    <?php $multi_values = !is_array( $this->value() ) ? explode( ',', $this->value() ) : $this->value(); ?>

            <ul>
			    <?php foreach ( $this->choices as $value => $label ) : ?>

                    <li>
                        <label>
                            <input type="checkbox" value="<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $multi_values ) ); ?> />
						    <?php echo esc_html( $label ); ?>
                        </label>
                    </li>

			    <?php endforeach; ?>
            </ul>

            <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', $multi_values ) ); ?>" />
	    <?php }
    }

}
