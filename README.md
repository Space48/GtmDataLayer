# GTM Data Layer

This module will add basic data layer variables to certain site pages so that they are available within Google Tag Manager.


### Order

- transactionId
- transactionAffiliation
- transactionTotal
- transactionShipping
- transactionTax
- transactionCouponCode
- transactionDiscount
- transactionSubTotal
- transactionProducts[].sku
- transactionProducts[].name
- transactionProducts[].price
- transactionProducts[].quantity

## Installation

**Manual**

To install this module copy the code from this repo to `app/code/Space48/GtmDataLayer` folder of your Magento 2 instance, then you need to run php `bin/magento setup:upgrade`

**Composer**:

From the terminal execute the following:

`composer config repositories.space48-gtm-datalayer vcs git@github.com:Space48/GtmDataLayer.git`

then

`composer require "space48/gtmdatalayer:dev-master"`

## How to use it
Once installed, go to the `Admin Penel -> Stores -> Configuration -> Space48 -> GTM Data Layer` and `enable` the extension.