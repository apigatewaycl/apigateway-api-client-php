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
use apigatewaycl\api_client\sii\ActividadesEconomicas;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ActividadesEconomicas::class)]
/**
 * Clase de pruebas que permite obtener una lista de actividades económicas.
 */
class ListarActividadesEconomicasTest extends TestCase
{
    protected static $verbose;

    protected static $client;

    public static function setUpBeforeClass(): void
    {
        self::$verbose = env(varname: 'TEST_VERBOSE', default: false);
        self::$client = new ActividadesEconomicas();
    }

    public function testListarActividadesEconomicas(): void
    {
        echo "test_listar_actividades_economicas(): ";
        try {
            $response = self::$client->listarActividades();

            $this->assertSame(200, $response->getStatusCode());

            if (self::$verbose) {
                echo "\n",
                'test_actividades_economicas_listar() actividades ',
                $response->getBody(),
                "\n";
            }
        } catch (ApiException $e) {
            $this->fail(sprintf(
                '[ApiException %d] %s',
                $e->getCode(),
                $e->getMessage()
            ));
        }
    }
}
