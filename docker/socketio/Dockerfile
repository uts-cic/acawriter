FROM node:13-alpine

WORKDIR /usr/src/app

COPY socket-server/package*.json ./
#COPY socket-server/node_modules .

RUN npm ci --only=production

COPY socket-server/server.js ./

EXPOSE 3000
CMD [ "node", "server.js" ]
