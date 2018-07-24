<?php

namespace Space48\GtmDataLayer\Block\Data;

use Magento\Framework\View\Element\Template;
use Space48\GtmDataLayer\Helper\Data as GtmHelper;
use Magento\Checkout\Model\Cart as ShoppingCart;

class Cart extends Template {

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * GtmDataLayer Helper
     *
     * @var \Space48\GtmDataLayer\Helper\Data
     */
    protected $gtmHelper = null;

    /**
     * @var ShoppingCart
     */
    protected $cart;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param GtmHelper $gtmHelper
     * @param ShoppingCart $cart
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        GtmHelper $gtmHelper,
        ShoppingCart $cart,
        array $data = []
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->gtmHelper = $gtmHelper;
        $this->cart = $cart;

        parent::__construct(
            $context,
            $data
        );
    }

    protected function _toHtml()
    {
        if (!$this->gtmHelper->isEnabled()) {
            return '';
        }

        return $this->getOutput();
    }

    public function getCartData()
    {
        $quote = $this->cart->getQuote();
        $items = $quote->getItemsCollection();

        $quoteItems = [];
        $productSkus = [];
        $totalValue = 0;

        foreach($items as $item) {
            $quoteItem['id'] = $item->getProductId();
            $quoteItem['name'] = $item->getName();
            $quoteItem['sku'] = $item->getSku();
            $quoteItem['qty'] = $item->getQty();
            $quoteItem['price'] = $item->getPrice();
            $quoteItem['base_price'] = $item->getBasePrice();
            $quoteItem['price_incl_tax'] = $item->getPriceInclTax();
            $quoteItem['base_price_incl_tax'] = $item->getBasePriceInclTax();
            $quoteItem['row_total'] = $item->getRowTotal();
            $quoteItem['base_row_total'] = $item->getBaseRowTotal();
            $quoteItem['row_total_incl_tax'] = $item->getRowTotalInclTax();
            $quoteItem['base_row_total_incl_tax'] = $item->getBaseRowTotalInclTax();
            $totalValue += $quoteItem['base_row_total_incl_tax'];
            $quoteItems[] = $quoteItem;
            $productSkus[] = $item->getSku();
        }

        return [
            'items' => $quoteItems,
            'subtotal' => $quote->getSubtotal(),
            'grand_total' => $quote->getGrandTotal(),
            'total_items' => $quote->getItemsCount(),
            'total_qty' => $quote->getItemsQty(),

            // Dynamic Remarketing Parameters
            'ecomm_totalvalue' => $totalValue,
            'ecomm_prodid' => $productSkus,
        ];
    }


    public function getOutput()
    {
        $result = [];
        $json = $this->getCartData();
        $json['pageType'] = "cart";
        $result[] = 'dataLayer.push(' . $this->jsonHelper->jsonEncode($json) . ");\n";

        return implode("\n", $result);
    }
}
