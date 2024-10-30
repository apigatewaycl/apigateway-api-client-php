Ejemplo
=======

El siguiente es un ejemplo básico de cómo obtener un listado de documentos BHE utilizando el cliente de API.

.. code-block:: php
    <?php

    # Definición de directorio autoload. Necesario si se usa la versión de GitHub.
    require_once __DIR__ . '/vendor/autoload.php';
    
    # Importaciones del cliente de API de API Gateway
    use apigatewaycl\api_client\ApiClient;

    # Creación de nueva instancia de cliente de API
    $client = new ApiClient();

    # RUT de contribuyente SII sin puntos y con Dígito Verificador.
    $contribuyente_rut = '12345678-9';
    # Clave de contribuyente SII.
    $contribuyente_clave = 'claveSii';

    # Periodo, formato AAAAMM.
    $periodo = date('Ym');
    # Recurso a consumir.
    $url = '/sii/bhe/recibidas/documentos/'.$contribuyente_rut.'/'.$periodo;

    # Diccionario de autenticación. Debe contener las credenciales del SII en esta forma.
    $auth = [
        'pass' => [
            'rut' => $contribuyente_rut,
            'clave' => $contribuyente_clave
        ],
    ];

    # Se efectua la solicitud HTTP y se guarda la respuesta. Parámetros: url, auth
    $response = $client->post($url, [
        'auth' => $auth,
    ]);

    # Se despliega el resultado en consola, para confirmar.
    echo "\n", $response->getStatusCode();
    echo "\nBHE RECIBIDAS: \n";
    echo "\n",$response->getBody(),"\n";

Desgloce de ejemplo
-------------------

Para utilizar el cliente de API de API Gateway, deberás tener definido el token de API como variable de entorno. 

.. seealso::
    Para más información sobre este paso, referirse al la guía en Configuración.

Al momento de integrar el cliente de API con tu programa, debes importar el cliente e instanciarlo.

.. code-block:: php
    # Importaciones del cliente de API de API Gateway
    use apigatewaycl\api_client\ApiClient;

    # Creación de nueva instancia de cliente de API
    $cliente = new ApiClient();

Luego de crear la instancia, puedes definir otros parámetros, como la URL a llamar, una fecha, un periodo, entre otros.

.. important::
    Para hacer que la solicitud HTTP funcione, necesitas credenciales de autenticación para algunos casos.

.. code-block:: php
    # RUT de contribuyente SII sin puntos y con Dígito Verificador.
    $contribuyente_rut = '12345678-9';
    # Clave de contribuyente SII.
    $contribuyente_clave = 'claveSii';

    # Periodo, formato AAAAMM.
    $periodo = date('Ym');
    # Recurso URL a utilizar
    $url = '/sii/bhe/recibidas/documentos/'.$contribuyente_rut.'/'.$periodo;

    # Diccionario de autenticación. Debe contener las credenciales del SII en esta forma.
    $auth = [
        'pass' => [
            'rut' => $contribuyente_rut,
            'clave' => $contribuyente_clave
        ],
    ];

Una vez tengas los recursos necesarios, puedes hacer una solicitud HTTP. La solicitud debe ser válida, y utiliza métodos get o post

.. code-block:: php
    # Se efectua la solicitud HTTP y se guarda la respuesta.
    $response = $client->post($url, [
        'auth' => $auth,
    ]);

Por último, para extraer la información de la respuesta HTTP, debes usar getBody() en la respuesta HTTP. Para comprobar el resultado, se debe imprimir.

.. code-block:: php
    # Se despliega el resultado en consola, para confirmar.
    echo "\n", $response->getStatusCode();
    echo "\nBHE RECIBIDAS: \n";
    echo "\n",$response->getBody(),"\n";

.. seealso::
    Para saber más sobre los parámetros posibles y el cómo consumir las API, referirse a la `documentación de API Gateway. <https://developers.apigateway.cl/>`_
