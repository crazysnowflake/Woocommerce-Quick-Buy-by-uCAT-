=== uCAT - Woocommerce Quick Buy ===
Contributors: elenkadark
Donate link: http://ucat.biz/
Tags: quick buy, buy, quick, woocommerce, curency, exchange, swap, shop, landing
Requires at least: 4.4
Tested up to: 4.9.6
Stable tag: 1.0.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Reduces standard 4-stage purchasing to one click. One click adds the product basket and automatically switches to the payment page. Your visitors will buy your product directly without being lost between pages.

== Description ==
* Author: [uCAT](http://ucat.biz)
* Project URI: <http://ucat.biz/woocommerce-quick-buy/>
* License: GPL v3. See License below for copyright jots and tittles.

This plugin redirects users to your checkout immediately. You get simple shortcodes to add this button to your pages. Users have the option to change redirection location and show the quick buy button for only certain products.
The plugin is great for selling game currency. Also, it is possible to order the swap of products by sending a request to the email address.
Demo: <http://demo.ucat.biz/quick-buy/>

For displaying quick-buy boxes use shortcode `[quick-buy]`.
List of all attributes:
* `id` - product ids separated by comma
* `fields` - additional fields what would be added after price, and will be displayed in order
* `swaprate` - exchange rate
* `swap` - swap labels (radio inputs)
* `email` - email which receive notification

Example:
```
[quick-buy id="96,97" fields="Username, Email" swaprate="1:3" swap="07 Gold,RS3 Gold" email="admin@example.com" ]
```


== Installation ==

Installing "uCAT - Woocommerce Quick Buy" can be done either by searching for "uCAT - Woocommerce Quick Buy" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org
1. Upload the ZIP file through the 'Plugins > Add New > Upload' screen in your WordPress dashboard
1. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. screenshot-1


== Changelog ==

= 23/06/2018 - version 1.0.1 =
Plugin release. Operate all the basic functions.