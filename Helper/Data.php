<?php

namespace Space48\GtmDataLayer\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    const XML_PATH = 's48_gtm_datalayer/default/';

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
        return $this->scopeConfig->isSetFlag(self::XML_PATH."active", \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * Whether the module and page type are enabled
     *
     * @param string
     * @return bool
     */
    public function isTypeEnabled($types = array()) {

        if (!$this->isEnabled() || !is_array($types)) {
            return false;
        }

        foreach ($types as $type) {
            if (!$this->scopeConfig->isSetFlag(self::XML_PATH.$type.'_active',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
                return false;
            }
        }

        return true;
    }

    public function getConfig($field) {
        return $this->scopeConfig->getValue(self::XML_PATH.$field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }


}