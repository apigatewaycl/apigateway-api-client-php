<?php

/**
 * LibreDTE
 * Copyright (C) SASCO SpA (https://sasco.cl)
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

namespace sasco\LibreDTE\API;

/**
 * Cliente de la API de LibreDTE
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
 * @version 2020-01-26
 */
class LibreDTE
{

    private $api_url = 'https://api.libredte.cl';
    private $api_prefix = '/api';
    private $api_version = '/v1';
    private $api_token = null;
    private $last_url = null;
    private $last_response = null;

    public function __construct($token = null, $url = null)
    {
        if ($token) {
            $this->setToken($token);
        }
        if ($url) {
            $this->setUrl($url);
        }
    }

    public function setUrl($url)
    {
        $this->api_url = $url;
        return $this;
    }

    public function setToken($token)
    {
        $this->api_token = $token;
        return $this;
    }

    public function getLastUrl()
    {
        return $this->last_url;
    }

    public function getLastResponse()
    {
        return $this->last_response;
    }

    public function consume($resource, $data = [], array $headers = [], $method = null)
    {
        $this->last_response = null;
        if (!$this->api_token) {
            throw new Exception('Falta especificar token para autenticación', 400);
        }
        if (!$method) {
            $method = $data ? 'POST' : 'GET';
        }
        $client = new \GuzzleHttp\Client();
        $this->last_url = $this->api_url.$this->api_prefix.$this->api_version.$resource;
        try {
            $request_data = [
                'headers' => array_merge([
                    'Authorization' => 'Bearer '.$this->api_token,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ], $headers),
            ];
            if ($data) {
                $request_data[\GuzzleHttp\RequestOptions::JSON] = $data;
            }
            $this->last_response = $client->request($method, $this->last_url, $request_data);
        } catch(\GuzzleHttp\Exception\ServerException $e) {
            $this->last_response = $e->getResponse();
            $this->throwException();
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->last_response = $e->getResponse();
            $this->throwException();
        }
        if ($this->getLastResponse()->getStatusCode() != 200) {
            $this->throwException();
        }
        return $this;
    }

    public function getBody()
    {
        return (string)$this->getLastResponse()->getBody();
    }

    public function getBodyDecoded()
    {
        return json_decode($this->getBody(), true);
    }

    public function toArray()
    {
        $headers = $this->getLastResponse()->getHeaders();
        foreach ($headers as &$header) {
            if (!isset($header[1])) {
                $header = $header[0];
            }
        }
        return [
            'status' => [
                'protocol' => $this->getLastResponse()->getProtocolVersion(),
                'code' => $this->getLastResponse()->getStatusCode(),
                'message' => $this->getLastResponse()->getReasonPhrase(),
            ],
            'header' => $headers,
            'body' => $this->getLastResponse()->getStatusCode() == 200 ? (
                $this->getLastResponse()->getHeader('content-type')[0] == 'application/json' ?
                $response['body'] = $this->getBodyDecoded() :
                $response['body'] = $this->getBody()
            ) : (
                $this->getError()->message
            ),
        ];
    }

    private function getError()
    {
        $data = $this->getBodyDecoded();
        if ($data) {
            if (empty($data['code'])) {
                $data['code'] = $this->getLastResponse()->getStatusCode();
            }
            $code = $data['code'];
            $message = $data['message'];
        } else {
            $code = $this->getLastResponse()->getStatusCode();
            $message = $this->getBody();
        }
        if (!$message) {
            $message = '[LibreDTE API] Código HTTP '.$code.': '.$this->getLastResponse()->getReasonPhrase();
        }
        return (object)[
            'code' => $code,
            'message' => $message,
        ];
    }

    private function throwException()
    {
        $error = $this->getError();
        throw new Exception($error->message, $error->code);
    }

}
