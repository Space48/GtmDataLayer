<?php

namespace Space48\GtmDataLayer\Block\Data;

use Magento\Framework\View\Element\Template;
use Space48\GtmDataLayer\Helper\Data as GtmHelper;

class OrderSuccess extends Template {

    protected $defaultCategoryName;

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
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;

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
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        GtmHelper $gtmHelper,
        array $data = []
    ) {
        $this->cookieHelper = $cookieHelper;
        $this->jsonHelper = $jsonHelper;
        $this->gtmHelper = $gtmHelper;
        $this->registry = $registry;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->salesOrderCollection = $salesOrderCollection;
        $this->defaultCategoryName = $this->gtmHelper->getConfig("item_category");

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

    public function getOrderPaymentMethod($order)
    {
        try {
            $payment = $order->getPayment();
            $method = $payment->getMethodInstance();
            return $method->getTitle();
        } catch (\Exception $exception) {
            // do nothing
        }

        return "";
    }

    public function getOutput()
    {
        $json = $result = array();
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
            $json['shipping_description'] = $order['shipping_description'];
            $json['order_date'] = $order['created_at'];
            $json['payment_method'] = $this->getOrderPaymentMethod($order);
            $json['pageType'] = "orderSuccess";

            $products = [];
            $totalValue = 0;

            /** @var \Magento\Sales\Model\Order\Item $item*/
            foreach ($order->getAllVisibleItems() as $item) {
                $product['id'] = $item->getProductId();
                $product['name'] = $item->getName();
                $product['base_price'] = $item->getBasePrice();
                $product['price'] = $item->getPrice();
                $product['base_price'] = $item->getBasePrice();
                $product['price_incl_tax'] = $item->getPriceInclTax();
                $product['base_price_incl_tax'] = $item->getBasePriceInclTax();
                $product['row_total'] = $item->getRowTotal();
                $product['base_row_total'] = $item->getBaseRowTotal();
                $product['row_total_incl_tax'] = $item->getRowTotalInclTax();
                $product['base_row_total_incl_tax'] = $item->getBaseRowTotalInclTax();
                $product['base_original_price'] = $item->getBaseOriginalPrice();
                $product['original_price'] = $item->getOriginalPrice();
                $product['tax_percent'] = $item->getTaxPercent();
                $product['tax_amount'] = $item->getTaxAmount();
                $product['base_tax_amount'] = $item->getBaseTaxAmount();
                $product['quantity'] = $item->getQtyOrdered();
                $product['discount_percent'] = $item->getDiscountPercent();
                $product['discount_amount'] = $item->getDiscountAmount();
                $product['base_discount_amount'] = $item->getBaseDiscountAmount();
                $product['sku'] = $item->getSku();
                $product['weight'] = $item->getWeight();
                $productEntity = $this->getProductById($item->getProductId());
                $product['category'] = $this->getCategoryName($productEntity);
                $products[] = $product;
                $totalValue += $product['base_row_total_incl_tax'];
            }

            $json['order_items'] = $products;
            $json['ecomm_totalvalue'] = $totalValue;

            // Added Support for Classic Google Analytics

            $itemsTax = 0;
            $transactionProducts = [];

            foreach ($order->getAllVisibleItems() as $item) {

                $transactionProduct['name'] = $item->getName();
                $transactionProduct['price'] = $item->getBasePriceInclTax();
                $transactionProduct['quantity'] = $item->getQtyOrdered();
                $transactionProduct['sku'] = $item->getSku();
                $transactionProducts[] = $transactionProduct;
                $itemsTax += $item->getBaseTaxAmount();
            }

            $json['transactionId'] = $order['increment_id'];
            $json['transactionTotal'] = $order['base_grand_total'];
            $json['transactionTax'] = $itemsTax;
            $json['transactionShipping'] = $order['base_shipping_incl_tax'];
            $json['transactionProducts'] = $transactionProducts;

            $result[] = 'dataLayer.push(' . $this->jsonHelper->jsonEncode($json) . ");\n";
        }

        return implode("\n", $result);
    }

    public function getProductById($productId)
    {
        return $this->productCollectionFactory->create()
            ->addAttributeToFilter('entity_id', $productId)
            ->addAttributeToSelect('name')
            ->setPageSize(1)
            ->getFirstItem();
    }

    public function getCategoryName($product)
    {
        $categories = $product->getCategoryIds();
        $categoryName = null;

        if(!empty($categories)){
            $category = $this->getFirstCategory($categories);
            $categoryName = $category->getName();
        }

        return is_null($categoryName) ? $this->defaultCategoryName : $categoryName;
    }

    public function getFirstCategory($categoryIds)
    {
        return $this->categoryCollectionFactory->create()
            ->addAttributeToFilter('entity_id', array("in" => $categoryIds))
            ->addAttributeToFilter('is_active', 1)
            ->addAttributeToSelect('name')
            ->setPageSize(1)
            ->getFirstItem();
    }
}
