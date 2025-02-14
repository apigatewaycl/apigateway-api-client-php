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
 * Módulo para interactuar con las opciones de Documentos Tributarios
 * Electrónicos (DTE) del SII.
 *
 * Para más información sobre la API, consulte la `documentación completa
 * de los DTE <https://developers.apigateway.cl/#8c113b9a-ea05-4981-9273-73e3f20ef991>`_.
 *
 * Cliente específico para interactuar con los endpoints de contribuyentes
 * de la API de API Gateway.
 *
 * Proporciona métodos para consultar la autorización de emisión de DTE
 * de un contribuyente.
 */
class DteContribuyentes extends ApiBase
{
    /**
     * Verifica si un contribuyente está autorizado para emitir DTE.
     *
     * @param string $rut RUT del contribuyente a verificar.
     * @param bool|null $certificacion Indica si se consulta en ambiente
     * de certificación (opcional).
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con el
     * estado de autorización del contribuyente.
     */
    public function verificarAutorizacion(
        string $rut,
        bool $certificacion = null
    ): ResponseInterface {
        $certificacion_flag = $certificacion ? 1 : 0;
        $url = sprintf(
            '/sii/dte/contribuyentes/autorizado/%s?certificacion=%d',
            $rut,
            $certificacion_flag
        );
        $body = [
            'auth' => $this->getAuthPass(),
        ];
        $response = $this->post(resource: $url, data: $body);

        return $response;
    }
}
