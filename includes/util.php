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


function _uefc_api_call( $path, $params = NULL, $apikey = NULL ) {
    $service_url = 'https://api.calendly.com/' . $path;
    if ($params) {
        $service_url .= "?" . http_build_query($params);
    }
    if (!$apikey) {
        $apikey = get_option("uefc_apikey");
    }
    $wpget_headers = [
        'Authorization' => 'Bearer ' . $apikey,
        'Content-Type'  => 'application/json',
    ];
    $wpget_args = [
        'headers' => $wpget_headers,
    ];
    $wpget_response = wp_remote_get($service_url, $wpget_args);
    $wpget_responsecode = wp_remote_retrieve_response_code($wpget_response);
    if ($wpget_responsecode != '200') {
        error_log("HTTP GET returned " . $wpget_responsecode . " " . $wpget_response['response']['message']);
    }
    return json_decode($wpget_response['body']);
}

function _uefc_get_event_availability_info( $event_type, $start_time ) {
    $data = _uefc_api_call('event_type_available_times', [
        'event_type' => $event_type,
        'start_time' => $start_time,
        'end_time'   => $start_time,
    ]);
    return $data->collection[0];
}
