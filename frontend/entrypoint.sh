#!/bin/sh
set -e

# ubaci PORT u nginx config
envsubst '${PORT}' < /etc/nginx/templates/default.conf.template > /etc/nginx/conf.d/default.conf

# start nginx
nginx -g 'daemon off;'
