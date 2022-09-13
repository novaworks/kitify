<?php

namespace KitifyExtensions;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Module_Base {
    /**
     * Module instance.
     *
     * Holds the module instance.
     *
     * @since 1.0.0
     * @access protected
     *
     * @var Module_Base
     */
    protected static $_instances = [];

    /**
     * Class name.
     *
     * Retrieve the name of the class.
     *
     * @since 1.0.0
     * @access public
     * @static
     */
    public static function class_name() {
        return get_called_class();
    }

    /**
     * Instance.
     *
     * Ensures only one instance of the module class is loaded or can be loaded.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return Module_Base An instance of the class.
     */
    public static function instance() {
        $class_name = static::class_name();

        if ( empty( static::$_instances[ $class_name ] ) ) {
            static::$_instances[ $class_name ] = new static();
        }

        return static::$_instances[ $class_name ];
    }


    public static function is_active(){
        return true;
    }
}
