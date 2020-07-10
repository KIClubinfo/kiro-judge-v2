'use strict';

const PORT = 8080;
const ALLOWED_ORIGIN = ["kiro.enpc.org", "cxhome.org"]

let env = process.argv[2] || 'dev';


const WebSocketServer = require('websocket').server;
const http = require('http');
const mysql = require('mysql');

// On ouvre la connection à la bdd
let databaseConnection = mysql.createConnection({
    host: "localhost",
    user: "kiro_user",
    database: "kiro",
    port: 6033,
    password: process.env.mysql_password || ''
})

databaseConnection.connect(function (error) {
    if (error) throw error;
    console.log("database connected.");

    databaseConnection.query("SHOW TABLES;", function (error, result) {
        if (error) throw error;
        console.log("Result: " + result.toString());
    })
})

let server = http.createServer(function (request, response) {
    let origin = request.headers.host;
    if (acceptConnectionFrom(origin)) {
        console.log((new Date()) + ' Received request for ' + request.url);
        if (request.url === '/refresh') {
            // Do some stuff here...
            response.writeHead(200);
        } else {
            response.writeHead(404);
        }
    } else {
        console.log('Connection refused from : ' + origin);
        response.writeHead(403);
    }

    response.end();
})


server.listen(PORT, function () {
    console.log((new Date()) + ' Server is listening on port ' + PORT);
})

let wsServer = new WebSocketServer({
    httpServer: server,
    autoAcceptConnections: false
})

function acceptConnectionFrom(origin) {
    return (env === 'dev') ? true : ALLOWED_ORIGIN.includes(origin);
}

let connections = {};
let connectionIDCounter = 0;

wsServer.on('request', function (request) {
    if (!acceptConnectionFrom(request.origin)) {
        // Make sure we only accept requests from an allowed origin
        request.reject();
        console.log((new Date()) + ' Connection from origin ' + request.origin + ' rejected.');
        return;
    }

    let connection = request.accept('', request.origin);
    connection.id = connectionIDCounter++;
    connections[connection.id] = connection;

    console.log((new Date()) + ' Connection accepted with Connection ID : ' + connection.id);
    connection.on('message', function(message) {
        if (message.type === 'utf8') {
            console.log('Received Message: ' + message.utf8Data);
            connection.sendUTF(message.utf8Data);
        }
        else if (message.type === 'binary') {
            console.log('Received Binary Message of ' + message.binaryData.length + ' bytes');
            connection.sendBytes(message.binaryData);
        }
    });
    connection.on('close', function(reasonCode, description) {
        console.log((new Date()) + ' Connection ' + connection.id + ' disconnected.');
        delete connections[connection.id];
    });
});

// Broadcast to all open connections
function broadcast(data) {
    Object.keys(connections).forEach(function(key) {
        let connection = connections[key];
        if (connection.connected) {
            connection.send(data);
        }
    });
}

// Send a message to a connection by its connectionID
function sendToConnectionId(connectionID, data) {
    let connection = connections[connectionID];
    if (connection && connection.connected) {
        connection.send(data);
    }
}

