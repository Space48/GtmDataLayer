<?php

namespace Space48\GtmDataLayer\Block\Data;

use Magento\GoogleTagManager\Block\Ga;

class OrderSuccess extends Ga {

    protected $helper;

    /**
     * @var \Magento\Cookie\Helper\Cookie
     */
    protected $cookieHelper;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $salesOrderCollection
     * @param \Magento\GoogleTagManager\Helper\Data $googleAnalyticsData
     * @param \Magento\Cookie\Helper\Cookie $cookieHelper
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $salesOrderCollection,
        \Magento\GoogleTagManager\Helper\Data $googleAnalyticsData,
        \Magento\Cookie\Helper\Cookie $cookieHelper,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        array $data = []
    ) {
        $this->cookieHelper = $cookieHelper;
        $this->jsonHelper = $jsonHelper;

        parent::__construct(
            $context,
            $salesOrderCollection,
            $googleAnalyticsData,
            $cookieHelper,
            $jsonHelper,
            $data
        );
    }

    protected function _toHtml()
    {
        return $this->getAdditionalOrderData();
    }

    public function getAdditionalOrderData()
    {
        foreach ($this->getOrderCollection() as $order) {
            $json['vis_opt_revenue'] = $order->getBaseGrandTotal();
            $result[] = 'dataLayer.push(' . $this->jsonHelper->jsonEncode($json) . ");\n";
        }
        return implode("\n", $result);

    }

    public function getOrderCollection()
    {
        $orderIds = $this->getOrderIds();

        var_dump($orderIds);

        if (empty($orderIds) || !is_array($orderIds)) {
            return;
        }

        $collection = $this->_salesOrderCollection->create();
        $collection->addFieldToFilter('entity_id', ['in' => $orderIds]);

        return $collection;
    }


}