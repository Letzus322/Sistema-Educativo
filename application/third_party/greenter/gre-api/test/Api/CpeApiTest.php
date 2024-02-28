<?php
/**
 * CpeApiTest
 * PHP version 7.4
 *
 * @category Class
 * @package  Greenter\Sunat\GRE
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * SUNAT GRE API
 *
 * PLATAFORMA NUEVA GRE.
 *
 * The version of the OpenAPI document: 1.0.0
 * Contact: me@giansalex.dev
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 6.3.0-SNAPSHOT
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Please update the test case below to test the endpoint.
 */

namespace Greenter\Sunat\GRE\Test\Api;

use Greenter\Sunat\GRE\Api\AuthApi;
use Greenter\Sunat\GRE\Api\CpeApi;
use \Greenter\Sunat\GRE\Configuration;
use \Greenter\Sunat\GRE\ApiException;
use Greenter\Sunat\GRE\Model\CpeDocument;
use Greenter\Sunat\GRE\Model\CpeDocumentArchivo;
use \Greenter\Sunat\GRE\ObjectSerializer;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

/**
 * CpeApiTest
 *
 * @group manual
 */
class CpeApiTest extends TestCase
{
    /**
     * Test case for enviarCpe
     *
     * Permite realizar el envio del comprobante.
     *
     */
    public function testEnviarCpe()
    {
        $client = new Client();
        $apiInstance = new AuthApi($client);

        $result = $apiInstance->getToken(
            'password',
            'https://api-cpe.sunat.gob.pe',
            'xxxxx-xxx-xxxx-xxxx-xxxxxxxxx',
            'xxxxxxxxxxxxx',
            '20000000001MODDATOS',
            'moddatos');
        $token = $result->getAccessToken();

        $config = Configuration::getDefaultConfiguration()
            ->setAccessToken($token);

        $cpeApi = new CpeApi(
            $client,
            $config->setHost($config->getHostFromSettings(1))
        );

        $greZip = file_get_contents('gre.zip');
        $doc = (new CpeDocument())
            ->setArchivo((new CpeDocumentArchivo())
                ->setNomArchivo('20000000001-09-T001-1.zip')
                ->setArcGreZip(base64_encode($greZip))
                ->setHashZip(hash('sha256', $greZip))
            );
        $result = $cpeApi->enviarCpe('20000000001-09-T001-1', $doc);
        $ticket = $result->getNumTicket();

        $result = $cpeApi->consultarEnvio($ticket);

        switch ($result->getCodRespuesta()) {
            case '98': echo 'En proceso'.PHP_EOL; break;
            case '99': echo 'Proceso con error:'.PHP_EOL;
                if ($result->getError()) {
                    $err = $result->getError();
                    echo 'Code: '.$err->getNumError().' - Description: '.$err->getDesError().PHP_EOL;
                }
            case '0':
                if ($result->getIndCdrGenerado()) {
                    file_put_contents('cdr.zip', base64_decode($result->getArcCdr()));
                }

        }
    }
}