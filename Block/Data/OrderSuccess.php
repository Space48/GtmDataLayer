<?php

namespace Space48\GtmDataLayer\Block\Data;

use Magento\Framework\View\Element\Template;
use Space48\GtmDataLayer\Helper\Data as GtmHelper;

class OrderSuccess extends Template {

    /**
     * @var \Magento\Cookie\Helper\Cookie
     */
    protected $cookieHelper;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $salesOrderCollection;

    /**
     * Google Tag Manager Helper
     *
     * @var \Space48\GtmDataLayer\Helper\Data
     */
    protected $gtmHelper = null;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Cookie\Helper\Cookie $cookieHelper
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param GtmHelper $gtmHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $salesOrderCollection,
        \Magento\Cookie\Helper\Cookie $cookieHelper,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\Registry $registry,
        GtmHelper $gtmHelper,
        array $data = []
    ) {
        $this->cookieHelper = $cookieHelper;
        $this->jsonHelper = $jsonHelper;
        $this->gtmHelper = $gtmHelper;
        $this->registry = $registry;
        $this->salesOrderCollection = $salesOrderCollection;

        parent::__construct(
            $context,
            $data
        );
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
        $orderIds = $this->registry->registry('orderIds');

        if (empty($orderIds) || !is_array($orderIds)) {
            return "";
        }

        $orderCollection = $this->salesOrderCollection->create();
        $orderCollection->addFieldToFilter('entity_id', ['in' => $orderIds]);

        foreach ($orderCollection as $order) {

            $json['grand_total'] = $order['grand_total'];
            $json['subtotal'] = $order['subtotal'];
            $json['shipping_amount'] = $order['shipping_amount'];
            $json['discount_amount'] = $order['discount_amount'];
            $json['coupon_code'] = $order['coupon_code'];
            $json['base_grand_total'] = $order['base_grand_total'];
            $json['base_subtotal'] = $order['base_subtotal'];
            $json['base_discount_amount'] = $order['base_discount_amount'];
            $json['base_shipping_amount'] = $order['base_shipping_amount'];
            $json['base_shipping_tax_amount'] = $order['base_shipping_tax_amount'];
            $json['base_tax_amount'] = $order['base_tax_amount'];
            $json['shipping_tax_amount'] = $order['shipping_tax_amount'];
            $json['tax_amount'] = $order['tax_amount'];
            $json['total_qty_ordered'] = $order['total_qty_ordered'];
            $json['customer_is_guest'] = $order['customer_is_guest'];
            $json['base_shipping_discount_amount'] = $order['base_shipping_discount_amount'];
            $json['base_subtotal_incl_tax'] = $order['base_subtotal_incl_tax'];
            $json['shipping_discount_amount'] = $order['shipping_discount_amount'];
            $json['subtotal_incl_tax'] = $order['subtotal_incl_tax'];
            $json['weight'] = $order['weight'];
            $json['base_currency_code'] = $order['base_currency_code'];
            $json['customer_email'] = $order['customer_email'];
            $json['customer_firstname'] = $order['customer_firstname'];
            $json['customer_lastname'] = $order['customer_lastname'];
            $json['order_currency_code'] = $order['order_currency_code'];
            $json['shipping_incl_tax'] = $order['shipping_incl_tax'];
            $json['base_shipping_incl_tax'] = $order['base_shipping_incl_tax'];
            $json['pageType'] = "orderSuccess";

            $products = [];
            /** @var \Magento\Sales\Model\Order\Item $item*/
            foreach ($order->getAllVisibleItems() as $item) {
                $product['id'] = $item->getProductId();
                $product['name'] = $item->getName();
                $product['price'] = $item->getBasePrice();
                $product['quantity'] = $item->getQtyOrdered();
                $product['sku'] = $item->getSku();
                $products[] = $product;
            }

            $json['order_items'] = $products;

            $result[] = 'dataLayer.push(' . $this->jsonHelper->jsonEncode($json) . ");\n";
        }

        return implode("\n", $result);
    }

    /*public function getProductCategory($productId)
    {
        $product = $this->productCollectionFactory->create()->load($productId);
        $categories = $product->getCategoryIds();

        if(!empty($categories)){
            $firstCategoryId = $categories[0];
            $category = $this->categoryCollectionFactory->create()->load($firstCategoryId);

            return $category->getName();
        }

        return "Furniture";
    }*/
}