<?php

namespace WykopApiClient;

use Error;
use stdClass;

class WykopApi
{
    /**
     * @var string  $apiUrl     Wykop API address.
     */
    private $apiUrl = 'https://a2.wykop.pl';

    /**
     * @var string  $appKey     Application's key.
     */
    private $appKey = null;

    /**
     * @var string  $appSecret  Application's secret.
     */
    private $appSecret = null;

    /**
     * @var string  $userKey    User's key.
     */
    private $userKey = null;

    /**
     * @var string  $apiClientUseragent     Useragent string used in requests to API.
     */
    private $apiClientUseragent = 'WykopAPI Client';

    public $wykopConnect = null;
    public $user = null;
    public $profile = null;
    public $privateMessage = null;

    /**
     * WykopApi constructor.
     *
     * @param string    $appKey     Application's key.
     * @param string    $appSecret  Application's secret.
     */
    public function __construct($appKey, $appSecret) {
        if (empty($appKey)) {
            throw new Error('Application\'s key for WykopApi::_construct() is required!');
        }

        if (empty($appSecret)) {
            throw new Error('Application\'s secret for WykopApi::_construct() is required!');
        }

        $this->appKey = $appKey;
        $this->appSecret = $appSecret;

        $this->wykopConnect = new WykopConnect($this);
        $this->user = new User($this);
        $this->profile = new Profile($this);
        $this->tags = new Tags($this);
        $this->entries = new Entries($this);
        $this->privateMessage = new PrivateMessage($this);
    }

    /**
     * Sets useragent string used in requests to API.
     *
     * @param string    $useragent  Useragent string.
     *
     * @return $this
     */
    public function setApiClientUseragent($useragent) {
        $this->apiClientUseragent = $useragent;

        return $this;
    }

    /**
     * Authorizes user.
     *
     * @param string    $userKey    User's key retrieved from api.
     *
     * @return $this
     */
    public function authorize($userKey) {
        if (empty($userKey)) {
            throw new Error('User\'s key for WykopAPI::authorize() is required!');
        }

        $this->userKey = $userKey;

        return $this;
    }

    /**
     * Returns API URL.
     *
     * @return string
     */
    public function getApiUrl() {
        return $this->apiUrl;
    }

    /**
     * Returns app's key.
     *
     * @return string
     */
    public function getAppKey() {
        return $this->appKey;
    }

    /**
     * Returns app's secret.
     *
     * @return string
     */
    public function getAppSecret() {
        return $this->appSecret;
    }

    /**
     * Returns user's key.
     *
     * @return string
     */
    public function getUserKey() {
         return $this->userKey;
    }

    /**
     * Returns a request sign.
     *
     * @link    https://www.wykop.pl/dla-programistow/apiv2docs/podpisywanie-zadan/
     *
     * @param $url
     * @param $postData
     *
     * @return string
     */
    private function getRequestSign($url, $postData) {
        return md5($this->appSecret . $url . (empty($postData) ? '' : implode(',', $postData)));
    }


    /**
     * Removes user's authorization.
     * Works like a logout, but keeps old key valid.
     *
     * @return $this
     */
    public function removeAuthorization() {
        $this->userKey = null;

        return $this;
    }

    /**
     * Makes request to the Wykop API.
     *
     * @param string $path      A path to the API that you want to call.
     * @param null   $postData  Data that should be send in a POST request.
     *
     * @return stdClass
     *
     * @throws Error
     */
    public function request($path, $postData = null) {
        $userKey = ($this->userKey) ? ('userkey/' . $this->userKey) : '';
        $path = $this->apiUrl . '/' . $path . '/appkey/' . $this->appKey . '/' . $userKey;

        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_ENCODING => '',
            CURLOPT_USERAGENT => $this->apiClientUseragent,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTPHEADER => ['apisign:' . $this->getRequestSign($path, $postData)],
            CURLOPT_SSLVERSION => 1
        );

        if ($postData !== null) {
            $post_value = is_array($postData) ? http_build_query($postData, 'f_' , '&') : '';
            $options[CURLOPT_POST] = 1;
            $options[CURLOPT_POSTFIELDS] = $post_value;
        }

        $ch  = curl_init($path);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $errorMessage = curl_error($ch);
        curl_close($ch);

        $responseContent = json_decode($content);

        if (!empty($responseContent->error)) {
            throw new Error('Request to the Wykop API failed due to following error: ' . $responseContent->error->message_en);
        }

        if (!empty($errorMessage)) {
            throw new Error('Request to the Wykop API failed due to following error: ' . $errorMessage);
        }

        return $responseContent;
    }
}
