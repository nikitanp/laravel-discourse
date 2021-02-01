PHP Discourse Client for the Laravel Framework
==============

## Version Compatibility

| Laravel Framework   | Discourse Client |
| :-------- | :---------- |
| > 6 or higher   | 0.0.3 |

## Getting Started

Before going through the rest of this documentation, please take some time to read
the [Discourse API Documentation](https://docs.discourse.org/). Not all of the API calls are documented, but it's a good
place to start. Additional help with SSO: [https://meta.discourse.org](https://meta.discourse.org/c/dev/sso/24)

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
  "require": {
    "nikitanp/laravel-discourse": "^0.0.3"
  }
}
```

And then run `composer install` from the terminal.

Add the following to config/app.php if you version laravel doesn't support package auto discovering:

```php
    /*
    * $APP_PATH/config/app.php
    */
    'providers' => [
        NikitaMikhno\LaravelDiscourse\DiscourseServiceProvider::class
    ],
```

You can optionally publish the config file with:

```bash
php artisan vendor:publish --provider="NikitaMikhno\LaravelDiscourse\DiscourseServiceProvider" --tag="config"

```

# Configure Laravel Discourse

## Laravel Env

Set the API Token, Forum url and SSO Token in your .env:

```
DISCOURSE_URL=https://forum.url
DISCOURSE_SECRET={sso secret}
DISCOURSE_TOKEN={api token}
DISCOURSE_SSO_ENABLED=true/false
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

### Credits

Forked from: [matthew-jensen/laravel-discourse-client](https://github.com/matthew-jensen/laravel-discourse).

SSO Helper Methods: [cviebrock/discourse-php](https://github.com/cviebrock/discourse-php/).

SSO Controller Methods: [spinen/laravel-discourse-sso](https://github.com/spinen/laravel-discourse-sso).

