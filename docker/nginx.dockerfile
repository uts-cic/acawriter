FROM node:13-alpine AS webpack
WORKDIR /usr/src/app
COPY package*.json ./
RUN npm ci
COPY webpack.mix.js ./
COPY resources/assets ./resources/assets
RUN npm run prod

FROM nginx:1.17-alpine
WORKDIR /var/www
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY public/favicon.ico ./
COPY public/robots.txt ./
COPY public/images ./images
COPY --from=webpack /usr/src/app/public/css ./css
COPY --from=webpack /usr/src/app/public/js ./js
COPY --from=webpack /usr/src/app/fonts ./fonts
