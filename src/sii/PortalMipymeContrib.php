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
 * Módulo para consultar info de contribuyente al Portal MIPYME del SII.
 *
 * Para más información sobre la API, consulte la `documentación completa de contribuyentes
 * en Portal MIPYME <https://developers.apigateway.cl/#cf870509-60bd-4788-a8ba-36f9b1b72bca>`_.
 *
 * Cliente específico para interactuar con los endpoints de contribuyentes del Portal Mipyme.
 */
class PortalMipymeContrib extends PortalMiPyme
{
    /**
     * Obtiene información de un contribuyente específico.
     *
     * @param string $contribuyente RUT del contribuyente.
     * @param string $emisor RUT del emisor del DTE.
     * @param int $dte Tipo de DTE.
     * @return \Psr\Http\Message\ResponseInterface Datos del contribuyente.
     */
    public function obtenerInfoContribuyenteMipyme(
        $contribuyente,
        $emisor,
        $dte = 33
    ): ResponseInterface {
        $url = sprintf(
            '/sii/mipyme/contribuyentes/info/%s/%s/%d',
            $contribuyente,
            $emisor,
            $dte
        );

        $body = [
            'auth' => $this->getAuthPass(),
        ];
        $response = $this->post(resource: $url, data: $body);
        return $response;
    }
}
