<?php

namespace WykopApiClient;

use Error;

class User
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
     * Retrieves user key and saves it to use later in next requests.
     *
     * @param string    $username       An username that you want to log in.
     * @param string    $accountKey     A generated account key of the user.
     *
     * @return array
     */
    public function login($username, $accountKey) {
        if (empty($username)) {
            throw new Error('Username parameter for User::login() is required!');
        }

        if (empty($accountKey)) {
            throw new Error('Account parameter for User::login() is required!');
        }

        $request = $this->apiClient->request(
            'Login/Index/',
            [
                'accountkey' => $accountKey,
                'login' => $username
            ]
        );

        $this->apiClient->authorize($request->data->userkey);

        return $request;
    }
}
