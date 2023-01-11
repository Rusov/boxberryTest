docker-up:
	docker-compose --file=./app/docker-compose.yml up -d
docker-down:
	docker-compose --file=./app/docker-compose.yml down --remove-orphans
docker-build:
	docker-compose --file=app/docker-compose.yml build
docker-migrations-init:
	docker-compose --file=app/docker-compose.yml exec boxberry-php php yii migrate
