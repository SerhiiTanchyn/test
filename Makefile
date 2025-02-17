SHELL := /bin/bash

start:
	docker-compose up -d
	docker-compose exec php composer install
	docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction
	echo "Project started successfully!"

stop:
	docker-compose down
	echo "Project stopped."

rebuild:
	docker-compose down --volumes --remove-orphans
	docker-compose build --no-cache
	docker-compose up -d
	docker-compose exec php composer install
	docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction
	echo "Project rebuilt successfully!"

test:
	docker-compose exec php bin/phpunit
	echo "Tests executed successfully!"