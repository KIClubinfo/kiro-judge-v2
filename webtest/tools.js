const mysql = require('mysql');

module.exports = {
    databaseConnection: mysql.createConnection({
        host: 'db',
        user: "kiro_user",
        database: "kiro",
        port: 3306,
        password: process.env.mysql_password || ''
    })
}