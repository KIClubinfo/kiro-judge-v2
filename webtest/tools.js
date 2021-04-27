const mysql = require('mysql');

module.exports = {
    databaseConnection: mysql.createConnection({
        host: 'localhost',
        user: "kiro_user",
        database: "kiro",
        port: 6033,
        password: process.env.mysql_password || 'NotSecretPassword'
    })
}