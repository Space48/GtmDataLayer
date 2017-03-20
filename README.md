# GTM Data Layer

This module will add basic data layer variables to certain site pages so that they are available within Google Tag Manager.


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
- pageType
- order_items[]
    - id
    - name
    - quantity
    - sku

## Installation

**Manual**

To install this module copy the code from this repo to `app/code/Space48/GtmDataLayer` folder of your Magento 2 instance, then you need to run php `bin/magento setup:upgrade`

**Composer**:

From the terminal execute the following:

`composer config repositories.space48-gtm-datalayer vcs git@github.com:Space48/GtmDataLayer.git`

then

`composer require "space48/gtmdatalayer:dev-master"`

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

