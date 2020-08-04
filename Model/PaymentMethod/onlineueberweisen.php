<?php
/**
 * Copyright (c) 2018 CardGate B.V.
 * All rights reserved.
 * See LICENSE for license details.
 */
namespace Cardgate\Payment\Model\PaymentMethod;

/**
 * OnlineÜberweisen class.
 * @author DBS B.V.
 * @package Magento2
 */
class onlineueberweisen extends \Cardgate\Payment\Model\PaymentMethods {

	/**
	 * Payment method code
	 *
	 * @var string
	 */
	protected $code = 'cardgate_onlineueberweisen';

}