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
 * Módulo para interactuar con Boletas de Honorarios Electrónicas emitidas del SII.
 *
 * Para más información sobre la API, consulte la `documentación completa de
 * las BHE <https://developers.apigateway.cl/#6181f73d-3ffa-4940-a2f3-1ac3607536ec>`_.
 */
class BheEmitidas extends ApiBase
{
    // Quién debe hacer la retención asociada al honorario para pagar al SII
    /**
     * Constante de causa de retención por parte del emisor.
     * @var int
     */
    public const RETENCION_RECEPTOR = 1;

    /**
     * Constante de causa de retención por parte del receptor.
     * @var int
     */
    public const RETENCION_EMISOR = 2;

    // Posibles motivos de anulación de una BHE
    /**
     * Constante de motivo de anulación de BHE por no pago.
     * @var int
     */
    public const ANULACION_CAUSA_SIN_PAGO = 1;

    /**
     * Constante de motivo de anulación de BHE por no tener prestación.
     * @var int
     */
    public const ANULACION_CAUSA_SIN_PRESTACION = 2;

    /**
     * Constante de motivo de anulación de BHE por error de digitación.
     * @var int
     */
    public const ANULACION_CAUSA_ERROR_DIGITACION = 3;

    /**
     * Cliente específico para gestionar Boletas de Honorarios Electrónicas
     * (BHE) emitidas.
     *
     * Provee métodos para emitir, anular, y consultar información relacionada
     * con BHEs.
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
     * Obtiene los documentos de BHE emitidos por un emisor en un periodo específico.
     *
     * @param string $emisor RUT del emisor de las boletas.
     * @param string $periodo Período de tiempo de las boletas emitidas.
     * @param int|null $pagina Número de página para paginación (opcional).
     * @param string|null $pagina_sig_codigo Código para la siguiente
     * página (opcional).
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con los
     * documentos de BHE.
     */
    public function listarBhesEmitidas(
        string $emisor,
        string $periodo,
        int $pagina = null,
        string $pagina_sig_codigo = null
    ): ResponseInterface {
        $url = sprintf(
            '/sii/bhe/emitidas/documentos/%s/%s',
            $emisor,
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
                    $pagina_sig_codigo ?? '0'
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
     * Emite una nueva Boleta de Honorarios Electrónica.
     *
     * @param array $boleta Información detallada de la boleta a emitir.
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con la
     * confirmación de la emisión de la BHE.
     */
    public function emitirBhe(array $boleta): ResponseInterface
    {
        $url = '/sii/bhe/emitidas/emitir';
        $body = [
            'auth' => $this->getAuthPass(),
            'boleta' => $boleta,
        ];

        $response = $this->post(resource: $url, data: $body);

        return $response;
    }

    /**
     * Obtiene el PDF de una BHE emitida.
     *
     * @param string $codigo Código único de la BHE.
     * @return \Psr\Http\Message\ResponseInterface Contenido del PDF de la BHE.
     */
    public function descargarPdfBheEmitida(string $codigo): ResponseInterface
    {
        $url = sprintf(
            '/sii/bhe/emitidas/pdf/%s',
            $codigo
        );
        $body = [
            'auth' => $this->getAuthPass(),
        ];
        $response = $this->post(resource: $url, data: $body);

        return $response;
    }

    /**
     * Envía por correo electrónico una BHE emitida.
     *
     * @param string $codigo Código único de la BHE a enviar.
     * @param string $email Dirección de correo electrónico a la cual enviar la BHE.
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con la
     * confirmación del envío del email.
     */
    public function enviarEmailBheEmitida(
        string $codigo,
        string $email
    ): ResponseInterface {
        $url = sprintf(
            '/sii/bhe/emitidas/email/%s',
            $codigo
        );
        $body = [
            'auth' => $this->getAuthPass(),
            'destinatario' => ['email' => $email],
        ];
        $response = $this->post(resource: $url, data: $body);

        return $response;
    }

    /**
     * Anula una BHE emitida.
     *
     * @param string $emisor RUT del emisor de la boleta.
     * @param string $folio Número de folio de la boleta.
     * @param int $causa Motivo de anulación de la boleta.
     * @return \Psr\Http\Message\ResponseInterface Respuesta JSON con la
     * confirmación de la anulación de la BHE.
     */
    public function anularBheEmitida(
        string $emisor,
        string $folio,
        int $causa = self::ANULACION_CAUSA_ERROR_DIGITACION
    ) {
        $url = sprintf(
            '/sii/bhe/emitidas/anular/%s/%s?causa=%d',
            $emisor,
            $folio,
            $causa
        );
        $body = [
            'auth' => $this->getAuthPass(),
        ];
        $response = $this->post(resource: $url, data: $body);

        return $response;
    }
}
