# GTM Data Layer
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Space48/GtmDataLayer/badges/quality-score.png?b=master&s=40b352a1fec21670570fddd703321ad5251e04b9)](https://scrutinizer-ci.com/g/Space48/GtmDataLayer/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Space48/GtmDataLayer/badges/build.png?b=master&s=d902f6f6a71fe3f193c4cfe469e63dcf798f6ab2)](https://scrutinizer-ci.com/g/Space48/GtmDataLayer/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/Space48/GtmDataLayer/badges/coverage.png?b=master&s=3a3c42cd6d60da0006fe80942729ebdf5534f392)](https://scrutinizer-ci.com/g/Space48/GtmDataLayer/?branch=master)

This module will add basic data layer variables to certain site pages so that they are available within Google Tag Manager.

### All Pages
- customer_id (if logged in - uses local storage value)

### Order Success

- grand_total
- subtotal
- shipping_amount
- discount_amount
- coupon_code
- base_grand_total
- base_subtotal
- base_discount_amount
- base_shipping_amount
- base_shipping_tax_amount
- base_tax_amount
- shipping_tax_amount
- tax_amount
- total_qty_ordered
- customer_is_guest
- base_shipping_discount_amount
- base_subtotal_incl_tax
- shipping_discount_amount
- subtotal_incl_tax
- weight
- base_currency_code
- customer_email
- customer_firstname
- customer_lastname
- order_currency_code
- shipping_incl_tax
- base_shipping_incl_tax
- payment_method
- shipping_description
- ecomm_totalvalue
- pageType
- order_items[]
    - id
    - price
    - base_price
    - base_original_price
    - original_price
    - price_incl_tax
    - base_price_incl_tax
    - row_total
    - base_row_total
    - row_total_incl_tax
    - base_row_total_incl_tax
    - tax_percent
    - tax_amount
    - base_tax_amount
    - quantity
    - discount_percent
    - discount_amount
    - base_discount_amount
    - sku
    - weight
    - category
    
### Cart

- pageType
- subtotal
- grand_total
- total_items
- total_qty
- ecomm_totalvalue
- ecomm_prodid
- total_qty
- items[]
    - id
    - name
    - sku
    - price
    - base_price
    - price_incl_tax
    - base_price_incl_tax
    - row_total
    - base_row_total
    - row_total_incl_tax
    - base_row_total_incl_tax

### Category View

- pageType

### Product View

- pageType
- base_image
- base_price_excl_tax
- base_price_incl_tax
- ecomm_prodid
- ecomm_totalvalue
- availability

### Checkout

- pageType

### Search Result

- pageType

## Installation

**Manual**

To install this module copy the code from this repo to `app/code/Space48/GtmDataLayer` folder of your Magento 2 instance, then you need to run php `bin/magento setup:upgrade`

**Composer**:

From the terminal execute the following:

`composer config repositories.space48-gtm-datalayer vcs git@github.com:Space48/GtmDataLayer.git`

then

`composer require "space48/gtmdatalayer:{module-version}"`

## How to use it
Once installed, go to the admin area and go to `Stores -> Configuration -> Space48 -> GTM Data Layer` and `enable` the extension.

## Adding Custom Data Layer

This module creates an entry point into the code to add your own data to the Data Layer by simply referencing the block `space48_gtm_datalayer` in your layout XML. For example, if I wanted to add data to the product page I would do the following:

#### catalog_product_view.xml

````
<?xml version="1.0"?>
<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" layout="1column" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
    <referenceBlock name="space48_gtm_datalayer">
        <block class="Space48\CustomModule\Block\Data\Product" name="custom_product_variables"/>
    </referenceBlock>
</page>
````

#### Space48\CustomModule\Block\Data\Product.php

Within the block you can invoke as much business logic as you need to produce the data, then use the method `_toHtml()` to output the Data Layer string:
````
protected function _toHtml()
{
    return "dataLayer.push('brand' : 'Nike');\n";
}
````

### Configuration

Go to `Stores > Configuration > Space48 > GTM DataLayer > Default Data Layer` to configure/enable.

### Using Google Tag Manager

All of the monetary values provided by this module are decimals with 4 decimal places. Some trackers such as Affiliate Window require this to be rounded to two decimal places. If so simply use the following in the GTM tag:

````
function formatPrice(price)
{
    price = parseFloat(price);
    return price.toFixed(2);
}
````
