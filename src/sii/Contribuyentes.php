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

/*
 * Módulo para obtener datos de los contribuyentes a través del SII.
 *
 * Para más información sobre la API, consulte la `documentación completa de
 * los Contribuyentes <https://developers.apigateway.cl/#c88f90b6-36bb-4dc2-ba93-6e418ff42098>`_.
 *
 * Cliente específico para interactuar con los endpoints de contribuyentes
 * de la API de API Gateway.
 *
 * Hereda de ApiClient y utiliza su funcionalidad para realizar solicitudes a la API.
 */
class Contribuyentes extends ApiBase
{
    /**
     * Obtiene la situación tributaria de un contribuyente.
     *
     * @param string $rut RUT del contribuyente.
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con la
     * situación tributaria del contribuyente.
     */
    public function obtenerSituacionTributaria(string $rut): ResponseInterface
    {
        $url = sprintf(
            '/sii/contribuyentes/situacion_tributaria/tercero/%s',
            $rut
        );
        $response = $this->get(resource: $url);

        return $response;
    }

    /**
     * Verifica el RUT de un contribuyente.
     *
     * @param string $serie Serie del RUT a verificar.
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con la
     * verificación del RUT.
     */
    public function verificarRut(string $serie): ResponseInterface
    {
        $url = sprintf(
            '/sii/contribuyentes/rut/verificar/%s',
            $serie
        );
        $response = $this->get(resource: $url);

        return $response;
    }
}
