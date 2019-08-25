<?php

namespace WykopApiClient;

use Error;

class WykopConnect
{
    /**
     * @var WykopApi $apiClient
     */
    private $apiClient = null;

    /**
     * User constructor.
     *
     * @param WykopApi $apiClient
     */
    public function __construct($apiClient) {
        $this->apiClient = $apiClient;
    }


    /**
     * Returns an URL to Wykop Connect.
     *
     * @param string $redirectUrl   An URL address that you want to redirect an user after login to Wykop.
     *
     * @return string
     */
    public function getUrl($redirectUrl) {
        $encodedRedirectUrl = urlencode(base64_encode($redirectUrl));
        $secure = md5($this->apiClient->getAppSecret() . $redirectUrl);

        return $this->apiClient->getApiUrl() . '/login/connect/appkey/' . $this->apiClient->getAppKey() . '/redirect/' . $encodedRedirectUrl . '/secure/' . $secure;
    }

    /**
     * Retrieves data returned from the API.
     *
     * @return object
     *
     * @throws Error
     */
    public function getConnectData() {
        if (empty($_GET['connectData'])) {
            throw new Error('Connect data cannot be retrieved from a URL address!');
        }

        $connectData = json_decode(base64_decode($_GET['connectData']));
        $expectedSign = md5($this->apiClient->getAppSecret() . $this->apiClient->getAppKey() . $connectData->login . $connectData->token);

        if ($connectData->sign !== $expectedSign) {
            throw new Error('Connect data cannot be verified!');
        }

        return $connectData;
    }
}
