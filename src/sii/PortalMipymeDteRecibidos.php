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

use Psr\Http\Message\ResponseInterface;

/**
 * Módulo para consultas de DTEs recibidos al Portal MIPYME del SII.
 *
 * Para más información sobre la API, consulte la `documentación completa de DTEs
 * recibidos en el Portal MIPYME <https://developers.apigateway.cl/#24456c78-2c1d-4e91-a0b3-450eab612e7e>`_.
 *
 * Cliente específico para gestionar DTE recibidos en el Portal Mipyme.
 * Proporciona métodos para obtener documentos, PDF y XML de DTE recibidos.
 */
class PortalMipymeDteRecibidos extends PortalMiPymeDte
{
    /**
     * Obtiene documentos de DTE recibidos por un receptor.
     *
     * @param string $receptor RUT del receptor.
     * @param array $filtros Filtros adicionales para la consulta.
     * @return \Psr\Http\Message\ResponseInterface Documentos de DTE recibidos.
     */
    public function obtenerDtesRecibidos(
        string $receptor,
        array $filtros = []
    ): ResponseInterface {
        $url = sprintf(
            '/sii/mipyme/recibidos/documentos/%s',
            $receptor
        );

        $body = [
            'auth' => $this->getAuthPass(),
            'filtros' => $filtros,
        ];
        $response = $this->post(resource: $url, data: $body);
        return $response;
    }

    /**
     * Obtiene el PDF de un DTE recibido.
     *
     * @param string $receptor RUT del receptor.
     * @param string $emisor RUT del emisor.
     * @param string $dte Tipo de DTE o código del DTE recibido si no se pasa folio.
     * @param string $folio Número de folio del DTE (opcional).
     * @return \Psr\Http\Message\ResponseInterface Contenido del PDF del DTE recibido.
     */
    public function descargarPdfDteRecibido(
        string $receptor,
        string $emisor,
        string $dte,
        string $folio = null
    ): ResponseInterface {
        $url = $folio != null ? sprintf(
            '/sii/mipyme/recibidos/pdf/%s/%s/%s/%s',
            $receptor,
            $emisor,
            $dte,
            $folio
        ) : sprintf(
            '/sii/mipyme/recibidos/pdf/%s/%s/%s',
            $receptor,
            $emisor,
            $dte,
        );

        $body = [
            'auth' => $this->getAuthPass(),
        ];
        $response = $this->post(resource: $url, data: $body);
        return $response;
    }

    /**
     * Obtiene el XML de un DTE recibido.
     *
     * @param string $receptor RUT del receptor.
     * @param string $emisor RUT del emisor.
     * @param string $dte Tipo de DTE.
     * @param string $folio Número de folio del DTE.
     * @return \Psr\Http\Message\ResponseInterface Contenido del XML del
     * DTE recibido.
     */
    public function descargarXmlDteRecibido(
        string $receptor,
        string $emisor,
        string $dte,
        string $folio
    ): ResponseInterface {
        $url = sprintf(
            '/sii/mipyme/recibidos/xml/%s/%s/%s/%s',
            $receptor,
            $emisor,
            $dte,
            $folio
        );

        $body = [
            'auth' => $this->getAuthPass(),
        ];
        $response = $this->post(resource: $url, data: $body);
        return $response;
    }
}
