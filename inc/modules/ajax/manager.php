<?php
/**
 *
 * Version: 1.0.0
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('Kitify_Ajax_Manager')) {

    /**
     * Ajax builder class.
     *
     * @since 1.0.0
     */
    class Kitify_Ajax_Manager
    {
	    const NONCE_KEY = 'kitify_ajax';

	    /**
	     * Ajax actions.
	     *
	     * Holds all the register ajax action.
	     *
	     * @since 1.1.2
	     * @access private
	     *
	     * @var array
	     */
	    private $ajax_actions = [];

	    /**
	     * Ajax requests.
	     *
	     * Holds all the register ajax requests.
	     *
	     * @since 1.1.2
	     * @access private
	     *
	     * @var array
	     */
	    private $requests = [];

	    /**
	     * Ajax response data.
	     *
	     * Holds all the response data for all the ajax requests.
	     *
	     * @since 1.1.2
	     * @access private
	     *
	     * @var array
	     */
	    private $response_data = [];

	    /**
	     * Current ajax action ID.
	     *
	     * Holds all the ID for the current ajax action.
	     *
	     * @since 1.1.2
	     * @access private
	     *
	     * @var string|null
	     */
	    private $current_action_id = null;

	    /**
	     * Ajax manager constructor.
	     *
	     * Initializing Kitify ajax manager.
	     *
	     * @since 1.1.2
	     * @access public
	     */

    	public function __construct() {
			add_action('wp_ajax_nopriv_kitify_ajax', [ $this, 'handle_ajax_request' ] );
			add_action('wp_ajax_kitify_ajax', [ $this, 'handle_ajax_request' ] );
			add_action('kitify_ajax_do_ajax', [ $this, 'handle_ajax_request' ] );

		    add_action( 'init', array( __CLASS__, 'define_ajax' ), 0 );
		    add_action( 'template_redirect', array( __CLASS__, 'do_kitify_ajax' ), 0 );
		}


	    /**
	     * Register ajax action.
	     *
	     * Add new actions for a specific ajax request and the callback function to
	     * be handle the response.
	     *
	     * @since 1.1.2
	     * @access public
	     *
	     * @param string   $tag      Ajax request name/tag.
	     * @param callable $callback The callback function.
	     */
	    public function register_ajax_action( $tag, $callback ) {
		    if ( ! did_action( 'kitify/ajax/register_actions' ) ) {
			    _doing_it_wrong( __METHOD__, esc_html( sprintf( 'Use `%s` hook to register ajax action.', 'kitify/ajax/register_actions' ) ), '1.1.2' );
		    }

		    $this->ajax_actions[ $tag ] = compact( 'tag', 'callback' );
	    }

	    /**
	     * Handle ajax request.
	     *
	     * Verify ajax nonce, and run all the registered actions for this request.
	     *
	     * Fired by `wp_ajax_kitify_ajax` action.
	     *
	     * @since 1.1.2
	     * @access public
	     */
	    public function handle_ajax_request() {
		    if ( ! $this->verify_request_nonce() ) {

			    $this->current_action_id = 'error';

			    $this->add_response_data( false, esc_html__( 'Token Expired.', 'kitify' ) )
			         ->send_error( 401 );
		    }

		    if(empty($_REQUEST['actions'])){
			    $this->add_response_data( false, esc_html__( 'Action not found.', 'kitify' ) )
			         ->send_error( 401 );
		    }

		    /**
		     * Register ajax actions.
		     *
		     * Fires when an ajax request is received and verified.
		     *
		     * Used to register new ajax action handles.
		     *
		     * @since 1.1.2
		     *
		     * @param self $this An instance of ajax manager.
		     */
		    do_action( 'kitify/ajax/register_actions', $this );

		    $this->requests = json_decode( stripslashes( $_REQUEST['actions'] ), true );

		    if(empty($this->requests)){
			    $this->add_response_data( false, esc_html__( 'Action not found.', 'kitify' ) )
			         ->send_error( 401 );
		    }

		    foreach ( $this->requests as $id => $action_data ) {
			    $this->current_action_id = $id;

			    if ( ! isset( $this->ajax_actions[ $action_data['action'] ] ) ) {
				    $this->add_response_data( false, esc_html__( 'Action not found.', 'kitify' ), 400 );

				    continue;
			    }

			    try {
				    $results = call_user_func( $this->ajax_actions[ $action_data['action'] ]['callback'], $action_data['data'], $this );

				    if ( false === $results ) {
					    $this->add_response_data( false );
				    } else {
					    $this->add_response_data( true, $results );
				    }
			    } catch ( \Exception $e ) {
				    $this->add_response_data( false, $e->getMessage(), $e->getCode() );
			    }
		    }

		    $this->current_action_id = null;

		    $this->send_success();
	    }

	    /**
	     * Get current action data.
	     *
	     * Retrieve the data for the current ajax request.
	     *
	     * @since 1.1.2
	     * @access public
	     *
	     * @return bool|mixed Ajax request data if action exist, False otherwise.
	     */
	    public function get_current_action_data() {
		    if ( ! $this->current_action_id ) {
			    return false;
		    }

		    return $this->requests[ $this->current_action_id ];
	    }

	    /**
	     * Create nonce.
	     *
	     * Creates a cryptographic token to
	     * give the user an access to ajax actions.
	     *
	     * @since 1.1.2
	     * @access public
	     *
	     * @return string The nonce token.
	     */
	    public function create_nonce() {
		    return wp_create_nonce( self::NONCE_KEY );
	    }

	    /**
	     * Verify request nonce.
	     *
	     * Whether the request nonce verified or not.
	     *
	     * @since 1.1.2
	     * @access public
	     *
	     * @return bool True if request nonce verified, False otherwise.
	     */
	    public function verify_request_nonce() {
		    return ! empty( $_REQUEST['_nonce'] ) && wp_verify_nonce( $_REQUEST['_nonce'], self::NONCE_KEY );
	    }

	    /**
	     * Ajax success response.
	     *
	     * Send a JSON response data back to the ajax request, indicating success.
	     *
	     * @since 1.1.2
	     * @access protected
	     */
	    private function send_success() {
		    $response = [
			    'success' => true,
			    'data' => [
				    'responses' => $this->response_data,
			    ],
		    ];

		    $json = wp_json_encode( $response );

		    while ( ob_get_status() ) {
			    ob_end_clean();
		    }

		    if ( function_exists( 'gzencode' ) ) {
			    $response = gzencode( $json );

			    header( 'Content-Type: application/json; charset=utf-8' );
			    header( 'Content-Encoding: gzip' );
			    header( 'Content-Length: ' . strlen( $response ) );

			    echo $response; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		    } else {
			    echo $json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		    }

		    wp_die( '', '', [ 'response' => null ] );
	    }

	    /**
	     * Ajax failure response.
	     *
	     * Send a JSON response data back to the ajax request, indicating failure.
	     *
	     * @since 1.1.2
	     * @access protected
	     *
	     * @param null $code
	     */
	    private function send_error( $code = null ) {
		    wp_send_json_error( [
			    'responses' => $this->response_data,
		    ], $code );
	    }

	    /**
	     * Add response data.
	     *
	     * Add new response data to the array of all the ajax requests.
	     *
	     * @since 1.1.2
	     * @access protected
	     *
	     * @param bool  $success True if the requests returned successfully, False
	     *                       otherwise.
	     * @param mixed $data    Optional. Response data. Default is null.
	     *
	     * @param int   $code    Optional. Response code. Default is 200.
	     *
	     * @return Kitify_Ajax_Manager An instance of ajax manager.
	     */
	    private function add_response_data( $success, $data = null, $code = 200 ) {
		    $this->response_data[ $this->current_action_id ] = [
			    'success' => $success,
			    'code' => $code,
			    'data' => $data,
		    ];

		    return $this;
	    }

	    /**
	     * Set Lakit AJAX constant and headers.
	     */
	    public static function define_ajax() {
		    // phpcs:disable
		    if ( ! empty( $_REQUEST['kitify-ajax'] ) ) {

			    if ( ! defined( 'DOING_AJAX' ) ) {
				    define( 'DOING_AJAX', true );
			    }

			    if ( defined('WP_DEBUG') && (! WP_DEBUG || ( WP_DEBUG && ! WP_DEBUG_DISPLAY )) ) {
				    @ini_set( 'display_errors', 0 ); // Turn off display_errors during AJAX events to prevent malformed JSON.
			    }
			    $GLOBALS['wpdb']->hide_errors();
		    }
		    // phpcs:enable
	    }

	    /**
	     * Check for Lakit Ajax request and fire action.
	     */
	    public static function do_kitify_ajax() {

		    // phpcs:disable WordPress.Security.NonceVerification.Recommended
		    if ( ! empty( $_REQUEST['kitify-ajax'] ) ) {
			    self::kitify_ajax_headers();
			    do_action( 'kitify_ajax_do_ajax' );
			    wp_die();
		    }
		    // phpcs:enable
	    }

	    /**
	     * Send headers for Lakit Ajax Requests.
	     *
	     * @since 1.0.0
	     */
	    private static function kitify_ajax_headers() {
		    if ( ! headers_sent() ) {
			    send_origin_headers();
			    send_nosniff_header();
			    if ( ! defined( 'DONOTCACHEPAGE' ) ) {
				    define( 'DONOTCACHEPAGE', true );
			    }
			    if ( ! defined( 'DONOTCACHEOBJECT' ) ) {
				    define( 'DONOTCACHEOBJECT', true );
			    }
			    if ( ! defined( 'DONOTCACHEDB' ) ) {
				    define( 'DONOTCACHEDB', true );
			    }
			    nocache_headers();
			    header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
			    header( 'X-Robots-Tag: noindex' );
			    status_header( 200 );
		    }
		    elseif ( defined('WP_DEBUG') && WP_DEBUG ) {
			    headers_sent( $file, $line );
			    trigger_error( "kitify_ajax_headers cannot set headers - headers already sent by {$file} on line {$line}", E_USER_NOTICE ); // @codingStandardsIgnoreLine
		    }
	    }

    }
}
