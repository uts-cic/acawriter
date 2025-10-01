FROM node:13-alpine AS webpack
WORKDIR /usr/src/app
COPY package*.json ./
RUN npm install
COPY webpack.mix.js ./
COPY resources/js ./resources/js
COPY resources/sass ./resources/sass
RUN npm run prod

FROM nginx:1.29-alpine
WORKDIR /var/www
ARG network_mode
COPY docker/nginx/nginx.$network_mode.conf /etc/nginx/conf.d/default.conf
# /etc/nginx/nginx.awsvpc.conf will not be used. This is only an override to copy crt files only if they exist
# https://stackoverflow.com/questions/31528384/conditional-copy-add-in-dockerfile
COPY docker/nginx/nginx.awsvpc.conf *acawriter.crt *acawriter.key /etc/nginx/
COPY public/favicon.ico ./
COPY public/robots.txt ./
COPY public/images ./images
COPY --from=webpack /usr/src/app/public/css ./css
COPY --from=webpack /usr/src/app/public/js ./js
COPY --from=webpack /usr/src/app/fonts ./fonts
