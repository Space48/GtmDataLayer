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
        GtmHelper $gtmHelper,
        array $data = []
    ) {
        $this->cookieHelper = $cookieHelper;
        $this->jsonHelper = $jsonHelper;
        $this->gtmHelper = $gtmHelper;
        $this->salesOrderCollection = $salesOrderCollection;

        parent::__construct(
            $context,
            $data
        );
    }

    protected function _toHtml()
    {
        if (!$this->gtmHelper->isTypeEnabled('order_success')) {
            return '';
        }

        return $this->getAdditionalOrderData();
    }

    public function getAdditionalOrderData()
    {
        $orderIds = $this->getOrderIds();

        if (empty($orderIds) || !is_array($orderIds)) {
            return "";
        }

        $orderCollection = $this->salesOrderCollection->create();
        $orderCollection->addFieldToFilter('entity_id', ['in' => $orderIds]);

        foreach ($orderCollection as $order) {
            $json['grand_total'] = $order->getBaseGrandTotal();

            $result[] = 'dataLayer.push(' . $this->jsonHelper->jsonEncode($json) . ");\n";
        }

        return implode("\n", $result);
    }
}