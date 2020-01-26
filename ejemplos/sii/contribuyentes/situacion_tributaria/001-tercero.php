<?php

/**
 * LibreDTE
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
 *  - Consultar la situación tributaria de un contribuyente
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
 * @version 2020-01-26
 */

// datos a utilizar
$url = getenv('LIBREDTE_API_URL');
$token = getenv('LIBREDTE_API_TOKEN');
$rut = getenv('LIBREDTE_EMPRESA_RUT');

// incluir autocarga de composer
require('../../../../vendor/autoload.php');

// crear cliente
$LibreDTE = new \sasco\LibreDTE\API\LibreDTE($token, $url);

// consultar situación
try {
    $info = $LibreDTE->consume('/sii/contribuyentes/situacion_tributaria/tercero/'.$rut)->getBodyDecoded();
} catch (\sasco\LibreDTE\API\Exception $e) {
    die('Error #'.$e->getCode().': '.$e->getMessage()."\n");
}

// mostrar datos
print_r($info);
