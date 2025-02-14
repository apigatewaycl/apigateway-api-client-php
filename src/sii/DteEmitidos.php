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
 * Para más información sobre la API, consulte la `documentación completa de
 * los DTE <https://developers.apigateway.cl/#8c113b9a-ea05-4981-9273-73e3f20ef991>`_.
 */
class DteEmitidos extends ApiBase
{
    /**
     * Cliente específico para gestionar DTE emitidos.
     *
     * Permite verificar la validez y autenticidad de un DTE emitido.
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
     * Verifica la validez de un DTE emitido.
     *
     * @param string $emisor RUT del emisor del DTE.
     * @param string $receptor RUT del receptor del DTE.
     * @param int $dte Tipo de DTE.
     * @param int $folio Número de folio del DTE.
     * @param string $fecha Fecha de emisión del DTE.
     * @param int $total Monto total del DTE.
     * @param string|null $firma Firma electrónica del DTE (opcional).
     * @param bool|null $certificacion Indica si la verificación es en ambiente
     * de certificación (opcional).
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con el
     * resultado de la verificación del DTE.
     */
    public function verificarDteEmitido(
        string $emisor,
        string $receptor,
        int $dte,
        int $folio,
        string $fecha,
        int $total,
        string $firma = null,
        bool $certificacion = null
    ): ResponseInterface {
        $certificacion_flag = $certificacion ? 1 : 0;
        $url = sprintf(
            '/sii/dte/emitidos/verificar?certificacion=%d',
            $certificacion_flag
        );
        $body = [
            'auth' => $this->getAuthPass(),
            'dte' => [
                'emisor' => $emisor,
                'receptor' => $receptor,
                'dte' => $dte,
                'folio' => $folio,
                'fecha' => $fecha,
                'total' => $total,
                'firma' => $firma,
            ],
        ];
        $response = $this->post(resource: $url, data: $body);

        return $response;
    }
}
