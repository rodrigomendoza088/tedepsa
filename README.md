# Prueba TEDEPSA


El proyecto trata de una API RESTful de una pizzeria

## Requisitos
PHP >= 8.2.

MySql.

Laravel 11.

## Instalacion

Clonar el proyecto

```bash
  git clone https://github.com/rodrigomendoza088/tedepsa.git
```

Ir a la carpeta del proyecto

```bash
  cd tedepsa
```

Instalar dependencias

```bash
  composer install
```
Configurar nuestra coneccion a la base de datos en nuestro archivo .en (archi env de muestra proveido)

```bash
 DB_CONNECTION=mysql
 DB_HOST=127.0.0.1
 DB_PORT=3306
 DB_DATABASE=tedepsatest
 DB_USERNAME=root
 DB_PASSWORD=
```
Ejecutar migraciones

```bash
  php artisan migrate
```

Ejecutar seeders

```bash
  php artisan db:seed
```
Inicializar

```bash
  php artisan serve
```

## Base de Datos : tedepsatest

Para el proyecto se proporciona un dump.SQL para crear la base de datos inicial

## Coleccion POSTMAN

Se proporciona coleccion postman con ejemplos de las peticiones a la API 

## Para el Punto (Endpoint para agregar/quitar ingredientes a una pizza.)

Se hizo mediante la peticion PATCH, ya que el actualizado de datos es parcial. Atravez del metodo   actualizarIngredientes del PizzaController, se asume una actualizacion completa de ingredientes, previa verificacion de la existencia de los ingredientes proporcionados, con un array de IDs de ingredientes.

