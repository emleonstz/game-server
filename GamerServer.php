<?php 
require('vendor/autoload.php');

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class GamerServer implements MessageComponentInterface {
    protected $clients;
    protected $rooms;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->rooms = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg);
        switch($data->command) {
            case 'create':
                $this->createRoom($from);
                break;
            case 'join':
                $this->joinRoom($from, $data->roomId);
                break;
            // ... handle other commands
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // Remove connection and handle disconnection logic
        $this->clients->detach($conn);
        $this->terminateRoomForConnection($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        // Handle error
        $conn->close();
    }

    protected function createRoom($conn) {
        $roomId = uniqid();
        $this->rooms[$roomId] = [$conn];
        $conn->send(json_encode(['type' => 'created', 'roomId' => $roomId]));
    }

    protected function joinRoom($conn, $roomId) {
        if(isset($this->rooms[$roomId]) && count($this->rooms[$roomId]) < 2) {
            $this->rooms[$roomId][] = $conn;
            $conn->send(json_encode(['type' => 'joined', 'roomId' => $roomId]));
            // Notify both players that the game can begin
            foreach($this->rooms[$roomId] as $player) {
                $player->send(json_encode(['type' => 'start']));
            }
        } else {
            $conn->send(json_encode(['type' => 'error', 'message' => 'Room full or does not exist']));
        }
    }

    protected function terminateRoomForConnection($conn) {
        foreach($this->rooms as $roomId => $players) {
            if(in_array($conn, $players, true)) {
                unset($this->rooms[$roomId]);
                foreach($players as $player) {
                    if($player !== $conn) {
                        $player->send(json_encode(['type' => 'terminated']));
                    }
                }
            }
        }
    }
}
