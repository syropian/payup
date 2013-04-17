payup
=====

A pre-built, self-hosted solution for accepting Stripe payments.

### Setup

There are a few things you need to do to get this up and running.

* Set your API keys. Depending on your development stage, these keys can be entered on lines 12 or 15 in `index.php` and lines 2 or 5 in `payup.js`.
* Set your currency on line 22 of `index.php`
* You **must** use SSL in a production environment as per Stripe's terms. An easy & free way to do this is to host your app on **Heroku**. I have written the necesarry code to force Heroku to use SSL. 