<?php

namespace WykopApiClient;

use Error;
use stdClass;

class PrivateMessage
{
    /**
     * @var WykopApi $apiClient
     */
    private $apiClient = null;

    /**
     * PrivateMessage constructor.
     *
     * @param WykopApi $apiClient
     */
    public function __construct($apiClient) {
        $this->apiClient = $apiClient;
    }

    /**
     * Retrieves and returns list of all user's conversations.
     *
     * @return stdClass
     *
     * @throws Error
     */
    public function getConversations() {
        if (empty($this->apiClient->getUserKey())) {
            throw new Error('You should call PrivateMessage::getConversations() method as an authorized user!');
        }

        return $this->apiClient->request(
            'Pm/ConversationsList'
        );
    }

    /**
     * Retrieves and returns all messages between current user and receiver.
     *
     * @param string    $receiver   Message's receiver username.
     *
     * @return stdClass
     *
     * @throws Error
     */
    public function getConversation($receiver) {
        if (empty($this->apiClient->getUserKey())) {
            throw new Error('You should call PrivateMessage::getConversation() method as an authorized user!');
        }

        if (empty($receiver)) {
            throw new Error('Receiver parameter for PrivateMessage::getConversation() is required!');
        }

        return $this->apiClient->request(
            'Pm/Conversation/' . $receiver
        );
    }

    /**
     * Sends a private message to specified receiver.
     *
     * @param string $receiver  Message's receiver username.
     * @param string $body      A message's content.
     * @param null   $embed     Attached image/video url address.
     *
     * @return stdClass
     *
     * @throws Error
     */
    public function sendMessage($receiver, $body, $embed = null) {
        if (empty($this->apiClient->getUserKey())) {
            throw new Error('You should call PrivateMessage::sendMessage() method as an authorized user!');
        }

        if (empty($receiver)) {
            throw new Error('Receiver parameter for PrivateMessage::sendMessage() is required!');
        }

        if (empty($body)) {
            throw new Error('Body parameter for PrivateMessage::sendMessage() is required!');
        }

        if (!empty($embed) && !filter_var($embed, FILTER_VALIDATE_URL)) {
            throw new Error('Embed parameter for PrivateMessage::sendMessage() should be an URL address!');
        }


        return $this->apiClient->request(
            'Pm/SendMessage/' . $receiver,
            [
                'body' => $body,
                'embed' => $embed
            ]
        );
    }
}
