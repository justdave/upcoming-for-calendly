<?php
/*
 * Plugin Name: Calendly Upcoming Events
 * Plugin URI: https://github.com/justdave/calendly-upcoming
 * Description: Wordpress plugin to house custom stuff for this website
 * Version: 1.0
 * Author: David Miller
 * Author URI: https://github.com/justdave
 * Author Email: github@justdave.net
 * */

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

// All of the meat is in the includes directory, to keep it organized.
// Just pull it all in from here.
require_once("includes/util.php");
require_once("includes/settings.php");
require_once("includes/shortcode.php");
