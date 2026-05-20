FROM webdevops/php-nginx:8.2-alpine

# Copy and use custom entrypoint script
COPY entrypoint.sh /init-entrypoint.sh
RUN chmod +x /init-entrypoint.sh

# Replace the default entrypoint with our custom one
ENTRYPOINT ["/init-entrypoint.sh"]
