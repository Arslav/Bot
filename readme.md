docker build -t newbot .

docker run --rm -it -v $(pwd):/web/app newbot composer install

docker run --rm -it -v $(pwd):/web/app newbot vendor/bin/codecept run