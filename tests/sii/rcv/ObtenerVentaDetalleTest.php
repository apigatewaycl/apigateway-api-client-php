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
use apigatewaycl\api_client\sii\Rcv;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Rcv::class)]
/**
 * Clase de pruebas para obtener un resumen y el detalle de ventas del RCV.
 */
class ObtenerVentaDetalleTest extends TestCase
{
    protected static $verbose;

    protected static $client;

    protected static $auth;

    private static $contribuyente_rut;

    private static $periodo;

    private static $estados = ['REGISTRO', 'PENDIENTE', 'NO_INCLUIR', 'RECLAMADO'];

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
        self::$client = new Rcv(self::$auth);
        self::$periodo = env(
            varname: 'TEST_PERIODO',
            default: date('Y-m-d')
        );
    }

    /**
     * Método de test para obtener el resumen y además detalle de ventas del RCV.
     * @return void
     */
    public function testObtenerVentaDetalle(): void
    {
        try {
            $ventas_resumen = self::$client->obtenerResumenVentas(
                self::$contribuyente_rut,
                self::$periodo
            );


            $this->assertSame(200, $ventas_resumen->getStatusCode());
            if (self::$verbose) {
                echo "\n",
                'testObtenerVentaDetalle() ventas_resumen: ',
                $ventas_resumen->getBody(),
                "\n";
            }

            $ventas_resumen_array = json_decode(
                json: (string)$ventas_resumen->getBody(),
                associative: true
            );

            if ($ventas_resumen_array['data'] != null) {
                foreach ($ventas_resumen_array as $resumen) {
                    if (
                        $resumen['dcvTipoIngresoDoc'] != 'DET_ELE' ||
                        $resumen['rsmnTotDoc'] == 0
                    ) {
                        continue;
                    }
                    $ventas_detalle = self::$client->obtenerDetalleVentas(
                        emisor: self::$contribuyente_rut,
                        periodo: self::$periodo,
                        dte: $resumen['rsmnTipoDocInteger']
                    );
                    $this->assertSame(200, $ventas_detalle->getStatusCode());
                    if (self::$verbose) {
                        echo "\n",
                        'testObtenerVentaDetalle() ventas_detalle: ',
                        $ventas_detalle->getBody(),
                        "\n";
                    }
                }
            } else {
                echo "\n",
                'testObtenerVentaDetalle() Libro ventas RCV vacío.',
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
