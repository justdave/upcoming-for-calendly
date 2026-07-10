<?php
/**
 * Plugin Name: Upcoming for Calendly
 * Plugin URI: https://github.com/justdave/upcoming-for-calendly
 * Description: Upcoming Events Registration List for Calendly
 * Version: 2.0
 * Requires PHP: 8.0
 * Requires at least: 5.8
 * Tested up to: 7.0
 * Author: Justdave IT Consulting LLC
 * Author URI: https://justdaveitconsulting.com
 * Author Email: github@justdave.net
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * GitHub Plugin URI: https://github.com/justdave/upcoming-for-calendly
 * Primary Branch: main
 * Release Asset: true
 *
 * @package Upcoming_For_Calendly
 */

/*
 * Copyright (C) 2023 Justdave IT Consulting LLC
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

namespace JDITC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/classes/class-upcomingforcalendly.php';

new Upcoming_For_Calendly\UpcomingForCalendly();
