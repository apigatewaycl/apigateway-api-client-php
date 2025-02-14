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
 * Módulo para interactuar con el Registro de Compra y Venta del SII.
 *
 * Para más información sobre la API, consulte la `documentación completa del
 * RCV <https://developers.apigateway.cl/#ef1f7d54-2e86-4732-bb91-d3448b383d66>`_.
 */
class Rcv extends ApiBase
{
    /**
     * Cliente específico para interactuar con los endpoints de Registro de
     * Compras y Ventas (RCV) de la API de API Gateway.
     *
     * Proporciona métodos para obtener resúmenes y detalles de compras y ventas.
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
     * Obtiene un resumen de las compras registradas para un receptor en un
     * periodo específico.
     *
     * @param string $receptor RUT del receptor de las compras.
     * @param string $periodo Período de tiempo de las compras.
     * @param string $estado Estado de las compras ('REGISTRO', 'PENDIENTE',
     * 'NO_INCLUIR', 'RECLAMADO').
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con
     * el resumen de compras.
     */
    public function obtenerResumenCompras(
        string $receptor,
        string $periodo,
        string $estado = 'REGISTRO'
    ): ResponseInterface {
        $url = sprintf(
            '/sii/rcv/compras/resumen/%s/%s/%s',
            $receptor,
            $periodo,
            $estado
        );

        $body = [
            'auth' => $this->getAuthPass(),
        ];
        $response = $this->post(resource: $url, data: $body);
        return $response;
    }

    /**
     * Obtiene detalles de las compras para un receptor en un periodo específico.
     *
     * @param string $receptor RUT del receptor de las compras.
     * @param string $periodo Período de tiempo de las compras.
     * @param int $dte Tipo de DTE.
     * @param string $estado Estado de las compras ('REGISTRO', 'PENDIENTE',
     * 'NO_INCLUIR', 'RECLAMADO').
     * @param string|null $tipo Tipo de formato de respuesta ('rcv_csv' o 'rcv').
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con
     * detalles de las compras.
     */
    public function obtenerDetalleCompras(
        string $receptor,
        string $periodo,
        int $dte = 0,
        string $estado = 'REGISTRO',
        string $tipo = null
    ): ResponseInterface {
        $tipo = (
            $dte == 0 &&
            $estado == 'REGISTRO'
        ) ? 'rcv_csv' : ($tipo ?? 'rcv');
        $url = sprintf(
            '/sii/rcv/compras/detalle/%s/%s/%d/%s?tipo=%s',
            $receptor,
            $periodo,
            $dte,
            $estado,
            $tipo
        );
        $body = [
            'auth' => $this->getAuthPass(),
        ];
        $response = $this->post(resource: $url, data: $body);
        return $response;
    }

    /**
     * Obtiene un resumen de las ventas registradas para un emisor en un
     * periodo específico.
     *
     * @param string $emisor RUT del emisor de las ventas.
     * @param string $periodo Período de tiempo de las ventas.
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con
     * el resumen de ventas.
     */
    public function obtenerResumenVentas(
        string $emisor,
        string $periodo
    ): ResponseInterface {
        $url = sprintf(
            '/sii/rcv/ventas/resumen/%s/%s',
            $emisor,
            $periodo
        );

        $body = [
            'auth' => $this->getAuthPass(),
        ];
        $response = $this->post(resource: $url, data: $body);
        return $response;
    }

    /**
     * Obtiene detalles de las ventas para un emisor en un periodo específico.
     *
     * @param string $emisor RUT del emisor de las ventas.
     * @param string $periodo Período de tiempo de las ventas.
     * @param int $dte Tipo de DTE.
     * @param string $tipo Tipo de formato de respuesta ('rcv_csv' o 'rcv').
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con
     * detalles de las ventas.
     */
    public function obtenerDetalleVentas(
        string $emisor,
        string $periodo,
        int $dte = 0,
        string $tipo = null
    ): ResponseInterface {
        $tipo = $dte == 0 ? 'rcv_csv' : ($tipo ?? 'rcv');
        $url = sprintf(
            '/sii/rcv/ventas/detalle/%s/%s/%d?tipo=%s',
            $emisor,
            $periodo,
            $dte,
            $tipo
        );
        $body = [
            'auth' => $this->getAuthPass(),
        ];
        $response = $this->post(resource: $url, data: $body);
        return $response;
    }
}
