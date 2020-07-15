const mysql = require('mysql');
const tools = require('./tools');

let databaseConnection = tools.databaseConnection;

if (databaseConnection.state === "disconnected") {
    databaseConnection.connect(function (error) {
        if (error) throw error;
        console.log("database connected.");
    })
}

let sql_request = "DELETE FROM `teams`;";

databaseConnection.query(sql_request, function (error, results) {
    if (error) throw error;
})