const mysql = require('mysql');
const randomstring = require('randomstring');
const populationSize = process.argv[2] || 0;
const envDocker = process.argv[3] || false;

const TEAM_NAME_LENGTH = 8;
const SCORE_MAX = 1024;

let databaseConnection = mysql.createConnection({
    host: envDocker ? 'db' : 'localhost',
    user: "kiro_user",
    database: "kiro",
    port: envDocker ? 3306 : 6033,
    password: process.env.mysql_password || ''
})

function getRandomInt(max) {
    return Math.floor(Math.random() * Math.floor(max));
}

if (databaseConnection.state === "disconnected") {
    databaseConnection.connect(function (error) {
    if (error) throw error;
        console.log("database connected.");
    })
}

for(let i = 0; i < populationSize; i++) {
    let nom = randomstring.generate(TEAM_NAME_LENGTH);
    let score = getRandomInt(SCORE_MAX);

    let sql_query = "INSERT INTO `teams` (`id`, `nom`, `score`, `classement`, `valide`, `hub`, `numero_emplacement`) VALUES (NULL, '" + nom + "', " + score + ", '0', '0', '0', '0');";

    databaseConnection.query(sql_query, function (error, result) {
        if (error) throw error;
    })
}

console.log("Database populated.");
