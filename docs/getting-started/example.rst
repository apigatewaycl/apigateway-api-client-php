Ejemplo
=======

Ejemplo de listar BHEs
----------------------

El siguiente es un ejemplo básico de cómo obtener un listado de documentos BHE utilizando el cliente de API.

Para utilizar el cliente de API de API Gateway, deberás tener definido el token de API como variable de entorno. 

.. seealso::
    Para más información sobre este paso, referirse al la guía en Configuración.

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

.. seealso::
    Para saber más sobre los parámetros posibles y el cómo consumir las API, referirse a la `documentación de API Gateway. <https://developers.apigateway.cl/>`_
