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

/**
 * Módulo para consultas de DTEs al Portal MIPYME del SII.
 *
 * Para más información sobre la API, consulte la `documentación completa del
 * Portal MIPYME <https://developers.apigateway.cl/#d545a096-09be-4c9e-8d12-7b86b6bf1be6>`_.
 *
 * Base para los clientes específicos de DTE del Portal Mipyme.
 * Incluye constantes para diferentes estados de DTE.
 */
class PortalMipymeDte extends PortalMiPyme
{
    /**
     * Abreviatura: Documento emitido
     * @var string
     */
    protected const ESTADO_EMITIDO = 'EMI'; # Documento emitido

    /**
     * Abreviatura: Borrador (pre-view) de documento
     * @var string
     */
    protected const ESTADO_BORRADOR = 'PRV'; # Borrador (pre-view) de documento

    /**
     * Abreviatura: Certificado rechazado
     * @var string
     */
    protected const ESTADO_CERTIFICADO_RECHAZADO = 'DCD'; # Certificado rechazado

    /**
     * Abreviatura: RUT emisor inválido
     * @var string
     */
    protected const ESTADO_EMISOR_INVALIDO = 'DEI'; # RUT emisor inválido

    /**
     * Abreviatura: Folio DTE inválido
     * @var string
     */
    protected const ESTADO_FOLIO_INVALIDO = 'DFI'; # Folio DTE inválido

    /**
     * Abreviatura: Incompleto
     * @var string
     */
    protected const ESTADO_INCOMPLETO = 'DIN'; # Incompleto

    /**
     * Abreviatura: Sin permiso de firma
     * @var string
     */
    protected const ESTADO_FIRMA_SIN_PERMISO = 'DPF'; # Sin permiso de firma

    /**
     * Abreviatura: DTE rechazado por firma
     * @var string
     */
    protected const ESTADO_FIRMA_RECHAZADA = 'DRF'; # DTE rechazado por firma

    /**
     * Abreviatura: RUT receptor inválido
     * @var string
     */
    protected const ESTADO_RECEPTOR_INVALIDO = 'DRI'; # RUT receptor inválido

    /**
     * Abreviatura: Rechazado por repetido
     * @var string
     */
    protected const ESTADO_REPETIDO = 'DRR'; # Rechazado por repetido

    /**
     * Abreviatura: DTE inicializado
     * @var string
     */
    protected const ESTADO_INICIALIZADO = 'INI'; # DTE inicializado

    /**
     * Abreviatura: DTE aceptado por receptor
     * @var string
     */
    protected const ESTADO_ACEPTADO = 'RAC'; # DTE aceptado por receptor

    /**
     * Abreviatura: DTE aceptado con discrepancias
     * @var string
     */
    protected const ESTADO_DISCREPANCIAS = 'RAD'; # DTE aceptado con discrepancias

    /**
     * Abreviatura: DTE no recibido por receptor
     * @var string
     */
    protected const ESTADO_NO_RECIBIDO = 'RNR'; # DTE no recibido por receptor

    /**
     * Abreviatura: DTE recibido por receptor
     * @var string
     */
    protected const ESTADO_RECIBIDO = 'RRC'; # DTE recibido por receptor

    /**
     * Abreviatura: DTE aceptado Ley 19.983
     * @var string
     */
    protected const ESTADO_ACEPTADO_LEY_19983 = 'RAL'; # DTE aceptado Ley 19.983

    /**
     * Abreviatura: DTE rechazado por receptor
     * @var string
     */
    protected const ESTADO_RECHAZADO_RECEPTOR = 'RRH'; # DTE rechazado por receptor

    /**
     * Abreviatura: Recibido sin reparos
     * @var string
     */
    protected const ESTADO_SIN_REPAROS = 'RSR'; # Recibido sin reparos

    /**
     * Obtiene el código correspondiente al tipo de DTE.
     *
     * @param string $tipo Tipo de DTE.
     * @return string Código del DTE.
     */
    public function getCodigoDte(string $tipo): string
    {
        # TODO: Resolver este fragmento de código: ¿¿QUÉ ES "DTE_TIPOS"??
        return isset($this->DTE_TIPOS[$tipo]) ? $this->DTE_TIPOS[$tipo] : str_replace(' ', '-', $tipo);
    }
}
