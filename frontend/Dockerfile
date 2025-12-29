FROM nginx:alpine

# Copy frontend files
COPY . /usr/share/nginx/html

# Nginx template
COPY nginx.conf.template /etc/nginx/templates/default.conf.template

# Railway provides $PORT
CMD ["/bin/sh", "-c", "envsubst '$PORT' < /etc/nginx/templates/default.conf.template > /etc/nginx/conf.d/default.conf && nginx -g 'daemon off;'"]
