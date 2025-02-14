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
 * Clase de pruebas para obtener detalle de venta del RCV.
 */
class ObtenerCsvVentaDetalleTest extends TestCase
{
    protected static $verbose;

    protected static $client;

    protected static $auth;

    private static $contribuyente_rut;

    private static $periodo;

    public static function setUpBeforeClass(): void
    {
        self::$verbose = env('TEST_VERBOSE', false);
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
     * Método de test para obtener el detalle de una venta del RCV, tipo "rcv_csv".
     * @return void
     */
    public function testObtenerCsvVentaDetalle(): void
    {
        try {
            $ventas_detalle = self::$client->obtenerDetalleVentas(
                self::$contribuyente_rut,
                self::$periodo
            );
            $this->assertSame(200, $ventas_detalle->getStatusCode());
            if (self::$verbose) {
                echo "\n",
                'testObtenerCsvVentaDetalle() venta_detalle: ',
                $ventas_detalle->getBody(),
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
