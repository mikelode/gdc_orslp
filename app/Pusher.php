<?php

namespace aidocs;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Pusher implements WampServerInterface {

    protected $subscribedTopics = array();

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        $this->subscribedTopics[$topic->getId()] = $topic;
    }

    public function onBlogEntry($entry)
    {
        $entryData = json_decode($entry, true);

        if(!array_key_exists($entryData['tipo'], $this->subscribedTopics))
        {
            return;
        }

        $topic = $this->subscribedTopics[$entryData['tipo']];
        $topic->broadcast($entryData);
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {

    }

    public function onOpen(ConnectionInterface $conn)
    {

    }

    public function onClose(ConnectionInterface $conn)
    {

    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        $conn->callError($id, $topic, 'No posee permiso para realizar llamadas')->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        $conn->close();
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {

    }
} 