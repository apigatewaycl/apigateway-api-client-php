Pruebas unitarias
=================

.. important::
  Al ejecutar pruebas, deberás tener configuradas las variables de entorno necesarias en el archivo test.env. Favor de duplicar test.env-dist, cambiar su nombre a test.env y rellenar las variables necesarias.

Antes de empezar, debes configurar las siguientes variables de entorno:
.. code-block:: shell
    APIGATEWAY_API_URL="https://apigateway.cl"
    API_GATEWAY_API_TOKEN="token-apigateway"
    CONTRIBUYENTE_RUT="66666666-6"
    CONTRIBUYENTE_CLAVE="clave-sii"
    TEST_VERBOSE=true
    TEST_PERIODO=202401

Para ejecutar las pruebas unitarias se necesita tener instaladas las dependencias de composer, y para hacer todas las pruebas, ejecutar lo siguiente:

.. code-block:: shell
    ./vendor/bin/phpunit

También es posible ejecutar una pruebas específica indicando el test. Ejemplo:

.. code-block:: shell
    ./vendor/bin/phpunit --filter test_contribuyentes_datos

Ejemplos
--------

Para revisar más ejemplos de cómo consumir los servicios web, dependiendo de la forma de autenticación que requieras usar, revisa las siguientes pruebas:

- Sin autenticación en SII: SiiContribuyentesTest.php.
- Autenticación con RUT y clave tributaria: SiiMisiiTest.php o SiiBheTest.php.
- Autenticación con firma electrónica: SiiDteTest.php.