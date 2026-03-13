<?php

declare(strict_types=1);

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

use apigatewaycl\api_client\ApiException;
use apigatewaycl\api_client\sii\BheEmitidas;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\FunctionHelpers;

#[CoversClass(BheEmitidas::class)]
class EnviarEmailBheEmitidaTest extends TestCase
{
    use FunctionHelpers;

    protected static $verbose;

    protected static $client;

    protected static $auth;

    private static $contribuyente_rut;

    private static $periodo;

    private static $version;

    public static function setUpBeforeClass(): void
    {
        self::requireEnv('APIGATEWAY_API_TOKEN');
        self::requireEnv('TEST_CONTRIBUYENTE_RUT');
        self::requireEnv('TEST_CONTRIBUYENTE_CLAVE');
        self::requireEnv('TEST_PERIODO_YMD');
        self::$verbose = env(varname: 'TEST_VERBOSE', default: false);
        self::$contribuyente_rut = env('TEST_CONTRIBUYENTE_RUT');
        $contribuyente_clave = env('TEST_CONTRIBUYENTE_CLAVE');
        self::$auth = [
            'pass' => [
                'rut' => self::$contribuyente_rut,
                'clave' => $contribuyente_clave,
            ],
        ];
        self::$client = new BheEmitidas(self::$auth);
        self::$periodo = env('TEST_PERIODO_YMD');
        self::$version = env('TEST_VERSION') ?? 'v2';
    }

    public function testEnviarEmailBheEmitida(): void
    {
        try {
            $documentos = self::$client->listarBhesEmitidas(
                self::$contribuyente_rut,
                self::$periodo
            );

            $data = json_decode(
                json: (string)$documentos->getBody(),
                associative: true
            );

            if($data !== []){
                $codigo = $data['codigo'];
                $receptor_email = env('TEST_RECEPTOR_EMAIL');

                $email = self::$client->enviarEmailBheEmitida(
                    $codigo,
                    $receptor_email
                );

                $this->assertSame(200, $email->getStatusCode());

                if (self::$verbose) {
                    echo "\n",
                    'testEnviarEmailBheEmitida() email: ',
                    $email->getBody(),
                    "\n";
                }
            }else{
                $this->markTestIncomplete(
                    "No hay BHEs emitidas para esta prueba."
                    );
            }

        } catch (ApiException $e) {
            $this->handleApiException($e);
        }
    }
}
