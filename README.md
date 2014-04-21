# PrismicBundle

This Bundles integrates the http://prismic.io php-kit with the Symfony Framework:
https://github.com/prismicio/php-kit

For an example use see:
https://github.com/prismicio/php-symfony-starter

## Installation

Add the following dependencies to your projects ``composer.json`` file:

    "require": {
        # ..
        "prismic/prismic-bundle": "~1.0@dev"
        # ..
    }

## Configuration

Add the following configuration to your projects ``app/config/config.yml`` file:

    # Default configuration for extension with alias: "prismic"
    prismic:
        api:
            endpoint:             ~ # Required
            access_token:         ~
            client_id:            ~
            client_secret:        ~

## TODOs

* Move to https://github.com/prismicio
* Register on packagist.org
* Remove SensioFrameworkExtraBundle dependency
* Add a listener for Symfony 2.3 to set the request data into the context as 2.3 does not support ExpressionLanguage
* Add unit (and functional?) tests
