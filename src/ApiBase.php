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

namespace apigatewaycl\api_client;

/**
 * Clase base para las clases que consumen la API (wrappers).
 */
class ApiBase extends ApiClient
{
    /**
     * Arreglo que contendrá el diccionario de autenticación.
     *
     * @var array
     */
    protected $auth = [];

    /**
     * Constructor de la clase base.
     *
     * @param array $credenciales Argumentos adicionales para la autenticación.
     * @param string|null $token Token de autenticación para la API.
     * @param string|null $url URL base para la API.
     */
    public function __construct(
        array $credenciales = [],
        string $token = null,
        string $url = null
    ) {
        parent::__construct($token, $url);
        $this->setupAuth($credenciales);
    }

    /**
     * Configura la autenticación específica para la aplicación.
     *
     * @param array $credenciales Parámetros de autenticación. Puede ser
     * 'pass' o 'cert'.
     * @throws \apigatewaycl\api_client\ApiException
     * @return void
     */
    private function setupAuth(array $credenciales): void
    {
        $tipo = key($credenciales); // Detecta si es 'pass' o 'cert'
        $datos = $credenciales[$tipo] ?? [];

        $identificador = $datos['rut'] ??
        $datos['cert-data'] ??
        $datos['file-data'] ??
        null;
        $clave = $datos['clave'] ??
        $datos['pkey-data'] ??
        $datos['file-pass'] ??
        null;

        if ($identificador && $clave) {
            if ($this->isAuthPass($identificador)) {
                $this->auth = [
                    'pass' => [
                        'rut' => $identificador,
                        'clave' => $clave,
                    ],
                ];
            } elseif ($this->isAuthCertData($identificador)) {
                $this->auth = [
                    'cert' => [
                        'cert-data' => $identificador,
                        'pkey-data' => $clave,
                    ],
                ];
            } elseif ($this->isAuthFileData($identificador)) {
                $this->auth = [
                    'cert' => [
                        'file-data' => $identificador,
                        'file-pass' => $clave,
                    ],
                ];
            } else {
                throw new ApiException(
                    message: 'No se han proporcionado las credenciales de autentificación.'
                );
            }
        }
    }

    /**
     * Valida la estructura de un RUT chileno utilizando una expresión regular.
     *
     * Este método verifica que el RUT cumpla con el formato estándar chileno, que incluye
     * puntos como separadores de miles opcionales y un guion antes del dígito verificador.
     * El dígito verificador puede ser un número o la letra 'K'.
     *
     * **Ejemplos de RUT válidos:**
     *
     *     - 12.345.678-5
     *     - 12345678-5
     *     - 9.876.543-K
     *     - 9876543-K
     *
     * **Ejemplos de RUT inválidos:**
     *
     *     - 12.345.678-9 (dígito verificador incorrecto)
     *     - 12345678- (falta dígito verificador)
     *     - 12345-6 (formato incorrecto)
     *     - 12.345.6785 (falta guion)
     *     - abcdefgh-i (caracteres no permitidos)
     *
     * @param string $rut El RUT a validar.
     * @return bool true si el RUT tiene un formato válido,false en caso
     * contrario.
     */
    private function isAuthPass(string $rut): bool
    {
        if (is_null($rut)) {
            return false;
        }
        // Expresión regular para validar el formato del RUT chileno
        $pattern = '/^(\d{1,3}\.?)(\d{3}\.?)(\d{3,4})-([\dkK])$/';
        return preg_match($pattern, $rut) === 1;
    }

    /**
     * Verifica si una cadena es una cadena codificada en Base64 válida.
     *
     * @param string $firmaElectronicaBase64 La cadena a verificar.
     * @return bool true si la cadena es válida en Base64, false en caso
     * contrario.
     */
    private function isAuthFileData(string $firmaElectronicaBase64): bool
    {
        if (is_null($firmaElectronicaBase64)) {
            return false;
        }
        // Asegúrate de que la longitud de la cadena sea múltiplo de 4
        if (strlen($firmaElectronicaBase64) % 4 !== 0) {
            return false;
        }
        // Validar Base64
        return base64_decode(
            string: $firmaElectronicaBase64,
            strict: true
        ) !== false;
    }

    /**
     * Valida si una cadena tiene formato PEM válido.
     *
     * El formato PEM debe cumplir con los siguientes criterios:
     *
     *      - Comienza con una línea "-----BEGIN [LABEL]-----"
     *      - Termina con una línea "-----END [LABEL]-----"
     *      - Contiene contenido Base64 válido entre las líneas BEGIN y END
     *
     * **Ejemplos de PEM Válidos:**
     *
     *      ```
     *      -----BEGIN CERTIFICATE-----
     *      MIIDdzCCAl+gAwIBAgIEbGzVnzANBgkqhkiG9w0BAQsFADBvMQswCQYDVQQGEwJV
     *      ...
     *      -----END CERTIFICATE-----
     *      ```
     *
     * **Ejemplos de PEM Inválidos:**
     *
     *      - Falta la línea de inicio o fin.
     *      - Contenido no codificado en Base64.
     *      - Etiquetas de BEGIN y END que no coinciden.
     *
     * @param string $pemStr La cadena a validar.
     * @return bool true si la cadena tiene formato PEM válido, false en caso contrario.
     */
    private function isAuthCertData(string $pemStr): bool
    {
        if (is_null($pemStr)) {
            return false;
        }
        // Expresión regular para validar el formato PEM
        $pattern = '/-----BEGIN ([A-Z ]+)-----\s+([A-Za-z0-9+\/=\s]+)-----END \1-----$/m';
        if (!preg_match(
            $pattern,
            trim($pemStr),
            $matches
        )
        ) {
            return false;
        }
        // Validar contenido Base64
        $base64Content = preg_replace(
            '/\s+/',
            '',
            $matches[2]
        );
        return base64_decode($base64Content, true) !== false;
    }

    /**
     * Obtiene la autenticación de tipo 'pass'.
     *
     * @throws \apigatewaycl\api_client\ApiException Si falta información
     * de autenticación.
     * @return array Información de autenticación.
     */
    protected function getAuthPass(): array
    {
        if (isset($this->auth['pass'])) {
            if (empty($this->auth['pass']['rut'])) {
                throw new ApiException(message: 'auth.pass.rut empty.');
            }
            if (empty($this->auth['pass']['clave'])) {
                throw new ApiException(message: 'auth.pass.clave empty.');
            }
        } elseif (isset($this->auth['cert'])) {
            if (
                isset($this->auth['cert']['cert-data']) &&
                empty($this->auth['cert']['cert-data'])
            ) {
                throw new ApiException(message: 'auth.cert.cert-data empty.');
            }
            if (
                isset($this->auth['cert']['pkey-data']) &&
                empty($this->auth['cert']['pkey-data'])
            ) {
                throw new ApiException(message: 'auth.cert.pkey-data empty.');
            }
            if (
                isset($this->auth['cert']['file-data']) &&
                empty($this->auth['cert']['file-data'])
            ) {
                throw new ApiException(message: 'auth.cert.file-data empty.');
            }
            if (
                isset($this->auth['cert']['file-pass']) &&
                empty($this->auth['cert']['file-pass'])
            ) {
                throw new ApiException(message: 'auth.cert.file-pass empty.');
            }
        } else {
            throw new ApiException(message: 'auth.pass or auth.cert missing.');
        }

        return $this->auth;
    }
}
