<?php

namespace WykopApiClient;

use Error;
use stdClass;

class Tags
{
    /**
     * @var WykopApi $apiClient
     */
    private $apiClient = null;

    /**
     * Tags constructor.
     *
     * @param WykopApi  $apiClient
     */
    public function __construct($apiClient) {
        $this->apiClient = $apiClient;
    }

    /**
     * Get entries for tag.
     *
     * @param string    $tag  Tag name.
     * @param int       $page  Page number.
     *
     * @return stdClass
     *
     * @throws Error
     */
    public function getEntries(string $tag, int $page = 0) {
        if (empty($tag)) {
            throw new Error('Tag parameter for Tags::get() is required!');
        }

        return $this->apiClient->request(
			'Tags/Entries/' . $tag . '/page/' . $page . '/data/full/'
        );
    }
}
