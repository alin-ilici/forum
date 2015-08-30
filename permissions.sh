# Run this file only one time, the first time when you setup the application.
# It will set the required permissions so that the browser can write content where it needs.
# Refference: http://symfony.com/doc/current/book/installation.html#book-installation-permissions

HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs

sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX src/Forum/CoreBundle/Resources/public/messages_uploads src/Forum/CoreBundle/Resources/public/private_messages_uploads src/Forum/CoreBundle/Resources/public/users_avatars
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX src/Forum/CoreBundle/Resources/public/messages_uploads src/Forum/CoreBundle/Resources/public/private_messages_uploads src/Forum/CoreBundle/Resources/public/users_avatars

# You should also run ./repair.sh after running this script.