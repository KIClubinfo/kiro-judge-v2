# Run scripts to ensure that the db is fully created

FROM node:12.18.2-alpine3.9
RUN apk add --no-cache mysql-client

WORKDIR /usr/src/app/

COPY package.json .
RUN npm install

COPY . .

# To keep it running in backgroung
CMD tail -f /dev/null
