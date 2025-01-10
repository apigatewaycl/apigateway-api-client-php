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
use apigatewaycl\api_client\sii\DteContribuyentes;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DteContribuyentes::class)]
class VerificarAutorizacionDteTest extends TestCase
{
    protected static $verbose;

    protected static $client;

    protected static $auth;

    private static $contribuyente_rut;

    public static function setUpBeforeClass(): void
    {
        self::$verbose = env('TEST_VERBOSE', false);
        self::$client = new DteContribuyentes();
        $firma_public_key = env('TEST_USUARIO_FIRMA_PUBLIC_KEY');
        $firma_private_key = env('TEST_USUARIO_FIRMA_PRIVATE_KEY');
        self::$auth = [
            'cert' => [
                'cert-data' => $firma_public_key,
                'pkey-data' => $firma_private_key,
            ],
        ];
        self::$contribuyente_rut = env('TEST_CONTRIBUYENTE_RUT');
    }

    public function testVerificarAutorizacionDte()
    {
        # TODO: Consultar por este test.
        $certificacion = env('TEST_SII_AMBIENTE', 0); // =1 certificación, =0 producción

        // Ruta base para el directorio actual (archivo ejecutándose en
        // "tests/dte_facturacion")
        $currentDir = __DIR__;

        // Nueva ruta relativa para guardar el archivo PDF en "tests/archivos"
        $targetDir = dirname(dirname($currentDir)) . '/archivos/dte_emit_mipyme_pdf';

        $filename = $targetDir . '/contribuyentes.csv';
        $resource = fopen($filename, 'w');

        try {
            $response = self::$client->verificarAutorizacion(
                self::$contribuyente_rut,
                $certificacion
            );

            $filesize = filesize($filename);
            fclose($resource);
            unlink($filename);
            $this->assertSame(200, $response->getStatusCode());
            if (self::$verbose) {
                echo "\n",'test_dte_contribuyentes_autorizados() filename ',$filename,"\n";
                echo "\n",'test_dte_contribuyentes_autorizados() filesize ',$filesize,"\n";
            }
        } catch (ApiException $e) {
            fclose($resource);
            unlink($filename);
            $this->fail(sprintf(
                '[ApiException %d] %s',
                $e->getCode(),
                $e->getMessage()
            ));
        }

    }
}
