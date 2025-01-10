<?php

declare(strict_types=1);

/**
 * API Gateway: Cliente de API en PHP.
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

namespace apigatewaycl\api_client\sii;

use apigatewaycl\api_client\sii\PortalMipymeDte;

/**
 * Módulo para consultas de DTEs emitidos al Portal MIPYME del SII.
 *
 * Para más información sobre la API, consulte la `documentación completa de DTEs
 * emitidos en el Portal MIPYME <https://developers.apigateway.cl/#c0d3d6f5-ae5e-47dc-891e-72f7beed93ca>`_.
 *
 * Cliente específico para gestionar DTE emitidos en el Portal Mipyme.
 */
class PortalMipymeDteEmitidos extends PortalMiPymeDte
{
    /**
     * Obtiene documentos de DTE emitidos por un emisor.
     *
     * @param string $emisor RUT del emisor.
     * @param array $filtros Filtros adicionales para la consulta.
     * @return \Psr\Http\Message\ResponseInterface Documentos de DTE emitidos.
     */
    public function obtenerDtesEmitidos(string $emisor, array $filtros = [])
    {
        $url = sprintf(
            '/sii/mipyme/emitidos/documentos/%s',
            $emisor
        );

        $body = [
            'auth' => $this->getAuthPass(),
            'filtros' => $filtros,
        ];
        $response = $this->post($url, $body);
        return $response;
    }

    /**
     * Obtiene el PDF de un DTE emitido.
     *
     * @param string $emisor RUT del emisor.
     * @param string $dte Tipo de DTE o código del DTE emitido si no se pasa folio.
     * @param string $folio Número de folio del DTE (opcional).
     * @return \Psr\Http\Message\ResponseInterface Contenido del PDF del DTE emitido.
     */
    public function descargarPdfDteEmitido(
        string $emisor,
        string $dte,
        string $folio = null
    )
    {
        $url = $folio != null ? sprintf(
            '/sii/mipyme/emitidos/pdf/%s/%s/%s',
            $emisor,
            $dte,
            $folio
        ) : sprintf(
            '/sii/mipyme/emitidos/pdf/%s/%s',
            $emisor,
            $dte,
        );

        $body = [
            'auth' => $this->getAuthPass(),
        ];
        $response = $this->post($url, $body);
        return $response;
    }

    /**
     * Obtiene el XML de un DTE emitido.
     *
     * @param string $emisor RUT del emisor.
     * @param string $dte Tipo de DTE.
     * @param string $folio Número de folio del DTE.
     * @return \Psr\Http\Message\ResponseInterface Contenido del XML del DTE emitido.
     */
    public function descargarXmlDteEmitido(
        string $emisor,
        string $dte,
        string $folio
    )
    {
        $url = sprintf(
            '/sii/mipyme/emitidos/xml/%s/%s/%s',
            $emisor,
            $dte,
            $folio
        );

        $body = [
            'auth' => $this->getAuthPass(),
        ];
        $response = $this->post($url, $body);
        return $response;
    }
}
