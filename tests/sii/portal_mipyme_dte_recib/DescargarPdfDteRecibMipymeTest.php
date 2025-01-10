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
use apigatewaycl\api_client\sii\PortalMipymeDteRecibidos;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PortalMipymeDteRecibidos::class)]
class DescargarPdfDteRecibMipymeTest extends TestCase
{
    protected static $verbose;

    protected static $client;

    private static $contribuyente_rut;

    protected static $auth;

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
        self::$client = new PortalMipymeDteRecibidos(self::$auth);
    }

    public function testDescargarPdfDteRecibMipyme()
    {
        $filtros = [
            'FEC_DESDE' => date('Y-m-d', strtotime('-30 days')),
            'FEC_HASTA' => date('Y-m-d'),
        ];
        try {
            $documentos = self::$client->obtenerDtesRecibidos(
                self::$contribuyente_rut,
                $filtros
            );

            $documentos_array = json_decode((string)$documentos->getBody(), true);

            if (count($documentos_array) <= 0) {
                $this->markTestSkipped("\n"."No hay DTEs recibidos para esta prueba."."\n");
            }

            $emisor = $documentos_array[0]['rut'].'-'.$documentos_array[0]['dv'];
            $dte = $documentos_array[0]['dte'];
            $folio = $documentos_array[0]['folio'];
            $codigo = $documentos_array[0]['codigo'];

            $response = self::$client->descargarPdfDteRecibido(
                self::$contribuyente_rut,
                $emisor,
                $codigo
            );

            $this->assertSame(200, $response->getStatusCode());

            // Ruta base para el directorio actual (archivo ejecutándose en
            // "tests/dte_facturacion")
            $currentDir = __DIR__;

            // Nueva ruta relativa para guardar el archivo PDF en "tests/archivos"
            $targetDir = dirname(dirname($currentDir)) . '/archivos/dte_recib_mipyme_pdf';

            // Define el nombre del archivo PDF en el nuevo directorio
            $filename = $targetDir . '/' . sprintf(
                'APIGATEWAY_MIPYME_DTE_RECIBIDO_%s_%s_T%sF%s.pdf',
                self::$contribuyente_rut,
                $emisor,
                $dte,
                $folio
            );

            // Verifica si el directorio existe, si no, créalo
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Se genera el archivo PDF.
            file_put_contents($filename, $response->getBody());

            if (self::$verbose) {
                echo "\n",'testDescargarPdfDteEmitMipyme() PDF: ',$filename,"\n";
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
