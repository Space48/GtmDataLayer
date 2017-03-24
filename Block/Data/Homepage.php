<?php

namespace Space48\GtmDataLayer\Block\Data;

use Magento\Framework\View\Element\Template;
use Space48\GtmDataLayer\Helper\Data as GtmHelper;

class Homepage extends Template {

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
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param GtmHelper $gtmHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        GtmHelper $gtmHelper,
        array $data = []
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->gtmHelper = $gtmHelper;

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

    public function getOutput()
    {
        $json = $result = array();
        $json['pageType2'] = "homepage";
        $result[] = 'dataLayer.push(' . $this->jsonHelper->jsonEncode($json) . ");\n";

        return implode("\n", $result);
    }
}