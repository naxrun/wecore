# Website Content Recommendation

Naxrun's Website Content Recommendation engine is free for anyone to use. We have developed it as a proof of concept, that illustrates how a website can display the most relevant content, based on a visitor's previous activity.

The only thing needed to run this is PHP 5.6 and MySQL 5.5.

### How it works

The engine is split into 2 main scripts:
* logger.php
* recommendation.php

The logger.php script is used to log every site interaction a site visitor has. It takes the following parameters:

* v_ip - The visitor's IP address (only IPv4 supported)
* v_ref - The visitor's referrer URL
* v_uid - Any unique visitor ID - eg. a fingerprint or ID stored in a cookie
* p_cat - Page category ID, which must be an integer
* p_url - Page URL

The logger.php script logs all these values in a MySQL database.

The other script - recommendation.php - provides a page category ID recommendation, based on either the visitor's IP address or the unique ID attached to the visitor. The script takes these parameters:

* v_ip - The visitor's IP address (only IPv4 supported)
* v_uid - Any unique visitor ID - eg. a fingerprint or ID stored in a cookie

All values are submitted using POST.

### Setup
The only thing needed to run this is PHP 5.6 and MySQL 5.5.

##### MySQL setup
Start by setting up the database. Use the 'setup.sql' to setup the database.

##### Web server setup
Download this repository to your web server. Replace the values in 'includes/c_db.php' with your database info.

##### Use the scripts
As mentioned, this is merely a proof of concept, so we haven't built any standardized way to interact with the scripts. You can use them both server-side and client-side. It's all up to you.
