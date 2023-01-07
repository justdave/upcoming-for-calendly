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


function _calendly_upcoming_api_call( $path, $params = NULL ) {
    $service_url = 'https://api.calendly.com/' . $path;
    if ($params) {
        $service_url .= "?" . http_build_query($params);
    }
    $curl = curl_init($service_url);
    $curl_headers = [
        'Authorization: Bearer ' . get_option("calendly_upcoming_apikey"),
        'Content-Type: application/json',
    ];
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $curl_headers,
    ]);
    $curl_response = curl_exec($curl);
    if (curl_errno($curl)) {
        error_log("curl returned " . curl_errno($curl) . " " . curl_error($curl));
    }
    curl_close($curl);
    return json_decode($curl_response);
}

function _calendly_upcoming_get_event_availability_info( $event_type, $start_time ) {
    $data = _calendly_upcoming_api_call('event_type_available_times', [
        'event_type' => $event_type,
        'start_time' => $start_time,
        'end_time'   => $start_time,
    ]);
    return $data->collection[0];
}
