Pruebas unitarias
=================

Para ejecutar las pruebas unitarias se necesita tener instaladas las
dependencias de composer y luego ejecutar:

.. code-block:: shell
    ./vendor/bin/phpunit

También es posible ejecutar una pruebas específica indicando el test. Ejemplo:

.. code-block:: shell
    ./vendor/bin/phpunit --filter test_contribuyentes_datos

Ejemplos
--------

Para revisar más ejemplos de cómo consumir los servicios web, dependiendo de la
forma de autenticación que requieras usar, revisa las siguientes pruebas:

- Sin autenticación en SII: SiiContribuyentesTest.php.
- Autenticación con RUT y clave tributaria: SiiMisiiTest.php o SiiBheTest.php.
- Autenticación con firma electrónica: SiiDteTest.php.