# Local development

* ddev start
* ddev composer install
* ddev launch

# Migrations
After starting the containers, connect to the Docker instance and run the migration:
* docker exec -it ddev-vivo-tech-test-web bash  <!-- Replace 'ddev-vivo-tech-test-web' with your actual web container name if different -->
* php bin/console doctrine:migrations:migrate

> **Note:** Ensure the database service is running and properly configured before running migrations.

# Other

* ddev console (execute symfony console commands)
* ddev ssh (ssh into container)
