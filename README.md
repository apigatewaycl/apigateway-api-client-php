API Gateway: Cliente de API en PHP
==================================

[![Total Downloads](https://poser.pugx.org/apigatewaycl/apigateway-api-client/downloads)](https://packagist.org/packages/apigatewaycl/apigateway-api-client)
[![Monthly Downloads](https://poser.pugx.org/apigatewaycl/apigateway-api-client/d/monthly)](https://packagist.org/packages/apigatewaycl/apigateway-api-client)
[![License](https://poser.pugx.org/apigatewaycl/apigateway-api-client/license)](https://packagist.org/packages/apigatewaycl/apigateway-api-client)

Cliente para realizar la integración con los servicios web de [API Gateway](https://www.apigateway.cl) desde PHP.

Instalación
-----------

Ejecutar en la terminal:

```shell
composer require apigatewaycl/apigateway-api-client
```

Ejemplos
--------

Para revisar ejemplos de cómo consumir los servicios web, dependiendo de la
forma de autenticación que requieras usar, revisa las siguientes pruebas:

- Sin autenticación en SII: `SiiContribuyentesTest.php`.
- Autenticación con RUT y clave tributaria: `SiiMisiiTest.php` o `SiiBheTest.php`.
- Autenticación con firma electrónica: `SiiDteTest.php`.

Documentación (dev)
-------------------

Para crear la documentación se necesita tener instaladas las dependencias
de composer, GraphViz en el sistema operativo y luego ejecutar:

```shell
./vendor/bin/phpdoc -d ./src -t ./docs
```

Pruebas unitarias (dev)
-----------------------

Para ejecutar las pruebas unitarias se necesita tener instaladas las
dependencias de composer y luego ejecutar:

```shell
./vendor/bin/phpunit
```

También es posible ejecutar una pruebas específica indicando el test. Ejemplo:

```shell
./vendor/bin/phpunit --filter test_contribuyentes_datos
```

Licencia
--------

Este programa es software libre: usted puede redistribuirlo y/o modificarlo
bajo los términos de la GNU Lesser General Public License (LGPL) publicada
por la Fundación para el Software Libre, ya sea la versión 3 de la Licencia,
o (a su elección) cualquier versión posterior de la misma.

Este programa se distribuye con la esperanza de que sea útil, pero SIN
GARANTÍA ALGUNA; ni siquiera la garantía implícita MERCANTIL o de APTITUD
PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de la GNU Lesser General
Public License (LGPL) para obtener una información más detallada.

Debería haber recibido una copia de la GNU Lesser General Public License
(LGPL) junto a este programa. En caso contrario, consulte
[GNU Lesser General Public License](http://www.gnu.org/licenses/lgpl.html).

Enlaces
-------

- [Sitio web API Gateway](https://www.apigateway.cl).
- [Código fuente en GitHub](https://github.com/apigatewaycl/apigateway-api-client-php).
- [Paquete en Packagist](https://packagist.org/packages/apigatewaycl/apigateway-api-client).
