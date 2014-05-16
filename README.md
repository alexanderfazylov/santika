Нужно установить composer:
Инструкция тут: [getcomposer.org](https://getcomposer.org/download/), или коротко

* on Linux or Mac, run the following commands:

  ```
  curl -s http://getcomposer.org/installer | php
  mv composer.phar /usr/local/bin/composer
  ```
* on Windows, download and run [Composer-Setup.exe](https://getcomposer.org/Composer-Setup.exe).

После того как склонился проект нужно установить зависимости используя composer:
  ```
  cd ./папка с исходниками
  composer update
  ```
Накатить миграции:
  ```
  cd ./папка с исходниками
 ./yii migrate/up
  ```
