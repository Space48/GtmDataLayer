<?php

namespace Space48\GtmDataLayer\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    const XML_PATH_ACTIVE = 's48_gtm_datalayer/general/active';
    const XML_PATH_ORDER_SUCCESS_ACTIVE = 's48_gtm_datalayer/general/order_success_active';

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\ObjectManagerInterface
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context, \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_objectManager = $objectManager;
        parent::__construct($context);
    }

    /**
     * Whether the module is ready to use
     *
     * @return bool
     */
    public function isEnabled() {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ACTIVE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * Whether the module and page type are enabled
     *
     * @param string
     * @return bool
     */
    public function isTypeEnabled($type) {

        if (!$this->isEnabled()) {
            return false;
        }

        return $this->scopeConfig->isSetFlag('s48_gtm_datalayer/general/'.$type.'_active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }


}