<?php

/**
 * API Gateway
 * Copyright (C) SASCO SpA (https://sasco.cl)
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

/**
 * Ejemplo que muestra los pasos para:
 *  - Obtener listado de contribuyentes autorizados a facturar electrónicamente (formato CSV o JSON).
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
 * @version 2020-01-26
 */

// datos a utilizar
$url = getenv('LIBREDTE_API_URL');
$token = getenv('LIBREDTE_API_TOKEN');
$dia = date('Y-m-d');
$formato = 'csv_sii'; // json, csv o csv_sii (este último es el formato más rápido)
$certificacion = 0; // =1 certificación, =0 producción
$firma_public_key =  getenv('LIBREDTE_USUARIO_FIRMA_PUBLIC_KEY');
$firma_private_key = getenv('LIBREDTE_USUARIO_FIRMA_PRIVATE_KEY');

// incluir autocarga de composer
require('../../../../vendor/autoload.php');

// crear cliente
$LibreDTE = new \sasco\LibreDTE\API\LibreDTE($token, $url);

// obtener datos de contribuyentes
try {
    $LibreDTE->consume('/sii/dte/contribuyentes/autorizados?dia='.$dia.'&formato='.$formato.'&certificacion='.$certificacion, [
        'auth' => [
            'cert' => [
                'cert-data' => $firma_public_key,
                'pkey-data' => $firma_private_key
            ],
        ],
    ]);
} catch (\sasco\LibreDTE\API\Exception $e) {
    die('Error #'.$e->getCode().': '.$e->getMessage()."\n");
}

// guardar datos en el disco
if (in_array($formato, ['csv', 'csv_sii'])) {
    file_put_contents(str_replace('.php', '.csv', basename(__FILE__)), $LibreDTE->getBody());
} else {
    file_put_contents(str_replace('.php', '.json', basename(__FILE__)), json_encode($LibreDTE->getBodyDecoded(), JSON_PRETTY_PRINT));
}
