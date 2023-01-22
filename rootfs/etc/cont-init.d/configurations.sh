#!/usr/bin/with-contenv sh

echo=echo
for cmd in echo /bin/echo; do
	$cmd >/dev/null 2>&1 || continue
	if ! $cmd -e "" | grep -qE '^-e'; then
		echo=$cmd
		break
	fi
done

cli=$($echo -e "\033[")
norm="${cli}0m"
bold="${cli}1;37m"
red="${cli}1;31m"
green="${cli}1;32m"
yellow="${cli}1;33m"
blue="${cli}1;34m"

echo -e "\n${bold}Nginx/PHP Configuration${norm}\n"

# General
USER=${USER:-nginx-php}
TZ=${TZ:-UTC}

# PHP
MEMORY_LIMIT=${MEMORY_LIMIT:-512M}
UPLOAD_MAX_SIZE=${UPLOAD_MAX_SIZE:-16M}
CLEAR_ENV=${CLEAR_ENV:-yes}
OPCACHE_MEM_SIZE=${OPCACHE_MEM_SIZE:-256}
MAX_FILE_UPLOADS=${MAX_FILE_UPLOADS:-50}

# Timezone
echo "  ${norm}[${green}+${norm}] Setting timezone to ${green}${TZ}${norm}..."
ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime
echo ${TZ} > /etc/timezone

# Fix permissions
chown ${PUID}:${PGID} /proc/self/fd/1 /proc/self/fd/2 || true
if [ -n "${PGID}" ] && [ "${PGID}" != "$(id -g ${USER})" ]; then
  sed -i -e "s/^${USER}:\([^:]*\):[0-9]*/${USER}:\1:${PGID}/" /etc/group
  sed -i -e "s/^${USER}:\([^:]*\):\([0-9]*\):[0-9]*/${USER}:\1:\2:${PGID}/" /etc/passwd
fi
if [ -n "${PUID}" ] && [ "${PUID}" != "$(id -u ${USER})" ]; then
  sed -i -e "s/^${USER}:\([^:]*\):[0-9]*:\([0-9]*\)/${USER}:\1:${PUID}:\2/" /etc/passwd
fi

# PHP
echo "  ${norm}[${green}+${norm}] Setting PHP-FPM configuration..."
sed -e "s/@MEMORY_LIMIT@/$MEMORY_LIMIT/g" \
    -e "s/@UPLOAD_MAX_SIZE@/$UPLOAD_MAX_SIZE/g" \
    -e "s/@CLEAR_ENV@/$CLEAR_ENV/g" \
    -i /etc/php81/php-fpm.d/www.conf

echo "  ${norm}[${green}+${norm}] Setting PHP INI configuration..."
sed -e "s|memory_limit.*|memory_limit = ${MEMORY_LIMIT}|g" \
    -e "s|;date\.timezone.*|date\.timezone = ${TZ}|g" \
    -e "s|max_file_uploads.*|max_file_uploads = ${MAX_FILE_UPLOADS}|g"  \
    -i /etc/php81/php.ini

# OpCache
echo "  ${norm}[${green}+${norm}] Setting OpCache configuration..."
sed -e "s/@OPCACHE_MEM_SIZE@/$OPCACHE_MEM_SIZE/g" \
    -i /etc/php81/conf.d/opcache.ini

# Init
echo "  ${norm}[${green}+${norm}] Setting files and folders..."
mkdir -p /passwd \
  /etc/nginx/conf.d \
  /var/cache/nginx \
  /var/lib/nginx \
  /var/run/nginx \
  /var/run/php-fpm \
  /var/www

# Perms
echo "  ${norm}[${green}+${norm}] Fixing perms..."
chown -R ${USER}: \
  /passwd \
  /var/cache/nginx \
  /var/lib/nginx \
  /var/log/nginx \
  /var/log/php81 \
  /var/run/nginx \
  /var/run/php-fpm \
  /var/www

echo -e "  ${norm}[${green}+${norm}] Settings services...\n"
mkdir -p /etc/services.d/nginx
cat > /etc/services.d/nginx/run <<EOL
#!/usr/bin/execlineb -P
with-contenv
s6-setuidgid ${PUID}:${PGID}
nginx -g "daemon off;"
EOL
chmod +x /etc/services.d/nginx/run

mkdir -p /etc/services.d/php-fpm
cat > /etc/services.d/php-fpm/run <<EOL
#!/usr/bin/execlineb -P
with-contenv
s6-setuidgid ${PUID}:${PGID}
php-fpm81 -F
EOL
chmod +x /etc/services.d/php-fpm/run