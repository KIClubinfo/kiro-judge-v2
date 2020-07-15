const http = require('http');

http.get('http://node_12:8080/refresh', res => {
   process.exit(0);
});