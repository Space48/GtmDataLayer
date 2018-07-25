<?php

namespace Space48\GtmDataLayer\Block\Data;

use Magento\CatalogInventory\Api\StockRegistryInterface;
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

    /**
     * @var StockRegistryInterface
     */
    protected $stockRegistry;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        StockRegistryInterface $stockRegistry,
        GtmHelper $gtmHelper,
        array $data = []
    ) {
        $this->_coreRegistry = $context->getRegistry();
        $this->jsonHelper = $jsonHelper;
        $this->gtmHelper = $gtmHelper;
        $this->imageBuilder = $context->getImageBuilder();
        $this->stockRegistry = $stockRegistry;
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

    public function getProductAvailability($product)
    {
        return $this->stockRegistry->getProductStockStatus($product->getId()) == "1"
            ? "in stock" : "out of stock";
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
        $product = $this->getProduct();

        $json['base_price_excl_tax'] = $product->getPriceInfo()->getPrice('final_price')->getAmount()->getBaseAmount();
        $json['base_price_incl_tax'] = $product->getFinalPrice();

        // Dynamic Remarketing Parameters
        $json['ecomm_prodid'] = $product->getSku();
        $json['ecomm_totalvalue'] = $json['base_price_incl_tax'];

        $json['availability'] = $this->getProductAvailability($product);
        $json['base_image'] = $this->escapeUrl($this->getImage($product, 'product_base_image')->getImageUrl());
        $json['pageType'] = "productDetail";

        $result[] = 'dataLayer.push(' . $this->jsonHelper->jsonEncode($json) . ");\n";

        return implode("\n", $result);
    }
}
