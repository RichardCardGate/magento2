<?php
/**
 * Copyright (c) 2018 CardGate B.V.
 * All rights reserved.
 * See LICENSE for license details.
 */
namespace Cardgate\Payment\Model\PaymentMethod;

/**
 * iDeal class with custom renderer.
 * @author DBS B.V.
 * @package Magento2
 */
class ideal extends \Cardgate\Payment\Model\PaymentMethods {

	/**
	 * Payment method code
	 *
	 * @var string
	 */
	protected $code = 'cardgate_ideal';

	/**
	 * Renderer template name
	 *
	 * @var string
	 */
	public static $renderer = 'ideal';
}
