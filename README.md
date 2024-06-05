# Instrucciones para el Proyecto Symfony

## Instalación de Dependencias

Primero, asegúrate de tener Composer instalado en tu sistema. Luego, en la raíz del proyecto, ejecuta el siguiente comando para instalar todas las dependencias necesarias:

    composer install

## Creación de la Base de Datos

A continuación, crea la base de datos ejecutando el siguiente comando:

    php bin/console doctrine:database:create

## Ejecución de las Migraciones

Para estructurar la base de datos de acuerdo a las migraciones existentes, ejecuta:

    php bin/console doctrine:migrations:migrate


## Creación de una Nueva Entidad

Para crear una nueva entidad, usa el siguiente comando y sigue las instrucciones proporcionadas:

    php bin/console make:entity
