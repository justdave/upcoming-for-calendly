<?php
/*
 * Plugin Name: Upcoming for Calendly
 * Plugin URI: https://github.com/justdave/upcoming-for-calendly
 * Description: Upcoming Events Registration List for Calendly
 * Version: 1.2.6
 * Requires PHP: 7.2
 * Requires at least: 5.8
 * Tested up to: 6.9
 * Author: David D. Miller
 * Author URI: https://github.com/justdave
 * Author Email: github@justdave.net
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * GitHub Plugin URI: https://github.com/justdave/upcoming-for-calendly
 * Primary Branch: main
 * Release Asset: true
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