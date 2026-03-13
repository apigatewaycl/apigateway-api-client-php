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
use apigatewaycl\api_client\sii\BteEmitidas;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\FunctionHelpers;

#[CoversClass(BteEmitidas::class)]
class DescargarHtmlBteEmitidaTest extends TestCase
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
        self::$client = new BteEmitidas(self::$auth);
        self::$periodo = env('TEST_PERIODO_YMD');
        self::$version = env('TEST_VERSION') ?? 'v2';
    }

    public function testDescargarHtmlBteEmitida(): void
    {
        try {
            $documentos = self::$client->listarBtesEmitidas(
                emisor: self::$contribuyente_rut,
                periodo: self::$periodo
            );

            $documentosArray = json_decode(
                json: (string)$documentos->getBody(),
                associative: true
            );
            $documento = $documentosArray[0] ?? null;
            if ($documento === null) {
                $this->markTestIncomplete(
                    "No hay BTEs emitidas para esta prueba."
                    );
            }
            $documentoData = $documento['data'] ?? $documento;

            $codigo = $documentoData['codigo'];

            $response = self::$client->obtenerHtmlBteEmitida($codigo);

            $this->assertSame(200, $response->getStatusCode());

            // Ruta base para el directorio actual (archivo ejecutándose en
            // "tests/dte_facturacion")
            $currentDir = __DIR__;

            // Nueva ruta relativa para guardar el archivo PDF en "tests/archivos"
            $targetDir = dirname(dirname($currentDir)) .
            '/archivos/bte_emitidas_html';

            // Define el nombre del archivo PDF en el nuevo directorio
            $filename = $targetDir . '/' . sprintf(
                'APIGATEWAY_BTE_%s.html',
                $codigo
            );

            // Verifica si el directorio existe, si no, créalo
            if (!is_dir($targetDir)) {
                mkdir(directory: $targetDir, permissions: 0777, recursive: true);
            }

            // Se genera el archivo PDF.
            file_put_contents($filename, $response->getBody());

            if (self::$verbose) {
                echo "\n",
                'testDescargarHtmlBteEmitida() Archivo: ',
                $filename,
                "\n";
            }
        } catch (ApiException $e) {
            $this->handleApiException($e);
        }
    }
}
