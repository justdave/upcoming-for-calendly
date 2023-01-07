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

add_shortcode( 'calendly_upcoming', 'calendly_upcoming_shortcode' );
function calendly_upcoming_shortcode ( $atts = [], $content = null, $tag = '' ) {
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );
    $attr =  shortcode_atts([
            'event' => '',
        ], $atts, $tag
	);
    $curdate = date_create();
    $curdate_string = date_format($curdate, DATE_ISO8601);
    $user = _calendly_upcoming_api_call('users/me');
    if (property_exists($user, 'message')) {
        return "[Calendly Access Token is invalid. Please contact the site administrator.]";
    }
    $data = _calendly_upcoming_api_call('scheduled_events', [
        'user' => $user->resource->uri,
        'status' => 'active',
        'min_start_time' => $curdate_string,
    ]);
    $event_list = $data->collection;
    $output = '<div class="calendly_upcoming_event_list"><ul>';
    foreach ($event_list as $event) {
        $event_date = date_create($event->start_time);
        $event_date->setTimeZone(wp_timezone());
        $event_date_string = date_format($event_date, 'l, F j, Y - g:i a');
        $avail_info = _calendly_upcoming_get_event_availability_info($event->event_type, $event->start_time);
        $slots = $avail_info->invitees_remaining;
        $slots_string = 'full';
        if ($slots == 1) {
            $slots_string = $slots . ' slot remaining';
        } else if ($slots > 1) {
            $slots_string = $slots . ' slots remaining';
        }
        if (($attr['event'] === '') || ($event->name == $attr['event'])) {
            $output .= '<li class="calendly_upcoming_event">' .
                '<a href="' . $avail_info->scheduling_url . '" target="_blank">' .
                htmlspecialchars($event_date_string) .
                '</a>' .
                ' (' . htmlspecialchars($slots_string) . ')' .
                '</li>';
        }
    }
    $output .= '</ol></div>';
    return $output;
}
