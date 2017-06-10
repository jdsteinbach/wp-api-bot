# WP API Bot 🤖

Sometimes you need 3rd party API data but they won't let you hit their endpoints because CORs. This plugin provides an abstraction around PHP cURL and makes API calls available to both PHP & JS (via WP `admin-ajax.php`).

## What the plugin does

This plugin does nothing by itself. It just provides a framework & you'll need to extend its classes for your own need.

You'll need to write your own extensions (in theme functions or a plugin of your own) to set up the endpoints & functions you need.

Examples & documentation coming soon.

## But the REST API…

Yeah, sorry. For now, `admin-ajax.php` is quicker and useful across more WP installs. Although, c'mon. If your WP install isn't updated, hurry up and do that.

## Next steps

* [ ] Documentation & examples
* [ ] Possibly replace cURL with `HttpRequest`
* [ ] Maybe an admin panel for instantiating new classes w/o writing code