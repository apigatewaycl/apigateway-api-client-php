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
class ListarBheEmitidasPaginadoTest extends TestCase
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
        self::$client = new BheEmitidas(self::$auth);
        self::$periodo = env('TEST_PERIODO');
    }

    public function testListarBheEmitidasPaginado()
    {
        $pagina = 1;
        try {
            while (true) {
                $response = self::$client->listarBhesEmitidas(
                    emisor: self::$contribuyente_rut,
                    periodo: self::$periodo,
                    pagina: $pagina
                );

                $this->assertSame(200, $response->getStatusCode());

                $documentos_array = json_decode((string)$response->getBody(), true);

                if (count($documentos_array) <= 0) {
                    echo "n",'testListarBheEmitidasPaginado() Lista de BHEs emitidas vacía.',"\n";
                    break;
                }

                $n_paginas = $documentos_array['n_paginas'];

                if (self::$verbose) {
                    echo "\n",'testListarBheEmitidasPaginado() pagina: '.$pagina.'; documentos: ',$response->getBody(),"\n";
                }

                $pagina += 1;

                if ($pagina >= $n_paginas) {
                    break;
                }
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
