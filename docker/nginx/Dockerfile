FROM node:13-alpine AS webpack
WORKDIR /usr/src/app
COPY package*.json ./
RUN npm ci
COPY webpack.mix.js ./
COPY resources/js ./resources/js
COPY resources/sass ./resources/sass
RUN npm run prod

FROM nginx:1.17-alpine
WORKDIR /var/www
ARG network_mode
COPY docker/nginx/nginx.$network_mode.conf /etc/nginx/conf.d/default.conf
# COPY acawriter.crt /etc/nginx/acawriter.crt
# COPY acawriter.key /etc/nginx/acawriter.key
COPY public/favicon.ico ./
COPY public/robots.txt ./
COPY public/images ./images
COPY --from=webpack /usr/src/app/public/css ./css
COPY --from=webpack /usr/src/app/public/js ./js
COPY --from=webpack /usr/src/app/fonts ./fonts
