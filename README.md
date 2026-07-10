# Upcoming Events Registration List for Calendly

This WordPress plugin displays upcoming scheduled events from Calendly using the Calendly API and offers registration links for already-scheduled group events that still have open spots. You can either list all of your scheduled events, or restrict it to a specific event type.

## Setup

To set it up, you will need to log into Calendly and generate an Access Token. A link to do so is provided on the plugin's settings page. Paste the token into the box on the settings page.

## Usage

### Gutenberg Block

The easiest way to add your upcoming events to a post or page is to use the Gutenberg block. When editing a post or page, search for "Upcoming for Calendly" in the block inserter and add it. You can then configure:

- **Event name filter** - Select a specific event type from your Calendly account, or leave as "All Events" to show all scheduled events.
- **Show remaining spots** - Toggle to show or hide the remaining available spots for each event.
- **Members-only booking links** - When enabled, only logged-in users will see clickable booking links. Logged-out visitors will still see event dates as plain text.

### Shortcode (Legacy)

You can also use the shortcode for backward compatibility. Place `[upcoming-for-calendly]` on a post or page where you want the list to appear. To restrict it to a specific event type, use `[upcoming-for-calendly event="Event Type Name"]`.

Calendly has a per-event setting in their UI to hide the available spots on an event, but they don't expose it in the API so the plugin has no way to tell if you have it set. If you want to hide the remaining spot counts, you can add `show_spots="false"` to the shortcode to hide them. For example: `[upcoming-for-calendly show_spots="false"]`.

If you only want booking links to be clickable for logged-in users, add `members_only_links="true"` to the shortcode. Logged-out visitors will still see the event date/time list, but as plain text with no booking URL. For example: `[upcoming-for-calendly members_only_links="true"]`.

The plugin currently implements a feature I needed. I am open to adding additional features that can be implemented via Calendly's API if there is a need for them.

Bug reports and feature requests can be filed at the [GitHub repository](https://github.com/justdave/upcoming-for-calendly/issues).
