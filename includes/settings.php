<?php
/*
 * Copyright (C) 2023 David D. Miller
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

add_action( 'admin_menu', 'uefc_settings_menu' );
function uefc_settings_menu() {
    add_options_page('Upcoming Events for Calendly', 'Upcoming Events for Calendly', 'manage_options', 'uefc-settings', 'uefc_options' );
}

function uefc_options() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'upcoming-for-calendly' ) );
    }
    ?>
<div class="wrap">
<h1><?php echo esc_html__( 'Upcoming Events for Calendly Settings', 'upcoming-for-calendly' ); ?></h1>
    <?php
    $hidden_field_name = 'uefc_hidden';
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'uefc_settings') {
        // Verify intent to prevent CSRF on settings updates.
        check_admin_referer('uefc_settings_action', 'uefc_settings_nonce');

        // process form code here

        # This is coming out of a textarea field, but we only used that for
        # word wrapping purposes since it's a LONG string. It shouldn't have
        # any spaces or linefeeds in it, so we'll sanitize it as a text field.
        $uefc_apikey = isset($_POST['uefc_apikey']) ? trim(sanitize_text_field(wp_unslash($_POST['uefc_apikey']))) : '';
        # Aside from the above, the api key could be pretty much anything. The
        # following API call will have Calendly validate the key for us. We
        # don't write it to the database until they say it's good.
        if ($uefc_apikey) {
            $data = uefc_api_call('users/me', [], $uefc_apikey);
            if (property_exists($data, 'message')) {
                ?><div class="notice notice-error settings-error"><p><strong><?php esc_html_e( 'The Access Token you supplied is not valid. It was not saved.', 'upcoming-for-calendly' ); ?></strong></p></div><?php
            } else {
                update_option('uefc_apikey', $uefc_apikey);
                ?><div class="updated"><p><strong><?php esc_html_e( 'Access Token successfully updated.', 'upcoming-for-calendly' ); ?></strong></p></div><?php
            }
        }

    }
    if (get_option("uefc_apikey")) {
        $data = uefc_api_call('users/me');
        if (property_exists($data, 'message')) {
            ?><div class="notice notice-error settings-error"><p><strong><?php esc_html_e( 'Your current Access Token is not valid.', 'upcoming-for-calendly' ); ?></strong></p></div><?php
        } else {
            ?><div class="updated"><p><?php esc_html_e( 'Your current Access Token is valid. You are logged in as:', 'upcoming-for-calendly' ); ?><br>
            <img src="<?php echo esc_url($data->resource->avatar_url); ?>" height="40" style="vertical-align: middle;"><span style="font-size: x-large;"><?php echo esc_html($data->resource->name); ?></span></p></div><?php
        }
    }
    ?>
<form name="upcoming-for-calendly-settings" method="post" action="">
<input type="hidden" name="<?php echo esc_attr($hidden_field_name); ?>" value="uefc_settings">
<?php wp_nonce_field('uefc_settings_action', 'uefc_settings_nonce'); ?>
<table class="form-table">
<tbody>
<tr>
  <th scope="row"><label for="uefc_apikey"><?php esc_html_e( 'Calendly Access Token', 'upcoming-for-calendly' ); ?></label></th>
  <td><?php
    if (get_option("uefc_apikey")) {
       ?><?php esc_html_e( 'For security reasons your existing Access Token is not shown here. To change it, paste a new one below.', 'upcoming-for-calendly' ); ?><br><?php
    }
  ?><textarea id="uefc_apikey" name="uefc_apikey" class="regular-text code" rows="3"></textarea>
  <p class="description"><?php esc_html_e( 'Generate an Access Token on', 'upcoming-for-calendly' ); ?> <a href="https://calendly.com/integrations/api_webhooks" target="_blank" class="external"><?php esc_html_e( 'this page', 'upcoming-for-calendly' ); ?></a>, <?php esc_html_e( 'then enter it here.', 'upcoming-for-calendly' ); ?></p>
  </td>
</tr>
</tbody>
</table>
<p class="submit"><input id="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save Changes', 'upcoming-for-calendly' ); ?>" name="submit"></p>
</form>
<p><?php esc_html_e( 'To place a list of your upcoming events that have already been scheduled into a post or page, use the shortcode', 'upcoming-for-calendly' ); ?> <code>[upcoming-for-calendly]</code>. <?php esc_html_e( 'To restrict it to a specific event type, pass the title of the event (must be an exact match) like so:', 'upcoming-for-calendly' ); ?> <code>[upcoming-for-calendly event="Event Name"]</code>.</p>
<?php
    echo "</div>";
}

add_filter( 'plugin_action_links', 'uefc_settings_link', 10, 2 );
function uefc_settings_link( $links, $file ) {
    if ( 'upcoming-for-calendly/upcoming-for-calendly.php' === $file ) {
        $url = add_query_arg( 'page', 'uefc-settings', get_admin_url() . 'options-general.php' );
        $settings_link = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'upcoming-for-calendly' ) . '</a>';
        array_push( $links, $settings_link );
    }
    return $links;
}
