<?php

/**
 * API Gateway: Cliente de API en PHP.
 * Copyright (C) API Gateway <https://www.apigateway.cl>
 *
 * Este programa es software libre: usted puede redistribuirlo y/o modificarlo
 * bajo los términos de la GNU Lesser General Public License (LGPL) publicada
 * por la Fundación para el Software Libre, ya sea la versión 3 de la Licencia,
 * o (a su elección) cualquier versión posterior de la misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero SIN
 * GARANTÍA ALGUNA; ni siquiera la garantía implícita MERCANTIL o de APTITUD
 * PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de la GNU Lesser General
 * Public License (LGPL) para obtener una información más detallada.
 *
 * Debería haber recibido una copia de la GNU Lesser General Public License
 * (LGPL) junto a este programa. En caso contrario, consulte
 * <http://www.gnu.org/licenses/lgpl.html>.
 */

 namespace apigatewaycl\api_client;

/**
 * Cliente de la API de API Gateway.
 *
 * Esta clase permite interactuar con la API de API Gateway, manejando
 * las solicitudes HTTP, la autenticación y el manejo de respuestas.
 */
class ApiClient
{

    /**
     * La URL base de la API de API Gateway.
     *
     * @var string
     */
    private $api_url = 'https://apigateway.cl';

    /**
     * El prefijo para las rutas de la API.
     *
     * @var string
     */
    private $api_prefix = '/api';

    /**
     * La versión de la API a utilizar.
     *
     * @var string
     */
    private $api_version = 'v1';

    /**
     * El token de autenticación para la API.
     *
     * @var string|null
     */
    private $api_token = null;

    /**
     * La última URL utilizada en la solicitud HTTP.
     *
     * @var string|null
     */
    private $last_url = null;

    /**
     * La última respuesta recibida de la API.
     *
     * @var \Psr\Http\Message\ResponseInterface|null
     */
    private $last_response = null;

    /**
     * Constructor del cliente de la API.
     *
     * @param string|null $token Token de autenticación para la API.
     * @param string|null $url URL base de la API.
     */
    public function __construct($token = null, $url = null)
    {
        $this->api_token = $token ?: $this->env('APIGATEWAY_API_TOKEN');
        if (!$this->api_token) {
            throw new ApiException('APIGATEWAY_API_TOKEN missing');
        }

        $this->api_url = $url ?: $this->env('APIGATEWAY_API_URL') ?: $this->api_url;
    }

    /**
     * Establece la URL base de la API.
     *
     * @param string $url URL base.
     * @return $this
     */
    public function setUrl($url)
    {
        $this->api_url = $url;
        return $this;
    }

    /**
     * Establece el token de autenticación.
     *
     * @param string $token Token de autenticación.
     * @return $this
     */
    public function setToken($token)
    {
        $this->api_token = $token;
        return $this;
    }

    /**
     * Obtiene la última URL utilizada en la solicitud HTTP.
     *
     * @return string|null
     */
    public function getLastUrl()
    {
        return $this->last_url;
    }

    /**
     * Obtiene la última respuesta recibida de la API.
     *
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function getLastResponse()
    {
        return $this->last_response;
    }

    /**
     * Obtiene el cuerpo de la última respuesta HTTP.
     *
     * Este método devuelve el cuerpo de la respuesta de la última
     * solicitud HTTP realizada utilizando este cliente API.
     *
     * @return string El cuerpo de la respuesta HTTP.
     * @throws ApiException Si no hay respuesta previa o el cuerpo no se puede obtener.
     */
    public function getBody()
    {
        if (!$this->last_response) {
            throw new ApiException('No hay una respuesta HTTP previa para obtener el cuerpo.');
        }

        return (string)$this->last_response->getBody();
    }

    /**
     * Obtiene el cuerpo de la última respuesta HTTP y lo decodifica de JSON.
     *
     * Este método devuelve el cuerpo de la respuesta de la última
     * solicitud HTTP realizada por este cliente API, decodificándolo de
     * formato JSON a un arreglo asociativo de PHP.
     *
     * @return array El cuerpo de la respuesta HTTP decodificado como un arreglo.
     * @throws ApiException Si no hay respuesta previa o el cuerpo no se puede decodificar.
     */
    public function getBodyDecoded()
    {
        $body = $this->getBody();

        $decodedBody = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException('Error al decodificar JSON: ' . json_last_error_msg());
        }

        return $decodedBody;
    }

    /**
     * Convierte la última respuesta HTTP en un arreglo asociativo.
     *
     * Este método transforma la última respuesta HTTP recibida en un arreglo
     * asociativo, que incluye información del estado HTTP, encabezados y el
     * cuerpo de la respuesta, ya sea en formato de texto o decodificado de JSON.
     *
     * @return array Arreglo asociativo con la información de la respuesta.
     * @throws ApiException Si se encuentra un error en el proceso.
     */
    public function toArray()
    {
        if (!$this->last_response) {
            throw new ApiException('No hay una respuesta HTTP previa para procesar.');
        }

        $headers = $this->getLastResponse()->getHeaders();
        foreach ($headers as &$header) {
            $header = isset($header[1]) ? $header : $header[0];
        }

        $statusCode = $this->getLastResponse()->getStatusCode();
        $contentType = $this->getLastResponse()->getHeader('content-type')[0];

        // Procesar el cuerpo de la respuesta según el tipo de contenido
        if ($contentType == 'application/json') {
            $body = $this->getBodyDecoded();
            if ($body === null) {
                $body = $this->getBody();
                $body = $body ?: $this->getError()->message;
            }
        } else {
            $body = $this->getBody();
            $body = $body ?: $this->getError()->message;
        }

        // Manejar respuestas con error
        if ($statusCode != 200) {
            if (!empty($body['message'])) {
                $body = $body['message'];
            } elseif (!empty($body['exception'])) {
                $body = $this->getError()->message;
            } else {
                $body = 'Error no determinado: ' . json_encode($body);
            }
        }

        return [
            'status' => [
                'protocol' => $this->getLastResponse()->getProtocolVersion(),
                'code' => $statusCode,
                'message' => $this->getLastResponse()->getReasonPhrase(),
            ],
            'header' => $headers,
            'body' => $body,
        ];
    }

    /**
     * Realiza una solicitud GET a la API.
     *
     * @param string $resource Recurso de la API al cual realizar la solicitud.
     * @param array $headers Encabezados adicionales para incluir en la solicitud.
     * @param array $options Arreglo con las opciones de la solicitud HTTP.
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function get($resource, array $headers = [], array $options = [])
    {
        return $this->consume($resource, [], $headers, 'GET', $options)->getLastResponse();
    }

    /**
     * Realiza una solicitud POST a la API.
     *
     * @param string $resource Recurso de la API al cual realizar la solicitud.
     * @param array $data Datos a enviar en la solicitud.
     * @param array $headers Encabezados adicionales para incluir en la solicitud.
     * @param array $options Arreglo con las opciones de la solicitud HTTP.
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function post($resource, array $data, array $headers = [], array $options = [])
    {
        return $this->consume($resource, $data, $headers, 'POST', $options)->getLastResponse();
    }

    /**
     * Realiza una solicitud PUT a la API.
     *
     * @param string $resource Recurso de la API al cual realizar la solicitud.
     * @param array $data Datos a enviar en la solicitud.
     * @param array $headers Encabezados adicionales para incluir en la solicitud.
     * @param array $options Arreglo con las opciones de la solicitud HTTP.
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function put($resource, array $data, array $headers = [], array $options = [])
    {
        return $this->consume($resource, $data, $headers, 'PUT', $options)->getLastResponse();
    }

    /**
     * Realiza una solicitud DELETE a la API.
     *
     * @param string $resource Recurso de la API al cual realizar la solicitud.
     * @param array $headers Encabezados adicionales para incluir en la solicitud.
     * @param array $options Arreglo con las opciones de la solicitud HTTP.
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function delete($resource, array $headers = [], array $options = [])
    {
        return $this->consume($resource, [], $headers, 'DELETE', $options)->getLastResponse();
    }

    /**
     * Realiza una solicitud HTTP a la API.
     *
     * Este método envía una solicitud HTTP a la API de API Gateway, utilizando
     * los parámetros especificados y manejando la autenticación y la respuesta.
     *
     * @param string $resource El recurso de la API al cual realizar la solicitud.
     * @param array $data Datos a enviar en la solicitud (para métodos POST y PUT).
     * @param array $headers Encabezados adicionales para incluir en la solicitud.
     * @param string|null $method Método HTTP a utilizar (GET, POST, PUT, DELETE).
     * @param array $options Arreglo con las opciones de la solicitud HTTP.
     * @return $this Instancia actual del cliente para encadenar llamadas.
     * @throws ApiException Si se produce un error en la solicitud.
     */
    public function consume($resource, $data = [], array $headers = [], $method = null, $options = [])
    {
        $this->last_response = null;
        if (!$this->api_token) {
            throw new ApiException('Falta especificar token para autenticación.', 400);
        }
        $method = $method ?: ($data ? 'POST' : 'GET');
        $client = new \GuzzleHttp\Client();
        $this->last_url = $this->api_url . $this->api_prefix . '/'. $this->api_version . $resource;

        // preparar cabeceras que se usarán
        $options[\GuzzleHttp\RequestOptions::HEADERS] = array_merge([
            'Authorization' => 'Bearer ' . $this->api_token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ], $headers);

        // agregar datos de la consulta si se pasaron (POST o PUT)
        if ($data) {
            $options[\GuzzleHttp\RequestOptions::JSON] = $data;
        }

        // Ejecutar consulta al SII.
        try {
            $this->last_response = $client->request($method, $this->last_url, $options);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            // Obtener la respuesta de la llamada.
            $this->last_response = $e->getResponse();
            // Se lanza la excepción.
            $this->throwException();
        }

        // Entregar respuesta (contenida en el mismo objeto del cliente).
        return $this;
    }

    /**
     * Extrae información detallada sobre un error a partir de la última respuesta HTTP.
     *
     * Este método analiza la última respuesta HTTP para extraer información
     * detallada sobre un error que ocurrió durante la solicitud. Devuelve un
     * objeto con los detalles del error, incluyendo el código y el mensaje.
     *
     * @return object Detalles del error con propiedades 'code' y 'message'.
     */
    private function getError()
    {
        $data = $this->getBodyDecoded();
        $response = $this->getLastResponse();
        $statusCode = $response ? $response->getStatusCode() : null;
        $reasonPhrase = $response ? $response->getReasonPhrase() : 'Sin respuesta';

        if ($data) {
            $code = isset($data['code']) ? $data['code'] : $statusCode;
            $message = isset($data['message']) ? $data['message'] : $reasonPhrase;
        } else {
            $code = $statusCode;
            $message = $reasonPhrase;
        }

        // Se maneja el caso donde no se encuentra un mensaje de error específico
        if (!$message || $message === '') {
            $message = '[API Gateway] Código HTTP ' . $code . ': ' . $reasonPhrase;
        }

        return (object)[
            'code' => $code,
            'message' => $message,
        ];
    }

    /**
     * Lanza una ApiException con los detalles del último error.
     *
     * Este método utiliza la información obtenida del método getError() para
     * lanzar una ApiException con un mensaje de error detallado y un código
     * de error asociado. Se utiliza para manejar errores de la API de manera
     * uniforme en toda la clase.
     *
     * @throws ApiException Lanza una excepción con detalles del error.
     */
    private function throwException()
    {
        $error = $this->getError();
        throw new ApiException($error->message, $error->code);
    }

    /**
     * Obtiene el valor de una variable de entorno.
     *
     * @param string $name Nombre de la variable de entorno.
     * @return string|null Valor de la variable de entorno o null si no está definida.
     */
    private function env($name)
    {
        return function_exists('env') ? env($name) : getenv($name);
    }

}
