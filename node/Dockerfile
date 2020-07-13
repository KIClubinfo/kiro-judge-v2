FROM node:12.18.2

WORKDIR /usr/src/app
COPY package.json ./
RUN npm install nodemon -g
RUN npm install

EXPOSE 8080

CMD ["nodemon", "index.js", "dev", "true"]