<?php
namespace App\Libraries\PaySera\WebToPay\Sign;
use App\Libraries\PaySera\WebToPay\Exception\WebToPay_Exception_Callback;
use App\Libraries\PaySera\WebToPay\WebToPay_Util;

/**
 * Checks SS2 signature. Depends on SSL functions
 */
class SS2SignChecker implements SignCheckerInterface {

    /**
     * @var string
     */
    protected $publicKey;

    /**
     * @var WebToPay_Util
     */
    protected $util;

    /**
     * Constructs object
     *
     * @param string        $publicKey
     * @param WebToPay_Util $util
     */
    public function __construct($publicKey, WebToPay_Util $util) {
        $this->publicKey = $publicKey;
        $this->util = $util;
    }

    /**
     * Checks signature
     *
     * @param array $request
     *
     * @return boolean
     *
     * @throws WebToPay_Exception_Callback
     */
    public function checkSign(array $request) {
        if (!isset($request['data']) || !isset($request['ss2'])) {
            throw new WebToPay_Exception_Callback('Not enough parameters in callback. Possible version mismatch');
        }

        $ss2 = $this->util->decodeSafeUrlBase64($request['ss2']);
        $ok = openssl_verify($request['data'], $ss2, $this->publicKey);
        return $ok === 1;
    }
}