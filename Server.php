<?php
require('GamerServer.php');
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Server\IoServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new GamerServer()
        )
    ),
    8090
);

$server->run();

