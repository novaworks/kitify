<?php
namespace KitifyThemeBuilder\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use KitifyThemeBuilder\Modules\DynamicTags\Tags\Base\Tag;
use KitifyThemeBuilder\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Contact_URL extends Tag {

	public function get_name() {
		return 'contact-url';
	}

	public function get_title() {
		return esc_html__( 'Contact URL', 'kitify' );
	}

	public function get_group() {
		return Module::ACTION_GROUP;
	}

	public function get_categories() {
		return [ Module::URL_CATEGORY ];
	}

	protected function register_controls() {
		$this->add_control(
			'link_type',
			[
				'label' => esc_html__( 'Type', 'kitify' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => '— ' . esc_html__( 'Select', 'kitify' ) . ' —',
					'email' => esc_html__( 'Email', 'kitify' ),
					'tel' => esc_html__( 'Tel', 'kitify' ),
					'sms' => esc_html__( 'SMS', 'kitify' ),
					'whatsapp' => esc_html__( 'WhatsApp', 'kitify' ),
					'skype' => esc_html__( 'Skype', 'kitify' ),
					'messenger' => esc_html__( 'Messenger', 'kitify' ),
					'viber' => esc_html__( 'Viber', 'kitify' ),
					'waze' => esc_html__( 'Waze', 'kitify' ),
					'google_calendar' => esc_html__( 'Google Calendar', 'kitify' ),
					'outlook_calendar' => esc_html__( 'Outlook Calendar', 'kitify' ),
					'yahoo_calendar' => esc_html__( 'Yahoo Calendar', 'kitify' ),
				],
			]
		);

		$this->add_control(
			'mail_to',
			[
				'label' => esc_html__( 'Email', 'kitify' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'link_type' => 'email',
				],
			]
		);

		$this->add_control(
			'mail_subject',
			[
				'label' => esc_html__( 'Subject', 'kitify' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => 'true',
				'condition' => [
					'link_type' => 'email',
				],
			]
		);

		$this->add_control(
			'mail_body',
			[
				'label' => esc_html__( 'Message', 'kitify' ),
				'type' => Controls_Manager::TEXTAREA,
				'condition' => [
					'link_type' => 'email',
				],
			]
		);

		$this->add_control(
			'tel_number',
			[
				'label' => esc_html__( 'Number', 'kitify' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'link_type' => [
						'tel',
						'sms',
						'whatsapp',
						'viber',
					],
				],
			]
		);

		$this->add_control(
			'username',
			[
				'label' => esc_html__( 'Username', 'kitify' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'link_type' => [ 'skype', 'messenger' ],
				],
			]
		);

		$this->add_control(
			'viber_action',
			[
				'label' => esc_html__( 'Action', 'kitify' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'contact' => esc_html__( 'Contact', 'kitify' ),
					'add' => esc_html__( 'Add', 'kitify' ),
				],
				'default' => 'contact',
				'condition' => [
					'link_type' => 'viber',
				],
			]
		);

		$this->add_control(
			'skype_action',
			[
				'label' => esc_html__( 'Action', 'kitify' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'call' => esc_html__( 'Call', 'kitify' ),
					'chat' => esc_html__( 'Chat', 'kitify' ),
					'userinfo' => esc_html__( 'Show Profile', 'kitify' ),
					'add' => esc_html__( 'Add to Contacts', 'kitify' ),
					'voicemail' => esc_html__( 'Send Voice Mail', 'kitify' ),
				],
				'default' => 'call',
				'condition' => [
					'link_type' => 'skype',
				],
			]
		);

		$this->add_control(
			'waze_address',
			[
				'label' => esc_html__( 'Location', 'kitify' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => 'true',
				'condition' => [
					'link_type' => 'waze',
				],
			]
		);

		$this->add_control(
			'event_title',
			[
				'label' => esc_html__( 'Title', 'kitify' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => 'true',
				'condition' => [
					'link_type' => [
						'google_calendar',
						'outlook_calendar',
						'yahoo_calendar',
					],
				],
			]
		);

		$this->add_control(
			'event_description',
			[
				'label' => esc_html__( 'Description', 'kitify' ),
				'type' => Controls_Manager::TEXTAREA,
				'condition' => [
					'link_type' => [
						'google_calendar',
						'outlook_calendar',
						'yahoo_calendar',
					],
				],
			]
		);

		$this->add_control(
			'event_location',
			[
				'label' => esc_html__( 'Location', 'kitify' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => 'true',
				'condition' => [
					'link_type' => [
						'google_calendar',
						'outlook_calendar',
						'yahoo_calendar',
					],
				],
			]
		);

		$this->add_control(
			'event_start_date',
			[
				'label' => esc_html__( 'Start', 'kitify' ),
				'type' => Controls_Manager::DATE_TIME,
				'condition' => [
					'link_type' => [
						'google_calendar',
						'outlook_calendar',
						'yahoo_calendar',
					],
				],
			]
		);

		$this->add_control(
			'event_end_date',
			[
				'label' => esc_html__( 'End', 'kitify' ),
				'type' => Controls_Manager::DATE_TIME,
				'condition' => [
					'link_type' => [
						'google_calendar',
						'outlook_calendar',
						'yahoo_calendar',
					],
				],
			]
		);
	}

	protected function register_advanced_section() {}

	private function build_mail_to_link( $settings ) {
		if ( empty( $settings['mail_to'] ) ) {
			return '';
		}

		$link = 'mailto:' . $settings['mail_to'] . '?';

		$build_parts = [];

		if ( ! empty( $settings['mail_subject'] ) ) {
			$build_parts['subject'] = $this->escape_space_in_url( $settings['mail_subject'] );
		}

		if ( ! empty( $settings['mail_body'] ) ) {
			$build_parts['body'] = $this->escape_space_in_url( $settings['mail_body'] );
		}

		return add_query_arg( $build_parts, $link );
	}

	private function build_sms_link( $settings ) {
		if ( empty( $settings['tel_number'] ) ) {
			return '';
		}

		$value = 'sms:' . $settings['tel_number'];
		return $value;
	}

	private function build_whatsapp_link( $settings ) {
		if ( empty( $settings['tel_number'] ) ) {
			return '';
		}

		return 'https://api.whatsapp.com/send?phone=' . $settings['tel_number'];
	}

	private function build_skype_link( $settings ) {
		if ( empty( $settings['username'] ) ) {
			return '';
		}

		$action = 'call';
		if ( ! empty( $settings['skype_action'] ) ) {
			$action = $settings['skype_action'];
		}
		$link = 'skype:' . $settings['username'] . '?' . $action;
		return $link;
	}

	private function build_waze_link( $settings ) {
		$link = 'https://waze.com/ul?';

		$build_parts = [
			'q' => $settings['waze_address'],
			'z' => 10,
			'navigate' => 'yes',
		];

		return add_query_arg( $build_parts, $link );
	}

	private function date_to_iso( $date, $all_day = false ) {
		$time = strtotime( $date );

		if ( $all_day ) {
			return gmdate( 'Ymd\/Ymd', $time );
		}

		return gmdate( 'Ymd\THis', $time );
	}

	private function date_to_ics( $date ) {
		$time = strtotime( $date );
		return gmdate( 'Y-m-d\Th:i:s', $time );
	}

	private function escape_space_in_url( $url ) {
		return str_replace( ' ', '%20', $url );
	}

	private function build_google_calendar_link( $settings ) {
		$dates = '';
		if ( ! empty( $settings['event_start_date'] ) ) {
			if ( empty( $settings['event_end_date'] ) ) {
				$dates = $this->date_to_iso( $settings['event_start_date'], true );
			} else {
				$dates = $this->date_to_iso( $settings['event_start_date'] ) . '/' . $this->date_to_iso( $settings['event_end_date'] );
			}
		}
		$link = 'https://www.google.com/calendar/render?action=TEMPLATE&';
		$build_parts = [
			'text' => empty( $settings['event_title'] ) ? '' : $this->escape_space_in_url( $settings['event_title'] ),
			'details' => empty( $settings['event_description'] ) ? '' : $this->escape_space_in_url( $settings['event_description'] ),
			'dates' => $dates,
			'location' => empty( $settings['event_location'] ) ? '' : $this->escape_space_in_url( $settings['event_location'] ),
		];

		return add_query_arg( $build_parts, $link );
	}

	private function build_outlook_calendar_link( $settings ) {
		$link = 'https://outlook.office.com/owa/?path=/calendar/action/compose&';
		$build_parts = [
			'subject' => empty( $settings['event_title'] ) ? '' : urlencode( $settings['event_title'] ),
			'body' => empty( $settings['event_description'] ) ? '' : urlencode( $settings['event_description'] ),
			'location' => empty( $settings['event_location'] ) ? '' : urlencode( $settings['event_location'] ),
		];

		if ( ! empty( $settings['event_start_date'] ) ) {
			$build_parts['startdt'] = urlencode( $this->date_to_ics( $settings['event_start_date'] ) );
		}

		if ( ! empty( $settings['event_end_date'] ) ) {
			$build_parts['enddt'] = urlencode( $this->date_to_ics( $settings['event_end_date'] ) );
		}

		return add_query_arg( $build_parts, $link );
	}

	private function build_messenger_link( $settings ) {
		if ( empty( $settings['username'] ) ) {
			return '';
		}
		return 'https://m.me/' . $settings['username'];
	}

	private function build_yahoo_calendar_link( $settings ) {
		$link = 'https://calendar.yahoo.com/?v=60&view=d&type=20';
		$build_parts = [
			'title' => empty( $settings['event_title'] ) ? '' : urlencode( $settings['event_title'] ),
			'desc' => empty( $settings['event_description'] ) ? '' : urlencode( $settings['event_description'] ),
			'in_loc' => empty( $settings['event_location'] ) ? '' : urlencode( $settings['event_location'] ),
		];

		if ( ! empty( $settings['event_start_date'] ) ) {
			$build_parts['st'] = urlencode( gmdate( 'Ymd\This', strtotime( $settings['event_start_date'] ) ) );
		}

		if ( ! empty( $settings['event_end_date'] ) ) {
			$build_parts['et'] = urlencode( gmdate( 'Ymd\This', strtotime( $settings['event_end_date'] ) ) );
		}

		return add_query_arg( $build_parts, $link );
	}

	public function build_viber_link( $settings ) {
		if ( empty( $settings['tel_number'] ) ) {
			return '';
		}
		$action = 'contact';
		if ( ! empty( $settings['viber_action'] ) ) {
			$action = $settings['viber_action'];
		}
		return add_query_arg( [
			'number' => urlencode( $settings['tel_number'] ),
		], 'viber://' . $action );
	}

	public function render() {
		$settings = $this->get_settings();

		if ( empty( $settings['link_type'] ) ) {
			return '';
		}

		$value = '';
		switch ( $settings['link_type'] ) {
			case 'email':
				$value = $this->build_mail_to_link( $settings );
				break;
			case 'tel':
				$value = ( empty( $settings['tel_number'] ) ? '' : 'tel:' . $settings['tel_number'] );
				break;
			case 'sms':
				$value = $this->build_sms_link( $settings );
				break;
			case 'messenger':
				$value = $this->build_messenger_link( $settings );
				break;
			case 'whatsapp':
				$value = $this->build_whatsapp_link( $settings );
				break;
			case 'skype':
				$value = $this->build_skype_link( $settings );
				break;
			case 'waze':
				$value = $this->build_waze_link( $settings );
				break;
			case 'google_calendar':
				$value = $this->build_google_calendar_link( $settings );
				break;
			case 'outlook_calendar':
				$value = $this->build_outlook_calendar_link( $settings );
				break;
			case 'yahoo_calendar':
				$value = $this->build_yahoo_calendar_link( $settings );
				break;
			case 'viber':
				$value = $this->build_viber_link( $settings );
				break;
		}
		echo esc_html( $value );
	}
}

