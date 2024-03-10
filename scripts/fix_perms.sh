#Set variables here
APP_OWNER=florin # <-- owner (user)
APP_GROUP=www-data # <-- WebServer group
APP_ROOT=.

# BEGIN Fix Permissions Script

# Adding owner to web server group
sudo usermod -a -G ${APP_GROUP} ${APP_OWNER}

# Set files owner/group
sudo chown -R ${APP_OWNER}:${APP_GROUP} ${APP_ROOT}

# Set correct permissions for directories 
#sudo find ${APP_ROOT} -type f -exec chmod 644 {} \;

# Set correct permissions for files 
#sudo find ${APP_ROOT} -type d -exec chmod 755 {} \;

# Set webserver group for storage + cache folders
sudo chgrp -R ${APP_GROUP} ${APP_ROOT}/runtime

# Set correct permissions for runtime folders (logs, cache)
sudo chmod -R ug+rwx ${APP_ROOT}/runtime

# END Fix Permissions Script
