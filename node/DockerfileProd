FROM node:12.18.2

WORKDIR /usr/src/app
COPY package.json ./
RUN npm install --production

COPY . .
EXPOSE 8080

CMD ["node", "index.js", "prod", "true"]