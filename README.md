osFighter
=========
[](http://www.ultimate-survival.net/views/FrenzoTheme/images/layout/logo.png)

:octocat: &nbsp;**Live Demo**: http://www.ultimate-survival.net/

Currently under development. Theme(s) by Frenzo Brouwer.

osFighter is an OS RPG. This RPG has loads of settings to fine tune.

<h2>Features</h2>

Most important features:
- Settings
 - Create/manage pages
 - Manage menu's
 - Edit/ban users
 - Ban ip's
 - Manage city's and rank's
 - themes
- PayPal integration
- Very safe login/registration system

<h2>Setup</h2>
1. Create a database
2. Set your database instance in '<i>core/constants.php</i>'
3. Upload the db_dump.sql into your database
4. Create an administrator account by signing up as 'admin'
5. Application is ready to go

<h3>PayPal integration</h3>

To use PayPal you need to have a developer account (https://developer.paypal.com/), then you need to create a new application (https://developer.paypal.com/webapps/developer/applications/createapp) and if you're done with that you need to save the Client ID and Secret ID to the database. You can fine the right fields in: <i>(your database) > configuration > PAYPAL_CLIENT_ID / PAYPAL_SECRET_ID</i>. If you follow the steps right you can now use PayPal on the application.