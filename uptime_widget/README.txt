Name: Uptime (uptime)
Author: Martin Postma ('lolandese', http://drupal.org/user/210402)
Drupal: 8.x


-- SUMMARY --

A block showing the uptime ratio of the 
site (e.g. 99,87%).

It uses the service from UptimeRobot.com:
"Monitors your websites every 5 minutes, totally free.
Get alerts by e-mail, SMS, Twitter, RSS or push notifications for iPhone/iPad."


-- INSTALL --

Extract the package in your modules directory, '/modules'.

Enable the module at '/config'/admin/modules'.


-- CONFIGURE --

Configuration at 'admin/config/system/uptime_widget/settings' and
'admin/structure/block'.


-- CUSTOMIZE --

To change the content in the widget (e.g. to put the ratio first):
1. Copy the uptime_widget.html.twig file to your theme's template folder.
2. Make your changes.
3. Clear the site cache at 'admin/config/development/performance'.

To change the style of the widget (e.g. colors):
1. Copy-paste the code in uptime_widget.css into your theme's custom CSS file.
2. Make your changes.
3. Clear both your browser and site cache.
