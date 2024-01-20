<?php

/**
 * API Gateway: Cliente de API en PHP - Pruebas Unitarias.
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

use PHPUnit\Framework\TestCase;
use apigatewaycl\api_client\ApiClient;
use apigatewaycl\api_client\ApiException;

class DteTest extends TestCase
{

    protected static $verbose;
    protected static $client;
    protected static $auth;

    public static function setUpBeforeClass(): void
    {
        self::$verbose = env('TEST_VERBOSE', false);
        self::$client = new ApiClient();
        $firma_public_key = env('TEST_USUARIO_FIRMA_PUBLIC_KEY');
        $firma_private_key = env('TEST_USUARIO_FIRMA_PRIVATE_KEY');
        self::$auth = [
            'cert' => [
                'cert-data' => $firma_public_key,
                'pkey-data' => $firma_private_key
            ],
        ];
    }

    public function test_dte_contribuyentes_autorizados()
    {
        $dia = env('TEST_FECHA', date('Y-m-d'));
        $certificacion = env('TEST_SII_AMBIENTE', 0); // =1 certificación, =0 producción
        $formato = 'csv_sii'; // json, csv o csv_sii (este último es el formato más rápido)
        $filename = __DIR__ . '/contribuyentes.csv';
        $resource = fopen($filename, 'w');
        $stream = \GuzzleHttp\Psr7\Utils::streamFor($resource);
        $url = '/sii/dte/contribuyentes/autorizados?dia='.$dia.'&formato='.$formato.'&certificacion='.$certificacion;
        $body = ['auth' => self::$auth];
        $headers = [];
        $options = [\GuzzleHttp\RequestOptions::SINK => $stream];
        try {
            $response = self::$client->post($url, $body, $headers, $options);
            $filesize = filesize($filename);
            fclose($resource);
            unlink($filename);
            $this->assertEquals(200, $response->getStatusCode());
            if (self::$verbose) {
                echo "\n",'test_dte_contribuyentes_autorizados() filename ',$filename,"\n";
                echo "\n",'test_dte_contribuyentes_autorizados() filesize ',$filesize,"\n";
            }
        } catch (ApiException $e) {
            fclose($resource);
            unlink($filename);
            $this->fail(sprintf('[ApiException %d] %s', $e->getCode(), $e->getMessage()));
        }

    }

}
