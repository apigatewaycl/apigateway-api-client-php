Ejemplo
=======

Ejemplo de listar BHEs
----------------------

El siguiente es un ejemplo básico de cómo emitir un documento BHE utilizando el cliente de API.

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

    # RUT de contribuyente SII, sin puntos y con Dígito Verificador.
    $contribuyente_rut = '12345678-9';
    # Clave de contribuyente SII.
    $contribuyente_clave = 'ClaveSii';
    # RUT del receptor del BHE a emitir, sin punto y con Dígito Verificador.
    $receptor_rut = '66666666-6';
    # Fecha de emisión de la BHE.
    $fecha_emision = date('Y-m-d');

    # Diccionario de autenticación.
    $auth = [
        'pass' => [
            'rut' => $contribuyente_rut,
            'clave' => $contribuyente_clave,
        ],
    ];

    $datos_bhe = [
        'Encabezado' => [
            'IdDoc' => [
                'FchEmis' => $fecha_emision,
                'TipoRetencion' => 0
            ],
            'Emisor' => [
                'RUTEmisor' => $contribuyente_rut
            ],
            'Receptor' => [
                'RUTRecep' => $receptor_rut,
                'RznSocRecep' => 'Receptor generico',
                'DirRecep' => 'Santa Cruz',
                'CmnaRecep' => 'Santa Cruz'
            ]
        ],
        'Detalle' => [
            [
                'NmbItem' => 'Prueba integracion API Gateway 1',
                'MontoItem' => 50
            ],
            [
                'NmbItem' => 'Prueba integracion API Gateway 2',
                'MontoItem' => 100
            ]
        ]
    ];

    # Recurso a consumir.
    $resource = '/sii/bhe/emitidas/emitir';

    $data = [
        'auth' => $auth,
        'boleta' => $datos_bhe
    ];

    # Se efectua la solicitud HTTP y se guarda la respuesta.
    $response = $client->post(resource: $resource, data: $data);

    # Código del response
    echo "Status: ".$response->getStatusCode()."\n";

    # Se despliega el resultado en consola, para confirmar.
    if ($response->getStatusCode() == 200) {
        echo "\nDTEs Temporales: \n";
        echo "\n",'EMITIR BHE: ',json_encode($response->getBody()),"\n";
    }

.. seealso::
    Para saber más sobre los parámetros posibles y el cómo consumir las API, referirse a la `documentación de API Gateway. <https://developers.apigateway.cl/>`_
