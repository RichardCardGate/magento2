<?php
/**
 * Copyright (c) 2018 CardGate B.V.
 * All rights reserved.
 * See LICENSE for license details.
 */
namespace Cardgate\Payment\Model\Layout;

use Cardgate\Payment\Model\Config\Master;
use Magento\Checkout\Block\Checkout\LayoutProcessor;

/**
 * Layout Processor plugin to inject payment methods in checkout billing-step section
 *
 * @author DBS B.V.
 *
 */
class LayoutProcessorPlugin
{

    /**
     *
     * @var Master $_masterConfig
     */
    private $_masterConfig = null;

    /**
     * @param Master $masterConfig
     */
    public function __construct(Master $masterConfig)
    {
        $this->_masterConfig = $masterConfig;
    }

    /**
     * Inject payment methods in checkout billing-step section
     *
     * @param LayoutProcessor $layoutProcessor
     * @param \Closure $proceed
     * @param unknown $scope
     * @return string[]|boolean[]
     */
    public function aroundProcess(LayoutProcessor $layoutProcessor, \Closure $proceed, $scope)
    {
        $arr = [
            'component' => 'Cardgate_Payment/js/view/payment/paymentmethods',
            'label' => 'CardGate',
            'methods' => []
        ];
        foreach ($this->_masterConfig->getPaymentMethods() as $paymentMethod) {
            $arr['methods'][$paymentMethod] = [
                'isBillingAddressRequired' => true
            ];
        }
        $scope['components']
        ['checkout']
        ['children']
        ['steps']
        ['children']
        ['billing-step']
        ['children']
        ['payment']
        ['children']
        ['renders']
        ['children']
        ['cardgate'] = $arr;
        $data = $proceed($scope);
        return $data;
    }
}
