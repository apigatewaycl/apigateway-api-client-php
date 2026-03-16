Configuración
=============

Para utilizar el cliente de API de API Gateway, se debe tener un token de usuario generado desde la cuenta de API Gateway.

.. important::
   El archivo ``test.env`` sólo sirve para usar con los test.

Versiones de API
----------------

El cliente permite utilizar dos versiones de la API mediante variables de entorno.

**API v1 (legacy)**

- URL: ``https://legacy.apigateway.cl``

**API v2 (recomendada)**

- URL: ``https://app.apigateway.cl``

.. note::
   Si no se define ``APIGATEWAY_API_VERSION`` el cliente utilizará automáticamente **v2**.

Variables de entorno
--------------------

Las siguientes variables deben definirse en el sistema:

- ``APIGATEWAY_API_URL``
- ``APIGATEWAY_API_VERSION``
- ``APIGATEWAY_API_TOKEN``

``APIGATEWAY_API_TOKEN`` es una cadena extremadamente larga. Guárdala en un lugar seguro.

Linux o MacOS
-------------

**Configuración para API v1**

.. code-block:: shell

    export APIGATEWAY_API_URL="https://legacy.apigateway.cl"
    export APIGATEWAY_API_VERSION="v1"
    export APIGATEWAY_API_TOKEN="aqui-su-token-de-apigateway"

**Configuración para API v2**

.. code-block:: shell

    export APIGATEWAY_API_URL="https://app.apigateway.cl"
    export APIGATEWAY_API_VERSION="v2"
    export APIGATEWAY_API_TOKEN="aqui-su-token-de-apigateway"

Para hacerlo persistente:

- Linux: añadir al archivo ``~/.bashrc``
- MacOS: añadir al archivo ``~/.zshrc``

Luego ejecutar:

.. code-block:: shell

    source ~/.zshrc

Windows
-------

**CMD – API v1**

.. code-block:: shell

    setx APIGATEWAY_API_URL "https://legacy.apigateway.cl"
    setx APIGATEWAY_API_VERSION "v1"
    setx APIGATEWAY_API_TOKEN "aqui-su-token-de-apigateway"

**CMD – API v2**

.. code-block:: shell

    setx APIGATEWAY_API_URL "https://app.apigateway.cl"
    setx APIGATEWAY_API_VERSION "v2"
    setx APIGATEWAY_API_TOKEN "aqui-su-token-de-apigateway"

**PowerShell – API v1**

.. code-block:: shell

    $Env:APIGATEWAY_API_URL = "https://legacy.apigateway.cl"
    $Env:APIGATEWAY_API_VERSION = "v1"
    $Env:APIGATEWAY_API_TOKEN = "aqui-su-token-de-apigateway"

**PowerShell – API v2**

.. code-block:: shell

    $Env:APIGATEWAY_API_URL = "https://app.apigateway.cl"
    $Env:APIGATEWAY_API_VERSION = "v2"
    $Env:APIGATEWAY_API_TOKEN = "aqui-su-token-de-apigateway"

.. important::
    Como alternativa para almacenar las variables de manera persistente, debes seguir los siguientes pasos:

    1.  Abre el Panel de Control > Sistema > Configuración avanzada del sistema.
    2.  En la pestaña Opciones avanzadas, selecciona Variables de entorno.
    3.  Añade o modifica las variables en la sección de "Variables de usuario" o "Variables del sistema".