<?php

namespace WykopApiClient;

use Error;
use stdClass;

class Profile
{
    /**
     * @var WykopApi $apiClient
     */
    private $apiClient = null;

    /**
     * Profile constructor.
     *
     * @param WykopApi  $apiClient
     */
    public function __construct($apiClient) {
        $this->apiClient = $apiClient;
    }

    /**
     * Retrieves and returns user profile.
     *
     * @param string    $username  An username that you want to retrieve data for.
     *
     * @return stdClass
     *
     * @throws Error
     */
    public function get($username) {
        if (empty($username)) {
            throw new Error('Username parameter for Profile::get() is required!');
        }

        return $this->apiClient->request(
            'Profiles/Index/' . $username
        );
    }

    /**
     * Retrieves and returns user actions.
     *
     * @param string    $username  An username that you want to retrieve data for.
     *
     * @return stdClass
     *
     * @throws Error
     */
    public function getActions($username) {
        if (empty($username)) {
            throw new Error('Username parameter for Profile::getActions() is required!');
        }

        return $this->apiClient->request(
            'Profiles/Actions/' . $username
        );
    }

    /**
     * Retrieves and returns users colors.
     *
     * @return stdClass
     */
    public function getAvailableColors() {
        return $this->apiClient->request(
            'Profiles/AvailableColors'
        );
    }

    /**
     * Retrieves and returns user's entries.
     *
     * @param string    $username    An username that you want to retrieve data for.
     * @param int|null  $page        One-based page number.
     *
     * @return stdClass
     *
     * @throws Error
     */
    public function getEntries($username, $page = 1) {
        if (empty($username)) {
            throw new Error('Username parameter for Profile::getEntries() is required!');
        }

        if (!empty($page) && !is_numeric($page)) {
            throw new Error('Page parameter for Profile::getEntries() should be a numeric value!');
        }

        return $this->apiClient->request(
            'Profiles/Entries/' . $username . '/page/' . $page
        );
    }
}
