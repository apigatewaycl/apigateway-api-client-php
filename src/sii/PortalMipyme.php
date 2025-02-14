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

use apigatewaycl\api_client\ApiBase;

/**
 * Módulo para consultas al Portal MIPYME del SII.
 *
 * Para más información sobre la API, consulte la `documentación completa del
 * Portal MIPYME <https://developers.apigateway.cl/#d545a096-09be-4c9e-8d12-7b86b6bf1be6>`_.
 */
class PortalMipyme extends ApiBase
{
    /**
     * Base para los clientes específicos del Portal Mipyme.
     *
     * @param array $credenciales Credenciales de autenticación.
     * @param string|null $token Token de autenticación para la API.
     * @param string|null $url URL base para la API.
     */
    public function __construct(
        array $credenciales,
        string $token = null,
        string $url = null
    ) {
        parent::__construct(
            credenciales: $credenciales,
            token: $token,
            url: $url
        );
    }
}
