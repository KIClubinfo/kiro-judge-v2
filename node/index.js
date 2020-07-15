'use strict';

const PORT = 8080;
const ALLOWED_ORIGIN = ["kiro.enpc.org", "cxhome.org"]

const envType = process.argv[2] || 'dev';
const envDocker = (process.argv[3] === 'true') || false;

const WebSocketServer = require('websocket').server;
const http = require('http');
const mysql = require('mysql');

let last_result;

// On explique comment ouvrir une connection avec la bdd
let databaseConnection = mysql.createConnection({
    host: envDocker ? 'db' : 'localhost',
    user: "kiro_user",
    database: "kiro",
    port: envDocker ? 3306 : 6033,
    password: process.env.mysql_password || ''
})

function updateLeaderboard(results) {
    results.forEach(function(element, index) {
        if (element.classement === (index + 1)) return;
        let sql_update_request = "UPDATE teams SET classement = " + (index + 1) + " WHERE id = " + element.id + ";";

        databaseConnection.query(sql_update_request, (error, results) => {
            if (error) throw error;
        })
    })

    for (let i = 0; i < results.length; i++) {
        results[i].classement = (i + 1);
    }

    console.log("Leaderboard updated.");
}

function notifyTeams(results) {
    console.log(JSON.stringify(results))
    broadcast(JSON.stringify(results));
}

function updateLeaderboardAndNotify() {
    // On check si on est déjà connecté, sinon on se connecte.
    if (databaseConnection.state === "disconnected") {
        databaseConnection.connect(function (error) {
            if (error) throw error;
            console.log("database connected.");
        })
    }

    // On récupére l'ensemble des équipes avec leur score, dans l'ordre décroissant
    let sql_request = "SELECT * FROM teams ORDER BY score DESC;";

    databaseConnection.query(sql_request, function (error, results) {
        if (error) throw error;
        last_result = results;
        updateLeaderboard(results);
        notifyTeams(results);
    });
}

let server = http.createServer(function (request, response) {
    let origin = request.headers.host;
    if (acceptConnectionFrom(origin)) {
        console.log((new Date()) + ' Received request for ' + request.url);
        if (request.url === '/refresh') {
            // Do some stuff here...
            updateLeaderboardAndNotify();
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
    return (envType === 'dev') ? true : ALLOWED_ORIGIN.includes(origin);
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
    if (!last_result) {
        updateLeaderboardAndNotify();
    } else {
        sendToConnectionId(connection.id, JSON.stringify(last_result))
    }
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