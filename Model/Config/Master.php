<?php
/**
 * Copyright (c) 2018 CardGate B.V.
 * All rights reserved.
 * See LICENSE for license details.
 */
namespace Cardgate\Payment\Model\Config;

use Magento\Framework\App\ObjectManager;

/**
 *
 * @author DBS B.V.
 *
 */
class Master
{

    /**
     *
     * @var array
     */
    private $paymentMethodIds = [];

    /**
     *
     * @var array
     */
    private $paymentMethodCodes = [];

    /**
     *
     * @var \Magento\Framework\App\Cache\Type\Collection
     */
    private $cache;

    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;

    private const CACHEKEY = "cgAllPM";

    /**
     *
     * @var \Cardgate\Payment\Model\Config
     */
    private $config;

    /**
     *
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;

    /**
     * @param \Magento\Framework\App\Cache\Type\Collection $cache
     * @param \Magento\Framework\Filesystem $filesystem
     */
    public function __construct(
        \Magento\Framework\App\Cache\Type\Collection $cache,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $serializer = $objectManager->create(\Magento\Framework\Serialize\SerializerInterface::class);
        $this->serializer = $serializer;
        $this->cache = $cache;
        $this->filesystem = $filesystem;
        if ($this->cache->test(self::CACHEKEY) !== false) {
            try {
                $cachedPMs = $serializer->unserialize($this->cache->load(self::CACHEKEY));
            } catch (\Exception $e) {
                $cachedPMs = [];
            }
            $this->paymentMethodIds = $cachedPMs;
        }
        if (!is_array($this->paymentMethodIds) || count($this->paymentMethodIds) < 1) {
            $this->updatePaymentMethods();
        }

        $this->paymentMethodCodes = [];
        foreach ($this->paymentMethodIds as $paymentMethod => $paymentMethodName) {
            $this->paymentMethodCodes["cardgate_{$paymentMethod}"] = $paymentMethodName;
        }
    }

    /**
     * Set CardGate config
     *
     * @param \Cardgate\Payment\Model\Config $config
     */
    public function setConfig(\Cardgate\Payment\Model\Config $config)
    {
        $this->config = $config;
    }

    /**
     * Get Payment method classname by Code
     *
     * @param string $paymentMethodCode
     * @param string $fullClassName
     * @return string
     */
    public function getPMClassByCode($paymentMethodCode, $fullClassName = true)
    {
        return ( $fullClassName ? 'Cardgate\\Payment\\Model\\PaymentMethod\\' : '' ) . substr($paymentMethodCode, 9);
    }

    /**
     * Get Payment method instance by Code.
     * $force can be set to ensure class exists (or create it if not exists)
     *
     * @param string $paymentMethodCode
     * @return \Cardgate\Payment\Model\PaymentMethods
     */
    public function getPMInstanceByCode($paymentMethodCode)
    {
        return ObjectManager::getInstance()->get($this->getPMClassByCode($paymentMethodCode));
    }

    /**
     * Test if Paymentmethod ID exists (CardGate style ID)
     *
     * @param string $paymentMethodId
     * @return boolean
     */
    public function hasPMId($paymentMethodId)
    {
        return isset($this->paymentMethodIds[$paymentMethodId]);
    }

    /**
     * Tests if Payment method Code is a CardGate Code
     *
     * @param unknown $paymentMethodCode
     * @return boolean
     */
    public function isCardgateCode($paymentMethodCode)
    {
        return ( substr($paymentMethodCode, 0, 9) == 'cardgate_' );
    }

    /**
     * Get Payment method Code for a given PM ID (CardGate style ID)
     *
     * @param unknown $paymentMethodId
     * @return string
     */
    public function getPMCodeById($paymentMethodId)
    {
        return 'cardgate_' . $paymentMethodId;
    }

    /**
     * Load Payment methods and save them in cache
     *
     * @return void
     */
    private function updatePaymentMethods()
    {
        // YYY: Make dynamic
        $this->paymentMethodIds = [
            'ideal' => 'iDeal',
            'mistercash' => 'Bancontact',
            'creditcard' => 'Creditcard',
            'paysafecard' => 'PaySafeCard',
            'paysafecash' => 'Paysafecash',
            'sofortbanking' => 'SofortBanking',
            'paypal' => 'PayPal',
            'klarna' => 'Klarna',
            'clickandbuy' => 'ClickandBuy',
            'afterpay' => 'Afterpay',
            'directdebit' => 'DirectDebit',
            'przelewy24' => 'Przelewy24',
            'safeklick' => 'Safeklick',
            'banktransfer' => 'Bank transfer',
            'giftcard' => 'Gift Card',
            'capayable' => 'Capayable',
            'bitcoin' => 'Bitcoin',
            'belfius' => 'Belfius',
            'billink' => 'Billink',
            'idealqr' => 'iDEAL QR',
            'onlineueberweisen' => 'OnlineÜberweisen',
            'spraypay' =>'SprayPay'
        ];
        $this->cache->save($this->serializer->serialize($this->paymentMethodIds), self::CACHEKEY, [], 24 * 3600);
    }

    /**
     * Get the CardGate Payment methods
     *
     * @return array
     */
    public function getCardgateMethods()
    {
        return $this->paymentMethodIds;
    }

    /**
     *  Get the payment methods
     *
     * @param bool $bIncludingNames
     *
     * @return array|int[]|string[]
     */
    public function getPaymentMethods($bIncludingNames = false)
    {
        if ($bIncludingNames) {
            return $this->paymentMethodCodes;
        } else {
            return array_keys($this->paymentMethodCodes);
        }
    }
}
