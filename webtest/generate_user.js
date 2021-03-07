const tools = require('./tools');
const randomstring = require('randomstring');
const bcrypt = require('bcrypt');

let USER_NAME_LENGTH = 8

let databaseConnection = tools.databaseConnection;

function getRandomInt(max) {
    return Math.floor(Math.random() * Math.floor(max));
}

// if (databaseConnection.state === "disconnected") {
//     databaseConnection.connect(function (error) {
//         if (error) throw error;
//         console.log("database connected.");
//     })
// }

let hash = '$2y$08$9TTThrthZhTOcoHELRjuN.3mJd2iKYIeNlV/CYJUWWRnDfRRw6fD2';
hash = hash.replace(/^\$2y(.+)$/i, '$2a$1');
let password = bcrypt("password", hash);
console.log(password);

