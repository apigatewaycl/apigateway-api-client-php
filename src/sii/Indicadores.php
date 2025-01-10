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

/*
 * Módulo para obtener indicadores desde el SII.
 *
 * Para más información sobre la API, consulte la `documentación completa de
 * los Indicadores <https://developers.apigateway.cl/#65aa568c-4c5a-448b-9f3b-95c3d9153e4d>`_.
 *
 * Cliente específico para interactuar con los endpoints de valores de UF
 * (Unidad de Fomento) de la API de API Gateway.
 *
 * Provee métodos para obtener valores de UF anuales, mensuales y diarios.
 */
class Indicadores extends ApiBase
{
    /**
     * Obtiene los valores de la UF para un año específico.
     *
     * @param int $anio Año para el cual se quieren obtener los valores de la UF.
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con los
     * valores de la UF del año especificado.
     */
    public function anual(int $anio)
    {
        $url = sprintf(
            '/sii/indicadores/uf/anual/%d',
            $anio
        );
        $response = $this->get($url);
        return $response;
    }

    /**
     * Obtiene los valores de la UF para un mes específico.
     *
     * @param string $periodo Período en formato AAAAMM (año y mes).
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con los
     * valores de la UF del mes especificado.
     */
    public function mensual(string $periodo)
    {
        $anio = substr($periodo, 0, 4);
        $mes = substr($periodo, 4, 2);
        $url = sprintf(
            '/sii/indicadores/uf/anual/%s/%s',
            $anio,
            $mes
        );
        $response = $this->get($url);
        return $response;
    }

    /**
     * Obtiene el valor de la UF para un día específico.
     *
     * @param string $fecha Fecha en formato AAAA-MM-DD.
     * @return \Psr\Http\Message\ResponseInterface Valor de la UF para
     * el día especificado.
     */
    public function diario(string $fecha)
    {
        $partes = explode('-', $fecha);
        $anio = $partes[0];
        $mes = $partes[1];
        $dia = $partes[2];

        $url = sprintf(
            '/sii/indicadores/uf/anual/%s/%s/%s',
            $anio,
            $mes,
            $dia
        );
        $response = $this->get($url);
        return $response;
    }
}
