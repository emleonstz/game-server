
---

# WebSocket Game Server with Ratchet

A simple multiplayer game server with Ratchet WebSocket. It allows players to join game in pairs, Once a room is full, new players will need to create or join another room. If a player disconnects, the game in that room is terminated.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [API](#api)
- [Contributing](#contributing)
- [License](#license)
## Requirements

- PHP >= 7.4
- Composer
- Ratchet Websocket PHP library

## Installation

1. Clone the repository:

```bash
git clone https://github.com/emleonstz/game-server.git
cd game-server
```

2. Install dependencies with Composer:

```bash
composer install
```

3. Run the WebSocket server:

```bash
php Server.php
```

The server will start and listen on the specified port (e.g., `8090`).

## Usage

To connect to the server, use WebSocket in JavaScript:

```javascript
const socket = new WebSocket("ws://localhost:8090");
```

### Generate match code

Open example.php in a browser window click generate match code button check macth code from console and copy it or send a message as an object.

```javascript
socket.send(JSON.stringify({ command: "create" }));
```

### Joining a Room

Open example.php in a  browser window pest match code then click join room button check in console if your are connected with opponent.

```javascript
socket.send(JSON.stringify({ command: "join", roomId: "specified_match_code" }));
```

### Fetching Active Rooms

```javascript
socket.send(JSON.stringify({ command: "listRooms" }));
```

## API

| Command     | Description                      | Payload                          |
|-------------|----------------------------------|----------------------------------|
| `create`    | Create a new game room.          | None                             |
| `join`      | Join an existing game room.      | `{ roomId: "specified_room_id" }`|
| `listRooms` | Fetch list of active game rooms. | None (Don use it for now)                             |


---
