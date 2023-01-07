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

add_action( 'admin_menu', 'calendly_upcoming_settings_menu' );
function calendly_upcoming_settings_menu() {
    add_options_page('Calendly Upcoming', 'Calendly Upcoming', 'manage_options', 'calendly_upcoming', 'calendly_upcoming_options' );
}

function calendly_upcoming_options() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    $hidden_field_name = 'calendly_upcoming_hidden';
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'calendly_upcoming_settings') {
        // process form code here
        $foundchanges = false;

        $calendly_upcoming_apikey = trim($_POST['calendly_upcoming_apikey']);
        if ($calendly_upcoming_apikey) {
            update_option('calendly_upcoming_apikey', $calendly_upcoming_apikey);
            $foundchanges = true;
        }

        if ($foundchanges) {
            ?><div class="updated"><p><strong>Changes saved.</strong></p></div><?php
        }
    }
    ?>
<h3>Calendly Upcoming Settings</h3>
    <?php
    $data = _calendly_upcoming_api_call('users/me');
    if (property_exists($data, 'message')) {
        ?><div class="error">Your current Access Token is not valid.</div><?php
    } else {
        ?><div class="updated">Your current Access Token is valid. You are logged in as:<br>
        <img src="<?php echo htmlspecialchars($data->resource->avatar_url); ?>" height="40" style="vertical-align: middle;"><span style="font-size: x-large;"><?php echo htmlspecialchars($data->resource->name); ?></span></div><?php
    }
    ?>
<form name="calendly-upcoming-settings" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="calendly_upcoming_settings">
<table class="form-table">
<tbody>
<tr>
  <th scope="row"><label for="calendly_upcoming_apikey">Calendly Access Token</label></th>
  <td><?php
    if (get_option("calendly_upcoming_apikey")) {
       ?>For security reasons your existing Access Token is not shown here. To change it, paste a new one below.<br><?php
    }
  ?><textarea id="calendly_upcoming_apikey" name="calendly_upcoming_apikey" class="regular-text code" rows="3"></textarea>
  <p class="description">Generate an Access Token on <a href="https://calendly.com/integrations/api_webhooks" target="_blank" class="external">this page</a>, then enter it here.</p>
  </td>
</tr>
</tbody>
</table>
<p class="submit"><input id="submit" class="button button-primary" type="submit" value="Save Changes" name="submit"></p>
</form>
<p>To place a list of your upcoming events that have already been scheduled into a post or page, use the shortcode <code>[calendly_upcoming]</code>. To restict it to a specific event type, pass the title of the event (must be an exact match) like so: <code>[calendly_upcoming event="Event Name"]</code>.</p>
<?php
    echo "</div>";
}
