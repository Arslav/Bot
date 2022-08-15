# Фреймворк для разработки ботов

В разработке...

Для локальной разработки фреймворка можно использовать след. команды

```docker build -t bot .```

```docker run --rm -it -v $(pwd):/web/app bot composer install```

```docker run --rm -it -v $(pwd):/web/app bot vendor/bin/codecept run```
