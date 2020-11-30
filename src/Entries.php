<?php

namespace WykopApiClient;

use Error;
use stdClass;

class Entries
{
    /**
     * @var WykopApi $apiClient
     */
    private $apiClient = null;

    /**
     * Entries constructor.
     *
     * @param WykopApi  $apiClient
     */
    public function __construct($apiClient) {
        $this->apiClient = $apiClient;
    }

    /**
     * Posts a new entry.
     *
     * @param string $body          A message's content.
     * @param null   $embed         Attached image/video url address.
     * @param bool   $adultMedia    Embed media contains a content for adults.
     *
     * @return stdClass
     *
     * @throws Error
     */
    public function post($body, $embed = null, $adultMedia = false) {
        if (empty($this->apiClient->getUserKey())) {
            throw new Error('You should call Entries::post() method as an authorized user!');
        }

        if (empty($body)) {
            throw new Error('Body parameter for Entries::post() is required!');
        }

        if (!empty($embed) && !filter_var($embed, FILTER_VALIDATE_URL)) {
            throw new Error('Embed parameter for Entries::post() should be an URL address!');
        }

        if (!is_bool($adultMedia)) {
            throw new Error('Adult media parameter for Entries::post() should be a boolean value!');
        }

        return $this->apiClient->request(
            'Entries/Add/',
            [
                'body' => $body,
                'embed' => $embed,
                'adultmedia' => $adultMedia
            ]
        );
    }
}
