# Add some Aiven magic to your Laravel project

This Laravel package provides some `aiven` commands for `artisan` to help with managing your development databases and producing the correct configuration to use with them. This version supports both MySQL and PostgreSQL.

Use the commands to:
* List the Aiven services in your project
* Power your databases on and off from artisan, so you don't leave the meter running when you're not working
* Get database config you can paste straight into a config file.

## Getting Started

You will need an Aiven account - [sign up for a free trial](https://console.aiven.io/signup?utm_source=github&utm_medium=aiven-laravel) if you don't have one already.

Get an [auth token](https://developer.aiven.io/docs/platform/howto/create_authentication_token) for your Aiven account, and set it as `AIVEN_API_TOKEN` in your environment.



