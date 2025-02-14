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
use apigatewaycl\api_client\sii\DteEmitidos;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DteEmitidos::class)]
class VerificarDteEmitidoTest extends TestCase
{
    protected static $verbose;

    protected static $client;

    protected static $auth;

    private static $contribuyente_rut;

    public static function setUpBeforeClass(): void
    {
        self::$verbose = env(varname: 'TEST_VERBOSE', default: false);
        $firma_public_key = env('TEST_USUARIO_FIRMA_PUBLIC_KEY');
        $firma_private_key = env('TEST_USUARIO_FIRMA_PRIVATE_KEY');
        self::$auth = [
            'cert' => [
                'cert-data' => $firma_public_key,
                'pkey-data' => $firma_private_key,
            ],
        ];
        self::$client = new DteEmitidos(self::$auth);
        self::$contribuyente_rut = env('TEST_CONTRIBUYENTE_RUT');
    }

    public function testVerificarDteEmitido()
    {
        $receptor = env(
            varname: 'TEST_DTE_EMITIDOS_VERIFICAR_RECEPTOR_RUT',
            default: ''
        );
        $dte = env(
            varname: 'TEST_DTE_EMITIDOS_VERIFICAR_DTE',
            default: ''
        );
        $folio = env(
            varname: 'TEST_DTE_EMITIDOS_VERIFICAR_FOLIO',
            default: ''
        );
        $fecha = env(
            varname: 'TEST_DTE_EMITIDOS_VERIFICAR_FECHA',
            default: ''
        );
        $total = env(
            varname: 'TEST_DTE_EMITIDOS_VERIFICAR_TOTAL',
            default: ''
        );
        $firma = env(
            varname: 'TEST_DTE_EMITIDOS_VERIFICAR_FIRMA',
            default: ''
        );

        try {
            $response = self::$client->verificarDteEmitido(
                emisor: self::$contribuyente_rut,
                receptor: $receptor,
                dte: $dte,
                folio: $folio,
                fecha: $fecha,
                total: $total,
                firma: $firma != '' ? $firma : null
            );

            $this->assertSame(200, $response->getStatusCode());

            if (self::$verbose) {
                echo "\n",
                'testVerificarDteEmitido() resultado: ',
                $response->getBody(),
                "\n";
            }
        } catch (ApiException $e) {
            $this->fail(message: sprintf(
                '[ApiException %d] %s',
                $e->getCode(),
                $e->getMessage()
            ));
        }
    }
}
