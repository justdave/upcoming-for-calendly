#### 1.2 / 2023-04-15

* Use WP HTTP API instead of the PHP Curl library
* Sanitize and escape, and use WP functions for it instead of built-in PHP functions
* Don't use underscore prefixes on functions that aren't in a class

#### 1.1.1 / 2023-01-12

* structured plugin zip file for wordpress.org plugin directory

#### 1.1 / 2023-01-09

* add a 'Settings' link on the Plugins page
* validate the Access Token before saving it on settings page
* don't hit the Calendly API on the settings page if there isn't an Access Token yet
* filter events before counting them, so "no events scheduled" message will still appear when other event types have events

#### 1.0.1 / 2023-01-07

* Display a message in shortcode output if there are no events to show

#### 1.0 / 2023-01-07

* Initial release.
