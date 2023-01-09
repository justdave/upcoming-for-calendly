#!/bin/bash

#
# Copyright (C) 2023 David D. Miller
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
#

# This script grabs data from various places in the GitHub structure
# and outputs a wordpress.org compatible readme.txt file

if [ ! -e "README.md" ]; then
    echo "Not running in correct directory. pwd=${PWD}" >&2
    exit -1
fi
# make assets directory visible
if [ -d ".wordpress-org" ]; then
    cp -r .wordpress-org/ assets
fi
# parse the main plugin file for metadata
CODEHEADER=`head -20 upcoming-for-calendly.php`
PLUGINNAME=`echo "$CODEHEADER" | grep -E '^ \* Plugin Name:' | sed -e 's/.*Plugin Name: //'`
REQUIRESWP=`echo "$CODEHEADER" | grep -E '^ \* Requires at least:' | sed -e 's/.*Requires at least: //'`
TESTED=`echo "$CODEHEADER" | grep -E '^ \* Tested up to:' | sed -e 's/.*Tested up to: //'`
VERSION=`echo "$CODEHEADER" | grep -E '^ \* Version:' | sed -e 's/.*Version: //'`
REQUIRESPHP=`echo "$CODEHEADER" | grep -E '^ \* Requires PHP:' | sed -e 's/.*Requires PHP: //'`
# write out the readme.txt
cat .github/wptemplates/readme-header.txt | \
    sed -e "s/%%pluginname%%/${PLUGINNAME}/" | \
    sed -e "s/%%requireswp%%/${REQUIRESWP}/" | \
    sed -e "s/%%tested%%/${TESTED}/" | \
    sed -e "s/%%version%%/${VERSION}/" | \
    sed -e "s/%%requiresphp%%/${REQUIRESPHP}/" \
    > readme.txt
echo >> readme.txt
echo "== Description ==" >> readme.txt
echo >> readme.txt
cat README.md >> readme.txt
echo >> readme.txt
cat .github/wptemplates/screenshots.txt >> readme.txt
echo >> readme.txt
echo "== Changelog ==" >> readme.txt
cat CHANGES.md >> readme.txt
