<?php
/**
 * Upcoming for Calendly plugin class.
 *
 * @package JDITC\Upcoming_For_Calendly
 */

namespace JDITC\Upcoming_For_Calendly;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 */
class UpcomingForCalendly {
	/**
	 * Register plugin hooks.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'settings_menu' ) );
		add_filter( 'plugin_action_links', array( $this, 'settings_link' ), 10, 2 );
		add_shortcode( 'upcoming-for-calendly', array( $this, 'shortcode' ) );
	}

	/**
	 * Register the plugin settings page under Settings.
	 *
	 * @return void
	 */
	public function settings_menu() {
		add_options_page( 'Upcoming Events for Calendly', 'Upcoming Events for Calendly', 'manage_options', 'uefc-settings', array( $this, 'options' ) );
	}

	/**
	 * Render and process the plugin settings page.
	 *
	 * @return void
	 */
	public function options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'upcoming-for-calendly' ) );
		}
		?>
<div class="wrap">
<h1><?php echo esc_html__( 'Upcoming Events for Calendly Settings', 'upcoming-for-calendly' ); ?></h1>
		<?php
		$hidden_field_name = 'uefc_hidden';
		if ( isset( $_POST[ $hidden_field_name ] ) && 'uefc_settings' === $_POST[ $hidden_field_name ] ) {
			// Verify intent to prevent CSRF on settings updates.
			check_admin_referer( 'uefc_settings_action', 'uefc_settings_nonce' );

			// Process form code here.

			// This is coming out of a textarea field, but we only used that for
			// word wrapping purposes since it's a long string. It should not have
			// spaces or linefeeds in it, so sanitize it as a text field.
			$uefc_apikey = isset( $_POST['uefc_apikey'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['uefc_apikey'] ) ) ) : '';
			if ( $uefc_apikey ) {
				$data = $this->api_call( 'users/me', array(), $uefc_apikey );
				if ( property_exists( $data, 'message' ) ) {
					?>
					<div class="notice notice-error settings-error"><p><strong><?php esc_html_e( 'The Access Token you supplied is not valid. It was not saved.', 'upcoming-for-calendly' ); ?></strong></p></div>
					<?php
				} else {
					update_option( 'uefc_apikey', $uefc_apikey );
					?>
					<div class="updated"><p><strong><?php esc_html_e( 'Access Token successfully updated.', 'upcoming-for-calendly' ); ?></strong></p></div>
					<?php
				}
			}
		}
		if ( get_option( 'uefc_apikey' ) ) {
			$data = $this->api_call( 'users/me' );
			if ( property_exists( $data, 'message' ) ) {
				?>
				<div class="notice notice-error settings-error"><p><strong><?php esc_html_e( 'Your current Access Token is not valid.', 'upcoming-for-calendly' ); ?></strong></p></div>
				<?php
			} else {
				?>
				<div class="updated"><p><?php esc_html_e( 'Your current Access Token is valid. You are logged in as:', 'upcoming-for-calendly' ); ?><br>
				<img src="<?php echo esc_url( $data->resource->avatar_url ); ?>" height="40" style="vertical-align: middle;"><span style="font-size: x-large;"><?php echo esc_html( $data->resource->name ); ?></span></p></div>
				<?php
			}
		}
		?>
<form name="upcoming-for-calendly-settings" method="post" action="">
<input type="hidden" name="<?php echo esc_attr( $hidden_field_name ); ?>" value="uefc_settings">
		<?php wp_nonce_field( 'uefc_settings_action', 'uefc_settings_nonce' ); ?>
<table class="form-table">
<tbody>
<tr>
	<th scope="row"><label for="uefc_apikey"><?php esc_html_e( 'Calendly Access Token', 'upcoming-for-calendly' ); ?></label></th>
	<td>
		<?php
		if ( get_option( 'uefc_apikey' ) ) {
			?>
			<?php esc_html_e( 'For security reasons your existing Access Token is not shown here. To change it, paste a new one below.', 'upcoming-for-calendly' ); ?><br>
			<?php
		}
		?>
	<textarea id="uefc_apikey" name="uefc_apikey" class="regular-text code" rows="3"></textarea>
	<p class="description"><?php esc_html_e( 'Generate an Access Token on', 'upcoming-for-calendly' ); ?> <a href="https://calendly.com/integrations/api_webhooks" target="_blank" class="external"><?php esc_html_e( 'this page', 'upcoming-for-calendly' ); ?></a>, <?php esc_html_e( 'then enter it here.', 'upcoming-for-calendly' ); ?></p>
	</td>
</tr>
</tbody>
</table>
<p class="submit"><input id="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save Changes', 'upcoming-for-calendly' ); ?>" name="submit"></p>
</form>
<p><?php esc_html_e( 'To place a list of your upcoming events that have already been scheduled into a post or page, use the shortcode', 'upcoming-for-calendly' ); ?> <code>[upcoming-for-calendly]</code>. <?php esc_html_e( 'To restrict it to a specific event type, pass the title of the event (must be an exact match) like so:', 'upcoming-for-calendly' ); ?> <code>[upcoming-for-calendly event="Event Name"]</code>.</p>
		<?php
		echo '</div>';
	}

	/**
	 * Add a settings link on the plugins list row.
	 *
	 * @param array  $links Existing action links.
	 * @param string $file  Relative plugin file path.
	 *
	 * @return array
	 */
	public function settings_link( $links, $file ) {
		if ( 'upcoming-for-calendly/upcoming-for-calendly.php' === $file ) {
			$url           = add_query_arg( 'page', 'uefc-settings', get_admin_url() . 'options-general.php' );
			$settings_link = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'upcoming-for-calendly' ) . '</a>';
			array_push( $links, $settings_link );
		}
		return $links;
	}

	/**
	 * Render the shortcode output.
	 *
	 * @param array       $atts    Shortcode attributes.
	 * @param string|null $content Shortcode enclosed content.
	 * @param string      $tag     Shortcode tag name.
	 *
	 * @return string
	 */
	public function shortcode( $atts = array(), $content = null, $tag = '' ) {
		$atts           = array_change_key_case( (array) $atts, CASE_LOWER );
		$attr           = shortcode_atts(
			array(
				'event' => '',
			),
			$atts,
			$tag
		);
		$curdate        = date_create();
		$curdate_string = date_format( $curdate, DATE_ISO8601 );
		$user           = $this->api_call( 'users/me' );
		if ( property_exists( $user, 'message' ) ) {
			return '[' . esc_html__( 'Calendly Access Token is invalid. Please contact the site administrator.', 'upcoming-for-calendly' ) . ']';
		}
		$data       = $this->api_call(
			'scheduled_events',
			array(
				'user'           => $user->resource->uri,
				'status'         => 'active',
				'min_start_time' => $curdate_string,
			)
		);
		$event_list = array();
		$output     = '<div class="uefc_event_list"><ul>';
		foreach ( $data->collection as $event ) {
			if ( ( '' === $attr['event'] ) || ( $event->name === $attr['event'] ) ) {
				$event_list[] = $event;
			}
		}
		if ( 0 === count( $event_list ) ) {
			$output .= '<li class="uefc_event">No events currently scheduled.</li>';
		}
		foreach ( $event_list as $event ) {
			$event_date = date_create( $event->start_time );
			$event_date->setTimeZone( wp_timezone() );
			$event_date_string = date_format( $event_date, 'l, F j, Y - g:i a' );
			$avail_info        = $this->get_event_availability_info( $event->event_type, $event->start_time );
			$slots_string      = 'availability unavailable';
			if ( $avail_info && property_exists( $avail_info, 'invitees_remaining' ) ) {
				$slots = (int) $avail_info->invitees_remaining;
				if ( 1 === $slots ) {
					$slots_string = $slots . ' slot remaining';
				} elseif ( $slots > 1 ) {
					$slots_string = $slots . ' slots remaining';
				} else {
					$slots_string = 'full';
				}
			}

			if ( $avail_info && property_exists( $avail_info, 'scheduling_url' ) ) {
				$output .= '<li class="uefc_event">' .
					'<a href="' . esc_url( $avail_info->scheduling_url ) . '" target="_blank">' .
					esc_html( $event_date_string ) .
					'</a>' .
					' (' . esc_html( $slots_string ) . ')' .
					'</li>';
			} else {
				$output .= '<li class="uefc_event">' .
					esc_html( $event_date_string ) .
					' (' . esc_html( $slots_string ) . ')' .
					'</li>';
			}
		}
		$output .= '</ol></div>';
		return $output;
	}

	/**
	 * Perform an authenticated GET request to the Calendly API.
	 *
	 * @param string      $path   API path relative to the Calendly base URL.
	 * @param array|null  $params Optional query parameters.
	 * @param string|null $apikey Optional API key override.
	 *
	 * @return mixed
	 */
	private function api_call( $path, $params = null, $apikey = null ) {
		$service_url = 'https://api.calendly.com/' . $path;
		if ( $params ) {
			$service_url .= '?' . http_build_query( $params );
		}
		if ( ! $apikey ) {
			$apikey = get_option( 'uefc_apikey' );
		}
		$wpget_headers  = array(
			'Authorization' => 'Bearer ' . $apikey,
			'Content-Type'  => 'application/json',
		);
		$wpget_args     = array(
			'headers' => $wpget_headers,
		);
		$wpget_response = wp_remote_get( $service_url, $wpget_args );
		return json_decode( $wpget_response['body'] );
	}

	/**
	 * Retrieve availability data for a specific event slot.
	 *
	 * @param string $event_type Event type URI.
	 * @param string $start_time Event start time in ISO 8601 format.
	 *
	 * @return mixed
	 */
	private function get_event_availability_info( $event_type, $start_time ) {
		$end_time = date_create( $start_time );
		if ( ! $end_time ) {
			return null;
		}

		// Query a non-zero range to avoid empty results on exact boundary matching.
		$end_time->modify( '+1 minute' );
		$data = $this->api_call(
			'event_type_available_times',
			array(
				'event_type' => $event_type,
				'start_time' => $start_time,
				'end_time'   => date_format( $end_time, DATE_ATOM ),
			)
		);

		if ( ! property_exists( $data, 'collection' ) || ! is_array( $data->collection ) || empty( $data->collection ) ) {
			return null;
		}

		$start_timestamp = strtotime( $start_time );
		foreach ( $data->collection as $slot ) {
			if ( property_exists( $slot, 'start_time' ) && strtotime( $slot->start_time ) === $start_timestamp ) {
				return $slot;
			}
		}

		return $data->collection[0];
	}
}
