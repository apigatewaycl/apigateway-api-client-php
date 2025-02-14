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
use Psr\Http\Message\ResponseInterface;

/**
 * Módulo para interactuar con la sección MiSii de un contribuyente en el
 * sitio web del SII.
 *
 * Para más información sobre la API, consulte la `documentación completa de
 * MiSii <https://developers.apigateway.cl/#b585f374-f106-46a9-9f47-666d478b8ac8>`_.
 */
class Misii extends ApiBase
{
    /**
     * Cliente específico para interactuar con los endpoints de un Contribuyente
     * de MiSii de la API de API Gateway.
     *
     * Hereda de ApiBase y utiliza su funcionalidad para realizar solicitudes
     * a la API.
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

    /**
     * Obtiene los datos de MiSii del contribuyente autenticado en el SII.
     *
     * @param mixed|null $auth_cache Parámetro de caché de autenticación. Si es 0,
     * se refrescará la caché. Si se obtiene "Too many requests", no
     * usar este parámetro.
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con los
     * datos del contribuyente.
     */
    public function obtenerDatosContribuyenteMisii(
        mixed $auth_cache = null
    ): ResponseInterface {
        $url = '/sii/misii/contribuyente/datos';
        if ($auth_cache != null) {
            $url = $url.'?auth_cache=0';
        }

        $body = [
            'auth' => $this->getAuthPass(),
        ];
        $response = $this->post(resource: $url, data: $body);
        return $response;
    }
}
