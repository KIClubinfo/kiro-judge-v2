# Run scripts to ensure that the db is fully created

FROM alpine:3.7
RUN apk add --no-cache mysql-client

WORKDIR /usr/src/app/
COPY webinstall.sh .
RUN chmod +x ./webinstall.sh

COPY . .

CMD ["sh", "-c", "/usr/src/app/webinstall.sh"]
