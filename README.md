PHP Discourse Client for the Laravel Framework
==============

## Version Compatibility

 Laravel   | Discourse Client
:--------- | :----------
 > 5.8.x   | Untested
 5.8.x     | 0.0.1


## Getting Started

Before going through the rest of this documentation, please take some time to read the [Discourse API Documentation](https://docs.discourse.org/).
Not all of the API calls are documented, but it's a good place to start.
Additional help with SSO: [https://meta.discourse.org](https://meta.discourse.org/c/dev/sso/24)

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require": {
        "matthew-jensen/laravel-discourse-client": "^0.0.1"
    }
}
```

And then run `composer install` from the terminal.

### Quick Installation

Above installation can also be simplify by using the following command:

```bash
    composer require "matthew-jensen/larave-discourse-client"
```

### Credits


SSO Helper Methods: [cviebrock/discourse-php](https://github.com/cviebrock/discourse-php/).

SSO Controller Methods: [spinen/laravel-discourse-sso](https://github.com/spinen/laravel-discourse-sso).

API Methods: [discoursehosting/discourse-api-php](https://github.com/discoursehosting/discourse-api-php).

