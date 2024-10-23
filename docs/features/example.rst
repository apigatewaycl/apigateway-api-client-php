Ejemplo
=======

El siguiente es un ejemplo básico de cómo obtener un listado de documentos BHE.

.. code-block:: php
    use apigatewaycl\api_client\ApiClient;

    $client = new ApiClient();
    $contribuyente_rut = "66666666-6"; # Inserte RUT contribuyente aquí
    $contribuyente_clave = "asdf"; # Inserte clave aquí

    $periodo = date('Ym');
    $url = '/sii/bhe/recibidas/documentos/'.$contribuyente_rut.'/'.$periodo;

    $auth = [
        'pass' => [
            'rut' => $contribuyente_rut,
            'clave' => $contribuyente_clave
        ],
    ];

    $response = $client->post($url, [
        'auth' => $auth,
    ]);

    echo "BHE RECIBIDAS: \n";
    echo "\n".$response->getBody()."\n";

Desgloce
--------

Para utilizar el cliente de API de API Gateway, deberás tener definido el token de API como variable de entorno. 
Para más información sobre este paso, referirse al la guía en Empezando/Configuración.

Al momento de integrar el cliente de API con tu programa, debes importar el cliente e instanciarlo.

.. code-block:: php
    # Importaciones
    use apigatewaycl\api_client\ApiClient;
    use apigatewaycl\api_client\ApiException;

    # Llamado
    $cliente = new ApiClient();

Luego de crear la instancia, puedes definir otros parámetros, como la URL a llamar, una fecha, un periodo, entre otros.

Recordatorio, para hacer que la solicitud HTTP funcione, necesitas credenciales de autenticación para algunos casos.

Para saber más sobre los parámetros posibles y el cómo llamar estos métodos, referirse a la `documentación de API Gateway. <https://developers.apigateway.cl/>`_

.. code-block:: php
    # RUT de contribuyente SII sin puntos y con Dígito Verificador.
    $contribuyente_rut = readline('Introduzca su RUT: ');
    # Clave de contribuyente SII.
    $contribuyente_clave = readline('Introduzca su clave SII: ');

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
    # Petición
    $response = $client->post($url, [
        'auth' => $auth,
    ]);

Por último, para extraer la información de la respuesta HTTP, debes usar getBody() en el resultado. Para comprobar el resultado, se debe imprimir.

.. code-block:: php
    $cuerpo = $response->getBody()
    echo $cuerpo;