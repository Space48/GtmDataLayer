<?php

namespace Space48\GtmDataLayer\Observer\Frontend;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Space48\GtmDataLayer\Helper\Data as GtmHelper;

class OrderSuccessObserver implements ObserverInterface
{
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
     * @param \Magento\Framework\View\LayoutInterface $layout
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        GtmHelper $gtmHelper
    ) {
        $this->registry = $registry;
        $this->gtmHelper = $gtmHelper;
    }

    /**
     * Add order information into GA block to render on checkout success pages
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        $orderIds = $observer->getEvent()->getOrderIds();
        if (empty($orderIds) || !is_array($orderIds)) {
            return;
        }

        $this->registry->register('orderIds', $orderIds);
    }
}