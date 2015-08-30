# Run this file whenever you have a problem.
# It is most likely to be resolved by the actions happening in this script!
# It copies the content from the web directory to your public directory from Resources,
# then clears the caches and reinstalls assets as hardlinks (very important to be that way,
# else you won't have access to write from browser in them).

sudo cp -Rf web/bundles/core/messages_uploads/* src/Forum/CoreBundle/Resources/public/messages_uploads/
sudo cp -Rf web/bundles/core/private_messages_uploads/* src/Forum/CoreBundle/Resources/public/private_messages_uploads/
sudo cp -Rf web/bundles/core/users_avatars/* src/Forum/CoreBundle/Resources/public/users_avatars/

app/console cache:clear --env=dev
app/console cache:clear --env=prod

app/console assets:install
