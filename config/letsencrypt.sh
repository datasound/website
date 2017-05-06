#!/usr/bin/sh
certbot --email mattmezza@gmail.com --agree-tos --apache -d ${SERVER_NAME} --non-interactive