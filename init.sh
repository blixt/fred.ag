#!/bin/bash
DEFAULT_CHARSET=utf-8

echo -n "Enter domain name: "
read DOMAIN
DOMAIN_ESCAPED=${DOMAIN//./\\.};

echo -n "Enter a charset [$DEFAULT_CHARSET]: "
read CHARSET
if [ -z "$CHARSET" ]; then
    CHARSET=$DEFAULT_CHARSET
fi

eval "echo \"$(cat settings.php.template)\"" > settings.php
echo "Wrote settings.php"
eval "echo \"$(cat .htaccess.template)\"" > .htaccess
echo "Wrote .htaccess"
