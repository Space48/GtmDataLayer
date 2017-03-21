<?php

namespace Space48\GtmDataLayer\Block\Data;

use Space48\GtmDataLayer\Helper\Data as GtmHelper;

class ProductView extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Product
     */
    protected $_product = null;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * Google Tag Manager Helper
     *
     * @var \Space48\GtmDataLayer\Helper\Data
     */
    protected $gtmHelper = null;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\Registry $registry,
        GtmHelper $gtmHelper,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->jsonHelper = $jsonHelper;
        $this->gtmHelper = $gtmHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->_coreRegistry->registry('product');
        }
        return $this->_product;
    }

    protected function _toHtml()
    {
        if (!$this->gtmHelper->isTypeEnabled(array('order_success'))) {
            return '';
        }

        return $this->getOutput();
    }

    public function getOutput()
    {
        $json = array();
        $result[] = 'dataLayer.push(' . $this->jsonHelper->jsonEncode($json) . ");\n";

        return implode("\n", $result);
    }


}