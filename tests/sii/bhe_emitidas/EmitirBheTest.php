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

#[CoversClass(BheEmitidas::class)]
class EmitirBheTest extends TestCase
{
    protected static $verbose;

    protected static $client;

    protected static $auth;

    private static $contribuyente_rut;

    private static $receptor_rut;

    public static function setUpBeforeClass(): void
    {
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
        self::$receptor_rut = env('TEST_EMISOR_RUT');
    }

    public function testEmitirBhe(): void
    {
        $fecha_emision = date('Y-m-d');
        $datos_bhe = [
            'Encabezado' => [
                'IdDoc' => [
                    'FchEmis' => $fecha_emision,
                    'TipoRetencion' => self::$client::RETENCION_EMISOR,
                ],
                'Emisor' => [
                    'RUTEmisor' => self::$contribuyente_rut,
                ],
                'Receptor' => [
                    'RUTRecep' => self::$receptor_rut,
                    'RznSocRecep' => 'Receptor generico',
                    'DirRecep' => 'Santa Cruz',
                    'CmnaRecep' => 'Santa Cruz',
                ],
            ],
            'Detalle' => [
                [
                    'NmbItem' => 'Prueba integracion API Gateway 1',
                    'MontoItem' => 50,
                ],
                [
                    'NmbItem' => 'Prueba integracion API Gateway 2',
                    'MontoItem' => 100,
                ],
            ],
        ];

        try {
            $response = self::$client->emitirBhe(
                boleta: $datos_bhe
            );

            $this->assertSame(200, $response->getStatusCode());

            if (self::$verbose) {
                echo "\n",
                'testEmitirBhe() resultado: ',
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
