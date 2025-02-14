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
 * Módulo para la emisión de Boletas de Terceros Electrónicas del SII.
 *
 * Para más información sobre la API, consulte la `documentación completa de las
 * BTE <https://developers.apigateway.cl/#e08f50ab-5509-48ab-81ab-63fc8e5985e1>`_.
 */
class BteEmitidas extends ApiBase
{
    /**
     * Cliente específico para gestionar Boletas de Terceros Electrónicas (BTE) emitidas.
     *
     * Provee métodos para emitir, anular, y consultar información relacionada con BTEs.
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
     * Obtiene los documentos BTE emitidos por un emisor en un periodo específico.
     *
     * @param string $emisor RUT del emisor de las BTE.
     * @param string $periodo Período de las BTE emitidas.
     * @param int|null $pagina Número de página para paginación (opcional).
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con
     * los documentos BTE.
     */
    public function listarBtesEmitidas(
        string $emisor,
        string $periodo,
        int $pagina = null
    ): ResponseInterface {
        $url = sprintf(
            '/sii/bte/emitidas/documentos/%s/%s',
            $emisor,
            $periodo
        );
        if ($pagina != null) {
            $url = sprintf(
                $url.'?pagina=%d',
                $pagina
            );
        }
        $body = [
            'auth' => $this->getAuthPass(),
        ];
        $response = $this->post(resource: $url, data: $body);
        return $response;
    }

    /**
     * Obtiene la representación HTML de una BTE emitida.
     *
     * @param string $codigo Código único de la BTE.
     * @return \Psr\Http\Message\ResponseInterface Contenido HTML de la BTE.
     */
    public function obtenerHtmlBteEmitida(string $codigo): ResponseInterface
    {
        $url = sprintf(
            '/sii/bte/emitidas/html/%s',
            $codigo
        );
        $body = [
            'auth' => $this->getAuthPass(),
        ];
        $response = $this->post(resource: $url, data: $body);
        return $response;
    }

    /**
     * Emite una nueva Boleta de Tercero Electrónica.
     *
     * @param array $datos Datos de la boleta a emitir.
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con la
     * confirmación de la emisión de la BTE.
     */
    public function emitirBte(array $datos): ResponseInterface
    {
        $url = '/sii/bte/emitidas/emitir';
        $body = [
            'auth' => $this->getAuthPass(),
            'boleta' => $datos,
        ];

        $response = $this->post(resource: $url, data: $body);
        return $response;
    }

    /**
     * Anula una BTE emitida.
     *
     * @param string $emisor RUT del emisor de la boleta.
     * @param string $numero Número de la boleta.
     * @param int $causa Causa de anulación.
     * @param string|null $periodo Período de emisión de la boleta (opcional).
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con la
     * confirmación de la anulación.
     */
    public function anularBteEmitida(
        string $emisor,
        string $numero,
        int $causa = 3,
        string $periodo = null
    ): ResponseInterface {
        $url = sprintf(
            '/sii/bhe/emitidas/anular/%s/%s?causa=%d',
            $emisor,
            $numero,
            $causa
        );
        if ($periodo != null) {
            $url = $url.sprintf('?periodo=%s', $periodo);
        }
        $body = [
            'auth' => $this->getAuthPass(),
        ];

        $response = $this->post(resource: $url, data: $body);
        return $response;
    }

    /**
     * Obtiene la tasa de retención aplicada a un receptor por un emisor específico.
     *
     * @param string $emisor RUT del emisor de la boleta.
     * @param string $receptor RUT del receptor de la boleta.
     * @param string|null $periodo Período de emisión de la boleta (opcional).
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con la tasa de retención.
     */
    public function obtenerTasaReceptorBte(
        string $emisor,
        string $receptor,
        string $periodo = null
    ): ResponseInterface {
        $url = sprintf(
            '/sii/bte/emitidas/receptor_tasa/%s/%s',
            $emisor,
            $receptor
        );
        if ($periodo != null) {
            $url = $url.sprintf('?periodo=%s', $periodo);
        }
        $body = [
            'auth' => $this->getAuthPass(),
        ];

        $response = $this->post(resource: $url, data: $body);
        return $response;
    }
}
