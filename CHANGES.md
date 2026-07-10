#### 2.0.1 / 2026-07-10

* The changelog was accidentally removed from the distribution. It has been restored.

#### 2.0 / 2026-07-10

* New features:
  * There is now a Gutenberg block in the editor as an alternate way to add the events to your page. This gives you a dropdown and switches to use instead of needing to remember the event names and shortcode parameters. See the help at the bottom of the settings page if you need help finding it.
  * Since Calendly doesn't expose the "Display remaining spots on booking page" setting via the API, you can now add `show_spots="false"` to the shortcode to hide the available spots.
  * You can now add `members_only_links="true"` to the shortcode to only show registration links to logged-in members. Logged-out visitors will still see the event dates, but they will not be clickable. 
* Bug fixes:
  * A recent API change caused us to fail to load event availability and list all sessions as full even if they still had open spots. We now correctly show available spots again.
* Under the hood:
  * Lots of code reoganization to make future development easier
  * Calendly API lookup results are now cached for up to 5 minutes to reduce API request volume on busy pages. There's a button on the settings page in case you need to clear it sooner.

#### 1.2.7 / 2026-07-07

* Tested with WordPress 7.0
* Reassigned copyright to Justdave IT Consulting LLC, which is still me, it's just the business I own.

#### 1.2.6 / 2025-12-09

* properly remove some IDE droppings from the generated SVN commit
  to wordpress.org

#### 1.2.5 / 2025-12-09

* Security fix for CVE-2025-14160: Add nonce verification on the
  settings form to prevent CSRF when updating the Calendly Access
  Token
* Lots of code cleanup

#### 1.2.4 / 2025-04-21

* Update compatibility to show it has been tested and still works
  with current WordPress

#### 1.2.3 / 2023-04-18

* Make the description easier to read on wordpress.org

#### 1.2.2 / 2023-04-18

* Corrections to WP.org deployment process

#### 1.2.1 / 2023-04-18

* Autodeploy to WordPress Plugin Directory on new release

#### 1.2 / 2023-04-15

* Use WP HTTP API instead of the PHP Curl library
* Sanitize and escape, and use WP functions for it instead of
  built-in PHP functions
* Don't use underscore prefixes on functions that aren't in a
  class

#### 1.1.1 / 2023-01-12

* structured plugin zip file for wordpress.org plugin directory

#### 1.1 / 2023-01-09

* add a 'Settings' link on the Plugins page
* validate the Access Token before saving it on settings page
* don't hit the Calendly API on the settings page if there
  isn't an Access Token yet
* filter events before counting them, so "no events scheduled"
  message will still appear when other event types have events

#### 1.0.1 / 2023-01-07

* Display a message in shortcode output if there are no events to show

#### 1.0 / 2023-01-07

* Initial release.
