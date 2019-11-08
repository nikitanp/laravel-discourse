PHP Discourse Client for the Laravel Framework
==============

## Version Compatibility

| Laravel   | Discourse Client |
| :-------- | :---------- |
| > 5.8.x   | Untested |
| 5.8.x     | 0.0.2 |


## Getting Started

Before going through the rest of this documentation, please take some time to read the [Discourse API Documentation](https://docs.discourse.org/).
Not all of the API calls are documented, but it's a good place to start.
Additional help with SSO: [https://meta.discourse.org](https://meta.discourse.org/c/dev/sso/24)

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require": {
        "matthew-jensen/laravel-discourse-client": "^0.0.2"
    }
}
```

And then run `composer install` from the terminal.

Add the following to config/app.php:

```php
    /*
    * $APP_PATH/config/app.php
    */
    'providers' => [
        MatthewJensen\LaravelDiscourse\DiscourseServiceProvider::class
    ],
```

You can optionally publish the config file with:

```bash
php artisan vendor:publish --provider="MatthewJensen\LaravelDiscourse\DiscourseServiceProvider" --tag="config"

```

This is the contents of the published config file:

```php
<?php
return [

    // API token. 
    'token' => env('DISCOURSE_TOKEN')

    // Middleware for the SSO login route to use
    'middleware' => ['web', 'auth'],

    // The route's URI that acts as the entry point for Discourse to start the SSO process.
    // Used by Discourse to route incoming logins.
    'route' => 'discourse/sso',
    'logout' => 'discourse/sso/logout',
    
    // Secret string used to encrypt/decrypt SSO information,
    // be sure that it is 10 chars or longer
    'secret' => env('DISCOURSE_SECRET'),
    
    // Disable Discourse from sending welcome message
    'suppress_welcome_message' => 'true',
    
    // Where the Discourse forum lives
    'url' => env('DISCOURSE_URL'),
    
    // User-specific items
    // NOTE: The 'email' & 'external_id' are the only 2 required fields
    'user' => [
        // Check to see if the user has forum access & should be logged in via SSO
        'access' => null,
    
        // Discourse Groups to make sure that the user is part of in a comma-separated string
        // NOTE: Groups cannot have spaces in their names & must already exist in Discourse
        'add_groups' => null,

        // Boolean for making the user a Discourse admin. Leave null to ignore
        'admin' => 'discourse_admin',

        // Full path to user's avatar image
        'avatar_url' => null,
        
        // The avatar is cached, so this triggers an update
        'avatar_force_update' => false,
        
        // Content of the user's bio
        'bio' => null,
        
        // Verified email address (see "require_activation" if not verified)
        'email' => 'email',
        
        // Unique string for the user that will never change
        'external_id' => 'id',
        
        // Boolean for making user a Discourse moderator. Leave null to ignore 
        'moderator' => 'discourse_moderator',
        
        // Full name on Discourse if the user is new or 
        // if SiteSetting.sso_overrides_name is set
        'name' => 'name',

        // Discourse Groups to make sure that the user is *NOT* part of in a comma-separated string.
        // NOTE: Groups cannot have spaces in their names & must already exist in Discourse
        // There is not a way to specify the exact list of groups that a user is in, so
        // you may want to send the inverse of the 'add_groups'
        'remove_groups' => null,
        
        // If the email has not been verified, set this to true
        'require_activation' => false,
        
        // username on Discourse if the user is new or 
        // if SiteSetting.sso_overrides_username is set
        'username' => 'email',
    ],
];
```
# Configure Laravel Discourse

## Laravel Env

Set the API Token, Forum url and SSO Token in your .env:

```
DISCOURSE_URL=https://forum.url
DISCOURSE_SECRET={sso secret}
DISCOURSE_TOKEN={api token}
```

## Discourse Env

**Enable SSO**
Via UI:
see: "{DISCOURSE\_URL}/admin/site\_settings/category/required?filter="

Via console:

```bash
cd /var/discourse
./launcher enter app
rails c
irb > SiteSetting.sso_secret = {config('discourse.secret')}
irb > SiteSetting.sso_url = {config('discourse.route')}
irb > SiteSetting.logout_redirect = {config('discourse.logout')}
irb > SiteSetting.enable_sso = true
irb > SiteSetting.enable_local_logins = false
irb > exit
exit
```

# TODO
- [ ] Artisan command to set sso secret, route and enable sso to install out of the box (could be done via API calls in Facade).

### Credits

SSO Helper Methods: [cviebrock/discourse-php](https://github.com/cviebrock/discourse-php/).

SSO Controller Methods: [spinen/laravel-discourse-sso](https://github.com/spinen/laravel-discourse-sso).

API Methods: [discoursehosting/discourse-api-php](https://github.com/discoursehosting/discourse-api-php).

