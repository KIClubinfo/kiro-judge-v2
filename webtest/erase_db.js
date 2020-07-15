const mysql = require('mysql');
const envDocker = process.argv[2] || true;

let databaseConnection = mysql.createConnection({
    host: envDocker ? 'db' : 'localhost',
    user: "kiro_user",
    database: "kiro",
    port: envDocker ? 3306 : 6033,
    password: process.env.mysql_password || ''
});

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