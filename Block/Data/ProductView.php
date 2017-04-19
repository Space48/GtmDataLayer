<?php

namespace Space48\GtmDataLayer\Block\Data;

use Magento\Framework\View\Element\Template;
use Space48\GtmDataLayer\Helper\Data as GtmHelper;

class ProductView extends Template
{
    /**
     * @var Product
     */
    protected $_product = null;

    protected $imageBuilder = null;

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

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        GtmHelper $gtmHelper,
        array $data = []
    ) {
        $this->_coreRegistry = $context->getRegistry();
        $this->jsonHelper = $jsonHelper;
        $this->gtmHelper = $gtmHelper;
        $this->imageBuilder = $context->getImageBuilder();
        parent::__construct($context, $data);
    }

    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->_coreRegistry->registry('product');
        }
        return $this->_product;
    }

    public function getImage($product, $imageId, $attributes = [])
    {
        return $this->imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
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
        $json = $result = array();
        $json['base_image'] = $this->escapeUrl($this->getImage($this->getProduct(), 'product_base_image')->getImageUrl());
        $json['pageType'] = "productDetail";

        $result[] = 'dataLayer.push(' . $this->jsonHelper->jsonEncode($json) . ");\n";

        return implode("\n", $result);
    }


}