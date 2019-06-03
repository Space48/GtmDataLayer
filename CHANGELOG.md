# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.3.1] - 2019-06-03

### Changed

- Added support for PHP 7.2 and PHP 7.3
- Added support for Magento 2.3

## [1.3.0] - 2018-08-14

### Added

- Added support for classic GA which dismisses variable in GTM and looks straight in the data layer for:

    - transactionId
    - transactionProducts
    - transactionShipping
    - transactionTax
    - transactionTotal

## [1.2.0] - 2018-07-25

### Added

- Added product availability (in stock or out of stock) to the data layer

## [1.1.0] - 2018-07-24

### Added

- Added more data into the data layer for product, cart and order success pages. This was initially to allow the additional Dynamic Remarketing tags.

## [1.0.12] - 2018-02-21

### Fixed

- Fixed module breaking some layouts after install
- Fixed console errors when GTM not configured

## [1.0.11] - 2018-02-21

### Fixed

- Fixed console error when the module is disabled

## [1.0.10] - 2017-11-17

### Changed

- Added support for PHP 7.1.x

## [1.0.9] - 2017-10-31

### Changed

- Added support for Magento 2.2

## [1.0.8] - 2017-06-15

### Fixed

- Fixed console error binding to undefined again

## [1.0.7] - 2017-06-07

### Removed

- Reverted the fix for console error binding to undefined

## [1.0.6] - 2017-06-05

### Added

- Added extra order item data for use in Data Layer. Now includes:
  
   - id
   - base_price
   - price
   - base_original_price
   - original_price
   - price_incl_tax
   - base_price_incl_tax
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


## [1.0.5] - 2017-06-01

### Fixed

- Fixed console error binding to undefined

## [1.0.4] - 2017-05-11

### Changed

- Removed unnecessary plugin declaration

## [1.0.3] - 2017-05-04

### Changed

- Use cookies to store private data instead of local storage

## [1.0.2] - 2017-05-03

### Fixed

- Fixed broken checkout header

## [1.0.1] - 2017-04-19

### Fixed

- Fixed core registry definition

## [1.0.0] - 2017-04-06

Initial release
