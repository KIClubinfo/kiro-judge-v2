# KIRO - Leaderboard

## Install

`npm install`

## Run (before inserting into the whole site)

Change `WEBSOCKER_URL` in **Leaderboard.js** to `ws://localhost:8125`.

## Deploy

Change `WEBSOCKER_URL` in **Leaderboard.js** to `wss://kiro.enpc.org/wss`.  
Then run `npm run build`.  
Inside the folder **build** you'll find 3 javascript files.  
Copy them into `php/scripts/` and change **php/leaderboard.php** to point to them.  
This build won't work in local! If you want to test it before, do the same steps without changing `WEBSOCKER_URL`.