# KIRO - Leaderboard

## Install

`npm install`

## Run (before inserting into the whole site)

Change `WEBSOCKER_URL` in **Leaderboard.js** to `localhost:8124`.

## Deploy

Once modifications work on localhost:3000, change "root" for "leaderboard" in index.js to incorporate with php.
Change `WEBSOCKER_URL` in **Leaderboard.js** to `wss://kiro.enpc.org/wss` to deploy on the online server.  
Then run `npm run build`.  
Inside the folder **build** you'll find 3 javascript files.  
Copy them into `php/scripts/` and change **php/leaderboard.php** to point to them (be sure to keep the current order for the scripts :
		runtime-main.XXXX.js
		XXXX.chunk.js
		main.XXXX.chunk.js).
This build won't work in local! If you want to test it before, do the same steps without changing `WEBSOCKER_URL`.