emailsys apiv3-examples-php
===========================
A set of PHP examples for access to the emailsys API v3

# What does this repository provide?
This repository contains a set of examples for the usage of the emailsys API v3 using the 
[Guzzle HTTP Client](http://docs.guzzlephp.org/en/stable/) for [PHP](https://secure.php.net).

# How can I use it?

## Browse examples online
- Recipient management
    - Get all recipients from a recipientlist [show example](examples/simple/recipients/get-recipients.php)
    - Add a recipient to a recipientlist [show example](examples/simple/recipients/add-recipient.php)
- Blacklist management
    - Export entries from your blacklist to a CSV file [show example](examples/simple/blacklist/export-blacklist-csv.php)
    - Import blacklist from a CSV file to your account [show example](examples/simple/blacklist/import-blacklist-csv.php)
    
## Check out repository via git
```bash
git checkout https://github.com/emailsys/apiv3-examples-php.git
```
You can then use the scripts in the repository with the php CLI.
```bash
php examples/simple/blacklist/export-blacklist-csv.php
``` 
:informational_source: Please note that you will have to configure your emailsys API credentials separately for each 
example.

:warning: Please make sure you use [composer](https://getcomposer.org) to install your vendor dependencies. Check the
[composer documentation](https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies) if you are unsure on 
how to do this. If you have not worked with composer before, please check the 
[composer Getting Started guide](https://getcomposer.org/doc/00-intro.md).

# FAQ

## PHP Warning: require_once(..vendor/autoload.php): failed to open stream: No such file or directory in...
Most likely you have not installed your dependencies via composer. Check the 
[composer documentation](https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies) if you are unsure on 
how to do this. If you have not worked with composer before, please check the 
[composer Getting Started guide](https://getcomposer.org/doc/00-intro.md).

## I keep getting 401 Unauthorized or 403 Forbidden errors
Make sure you have configured your credentials for the example you have to run. There are ```$username``` and 
```$password``` at the top of each example source file.

## I don't have any API credentials
Log into your emailsys account. API users can be set up via Setings > API in the main navigation bar.