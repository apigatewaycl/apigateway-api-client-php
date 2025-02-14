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
 * Módulo para interactuar con Boletas de Honorarios Electrónicas recibidas del SII.
 *
 * Para más información sobre la API, consulte la `documentación completa de
 * las BHE <https://developers.apigateway.cl/#7de04cde-a3e4-4ab5-b64a-e0fec7f7a5e9>`_.
 */
class BheRecibidas extends ApiBase
{
    /**
     * Cliente específico para gestionar Boletas de Honorarios Electrónicas
     * (BHE) recibidas.
     *
     * Provee métodos para obtener documentos, obtener PDF y observar
     * BHE recibidas.
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
     * Obtiene los documentos de BHE recibidos por un receptor en un periodo específico.
     *
     * @param string $receptor RUT del receptor de las boletas.
     * @param string $periodo Período de tiempo de las boletas recibidas.
     * @param int|null $pagina Número de página para paginación (opcional).
     * @param string|null $pagina_sig_codigo Código para la siguiente página (opcional).
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con
     * los documentos de BHE.
     */
    public function listarBhesRecibidas(
        string $receptor,
        string $periodo,
        int $pagina = null,
        string $pagina_sig_codigo = null
    ): ResponseInterface {
        $url = sprintf(
            '/sii/bhe/recibidas/documentos/%s/%s',
            $receptor,
            $periodo
        );
        if ($pagina != null) {
            $url = sprintf(
                $url.'?pagina=%d',
                $pagina
            );
            if ($pagina_sig_codigo != null) {
                $url = sprintf(
                    $url.'&pagina_sig_codigo=%s',
                    $pagina_sig_codigo ?? '00000000000000'
                );
            }
        }
        $body = [
            'auth' => $this->getAuthPass(),
        ];
        $response = $this->post(resource: $url, data: $body);
        return $response;
    }

    /**
     * Obtiene el PDF de una BHE recibida.
     *
     * @param string $codigo Código único de la BHE.
     * @return \Psr\Http\Message\ResponseInterface Contenido del PDF de la BHE.
     */
    public function descargarPdfBheRecibida(string $codigo): ResponseInterface
    {
        $url = sprintf(
            '/sii/bhe/recibidas/pdf/%s',
            $codigo
        );
        $body = [
            'auth' => $this->getAuthPass(),
        ];
        $response = $this->post(resource: $url, data: $body);

        return $response;
    }

    /**
     * Marca una observación en una BHE recibida.
     *
     * @param string $emisor RUT del emisor de la boleta.
     * @param string $numero Número de la boleta.
     * @param int $causa Motivo de la observación.
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con la
     * confirmación de la observación.
     */
    public function observarBheRecibida(
        string $emisor,
        string $numero,
        int $causa = 1
    ): ResponseInterface {
        $url = sprintf(
            '/sii/bhe/recibidas/observar/%s/%s/%d',
            $emisor,
            $numero,
            $causa
        );
        $body = [
            'auth' => $this->getAuthPass(),
        ];
        $response = $this->post(resource: $url, data: $body);

        return $response;
    }
}
