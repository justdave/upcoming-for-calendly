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
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    ?>
<h1>Upcoming Events for Calendly Settings</h1>
    <?php
    $hidden_field_name = 'uefc_hidden';
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'uefc_settings') {
        // process form code here

        # This is coming out of a textarea field, but we only used that for
        # word wrapping purposes since it's a LONG string. It shouldn't have
        # any spaces or linefeeds in it, so we'll sanitize it as a text field.
        $uefc_apikey = trim(sanitize_text_field($_POST['uefc_apikey']));
        # Aside from the above, the api key could be pretty much anything. The
        # following API call will have Calendly validate the key for us. We
        # don't write it to the database until they say it's good.
        if ($uefc_apikey) {
            $data = uefc_api_call('users/me', [], $uefc_apikey);
            if (property_exists($data, 'message')) {
                ?><div class="notice notice-error settings-error"><p><strong>The Access Token you supplied is not valid. It was not saved.</strong></p></div><?php
            } else {
                update_option('uefc_apikey', $uefc_apikey);
                ?><div class="updated"><p><strong>Access Token successfully updated.</div><?php
            }
        }

    }
    if (get_option("uefc_apikey")) {
        $data = uefc_api_call('users/me');
        if (property_exists($data, 'message')) {
            ?><div class="notice notice-error settings-error"><p><strong>Your current Access Token is not valid.</strong></p></div><?php
        } else {
            ?><div class="updated"><p>Your current Access Token is valid. You are logged in as:<br>
            <img src="<?php echo esc_url($data->resource->avatar_url); ?>" height="40" style="vertical-align: middle;"><span style="font-size: x-large;"><?php echo esc_html($data->resource->name); ?></span></p></div><?php
        }
    }
    ?>
<form name="upcoming-for-calendly-settings" method="post" action="">
<input type="hidden" name="<?php echo esc_attr($hidden_field_name); ?>" value="uefc_settings">
<table class="form-table">
<tbody>
<tr>
  <th scope="row"><label for="uefc_apikey">Calendly Access Token</label></th>
  <td><?php
    if (get_option("uefc_apikey")) {
       ?>For security reasons your existing Access Token is not shown here. To change it, paste a new one below.<br><?php
    }
  ?><textarea id="uefc_apikey" name="uefc_apikey" class="regular-text code" rows="3"></textarea>
  <p class="description">Generate an Access Token on <a href="https://calendly.com/integrations/api_webhooks" target="_blank" class="external">this page</a>, then enter it here.</p>
  </td>
</tr>
</tbody>
</table>
<p class="submit"><input id="submit" class="button button-primary" type="submit" value="Save Changes" name="submit"></p>
</form>
<p>To place a list of your upcoming events that have already been scheduled into a post or page, use the shortcode <code>[upcoming-for-calendly]</code>. To restict it to a specific event type, pass the title of the event (must be an exact match) like so: <code>[upcoming-for-calendly event="Event Name"]</code>.</p>
<?php
    echo "</div>";
}

add_filter( 'plugin_action_links', 'uefc_settings_link', 10, 2 );
function uefc_settings_link( $links, $file ) {
    if ($file == 'upcoming-for-calendly/upcoming-for-calendly.php') {
        $url = add_query_arg( 'page', 'uefc-settings', get_admin_url() . 'options-general.php' );
        $settings_link = '<a href="' . esc_url($url) . '">Settings</a>';
        array_push( $links, $settings_link );
    }
    return $links;
}
