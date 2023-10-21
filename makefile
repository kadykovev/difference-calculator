gendiff:
	./bin/gendiff

install:
	composer install

validate:
	composer validate

lint: # запуск линтера
	composer exec --verbose phpcs -- --standard=PSR12 src bin tests

test:
	composer exec --verbose phpunit tests