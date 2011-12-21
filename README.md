#JB Shortener

Changes the Short URL and the Twitter Tools URL for each post on your site using a custom, shortened domain and a base-36 encode of the post ID.

This plugin modifies the default short-url functionality to work with a custom shortened domain that you own. It works fully with Twitter Tools, a popular plugin for automatically tweeting about new posts. If you have a twitter plugin that you think we should support, let us know. For the nerds: we're doing a base-36 encode of the post ID as the short-url for each post.

*Note*: This plugin no longer requires external hosting of shortening scripts unless you're in a multisite environment.

#Setup

1. Buy a short domain
2. Upload `jb-shortener.php` to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Update the "Short URL" in Options->General
