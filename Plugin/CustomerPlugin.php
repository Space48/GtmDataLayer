<?php
namespace Space48\GtmDataLayer\Plugin;

use Magento\Customer\Helper\Session\CurrentCustomer;

class CustomerPlugin
{
    /**
     * @var CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @param CurrentCustomer $currentCustomer
     */
    public function __construct(
        CurrentCustomer $currentCustomer
    ) {
        $this->currentCustomer = $currentCustomer;
    }

    public function afterGetSectionData(\Magento\Customer\CustomerData\Customer $customer, $sectionData)
    {
        if (empty($sectionData)) {
            return [];
        }

        $sectionData['customer_id'] = $this->currentCustomer->getCustomerId();

        return $sectionData;
    }
}