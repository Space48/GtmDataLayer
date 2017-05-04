<?php

namespace Space48\GtmDataLayer\Observer\Frontend;

use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Helper\Session\CurrentCustomer;

class ControllerActionPredispatchObserver implements ObserverInterface
{
    protected $cookieManager;
    protected $cookieMetadataFactory;
    protected $jsonHelper;
    const CUSTOMER_SESSION_COOKIE_NAME = "customerSession";
    protected $currentCustomer;

    /**
     * @param \Magento\Framework\View\LayoutInterface $layout
     */
    public function __construct(
        CurrentCustomer $currentCustomer,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper
    )
    {
        $this->currentCustomer = $currentCustomer;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->jsonHelper = $jsonHelper;
    }


    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customerId = $this->currentCustomer->getCustomerId();

        if ($customerId) {
            $customerSession['user_id'] = $customerId;

            $publicCookieMetadata = $this->cookieMetadataFactory->createPublicCookieMetadata()
                ->setDuration(3600)
                ->setPath('/')
                ->setHttpOnly(false);

            $this->cookieManager->setPublicCookie(
                self::CUSTOMER_SESSION_COOKIE_NAME,
                $this->jsonHelper->jsonEncode($customerSession),
                $publicCookieMetadata
            );
        }
    }
}