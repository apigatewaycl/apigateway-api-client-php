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
 * @version 2020-01-25
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
            $this->error($e->getResponse());
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->error($e->getResponse());
        }
        if ($this->last_response->getStatusCode() != 200) {
            $this->error($this->last_response);
        }
        if ($this->last_response->getHeader('content-type')[0] == 'application/json') {
            return json_decode($this->last_response->getBody(), true);
        }
        return (string)$this->last_response->getBody();
    }

    private function error($response)
    {
        $data = json_decode($response->getBody(), true);
        if ($data) {
            if (empty($data['code'])) {
                $data['code'] = $response->getStatusCode();
            }
            throw new Exception($data['message'], $data['code']);
        } else {
            throw new Exception($response->getBody(), $response->getStatusCode());
        }
    }

}
