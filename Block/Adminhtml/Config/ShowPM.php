<?php
/**
 * Copyright (c) 2018 CardGate B.V.
 * All rights reserved.
 * See LICENSE for license details.
 */
namespace Cardgate\Payment\Block\Adminhtml\Config;

use Cardgate\Payment\Model\Config;

/**
 * Render for "show payment method" element
 *
 * @author DBS B.V.
 *
 */
class ShowPM extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     *
     * @var Config
     */
    private $config;

    /**
     *
     * @param \Magento\Backend\Block\Context $context
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param \Magento\Framework\View\Helper\Js $jsHelper
     * @param \Magento\Config\Model\Config $backendConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        Config $backendConfig,
        array $data = []
    ) {
        $this->config = $backendConfig;
        parent::__construct($context, $data);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Magento\Config\Block\System\Config\Form\Field::_getElementHtml()
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        if (empty($this->config->getGlobal('active_pm'))) {
            return "<span style='color:#ff0000'>" . __("No active payment methods found") . "</span>";
        } else {
            return implode(', ', $this->config->getActivePMIDs());
        }
    }
}
