FROM nginx:alpine

# Kopiraj statiku
COPY . /usr/share/nginx/html

# Nginx template + entrypoint
COPY nginx.conf.template /etc/nginx/templates/default.conf.template
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Railway daje PORT
ENV PORT=8080

CMD ["/entrypoint.sh"]
