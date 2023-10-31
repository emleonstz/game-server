<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSocket Game Client</title>
</head>

<body>
    <button id="createRoom">Create Mactch Code</button>
    <input type="text" id="roomIdInput" placeholder="Enter Room ID to join">
    <button id="joinRoom">Join Room</button>
</body>

</html>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Connect to the WebSocket server
        let socket = new WebSocket("ws://localhost:8090");

        socket.onopen = function(e) {
            console.log("Connection established");
        };

        socket.onerror = function(error) {
            console.log(`WebSocket Error: ${error}`);
        };

        socket.onmessage = function(event) {
            let message = JSON.parse(event.data);
            switch (message.type) {
                case 'created':
                    console.log("Room created with ID:", message.roomId);
                    break;
                case 'joined':
                    console.log("Joined room:", message.roomId);
                    break;
                case 'start':
                    console.log("Game starting!");
                    break;
                case 'terminated':
                    console.log("Game terminated due to player disconnection");
                    break;
                case 'error':
                    console.log("Error:", message.message);
                    break;
            }
        };

        // Example functions to send messages to the server

        document.getElementById("createRoom").addEventListener("click", function() {
            let message = {
                command: "create"
            };
            socket.send(JSON.stringify(message));
        });

        document.getElementById("joinRoom").addEventListener("click", function() {
            let roomId = document.getElementById("roomIdInput").value;
            let message = {
                command: "join",
                roomId: roomId
            };
            socket.send(JSON.stringify(message));
        });

    });
</script>