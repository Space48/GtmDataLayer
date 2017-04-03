define([
    'Magento_Customer/js/customer-data'
], function (customerData) {
    'use strict';

    return function (config) {
        var customerId = customerData.get('customer')().customer_id;

        if (customerId != undefined) {
            var json = '{ "customer_id" : '+ customerId +' }';
            dataLayer.push(json);
        }
    }
});