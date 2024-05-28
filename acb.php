<?php
require_once __DIR__ . '/acb/option.php';
/**
 * WHMCS Sample Payment Gateway Module
 *
 * Payment Gateway modules allow you to integrate payment solutions with the
 * WHMCS platform.
 *
 * This sample file demonstrates how a payment gateway module for WHMCS should
 * be structured and all supported functionality it can contain.
 *
 * Within the module itself, all functions must be prefixed with the module
 * filename, followed by an underscore, and then the function name. For this
 * example file, the filename is "momowhmcspay" and therefore all functions
 * begin "momowhmcspay_".
 *
 * If your module or third party API does not support a given function, you
 * should not define that function within your module. Only the _config
 * function is required.
 *
 * For more information, please refer to the online documentation.
 *
 * @see https://developers.whmcs.com/payment-gateways/
 *
 * @copyright Copyright (c) WHMCS Limited 2017
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

/**
 * Define module related meta data.
 *
 * Values returned here are used to determine module related capabilities and
 * settings.
 *
 * @see https://developers.whmcs.com/payment-gateways/meta-data-params/
 *
 * @return array
 */
/*function momowhmcs_MetaData()
{
    return array(
        'DisplayName' => 'Ví điện tử MoMo (Cá nhân)',
        'APIVersion' => '1.1', // Use API Version 1.1
        'DisableLocalCredtCardInput' => true,
        'TokenisedStorage' => false,
    );
} */

/**
 * Define gateway configuration options.
 *
 * The fields you define here determine the configuration options that are
 * presented to administrator users when activating and configuring your
 * payment gateway module for use.
 *
 * Supported field types include:
 * * text
 * * password
 * * yesno
 * * dropdown
 * * radio
 * * textarea
 *
 * Examples of each field type and their possible configuration parameters are
 * provided in the sample function below.
 *
 * @return array
 */

/**
 * Payment link.
 *
 * Required by third party payment gateway modules only.
 *
 * Defines the HTML output displayed on an invoice. Typically consists of an
 * HTML form that will take the user to the payment gateway endpoint.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @see https://developers.whmcs.com/payment-gateways/third-party-gateway/
 *
 * @return string
 */
function acb_link($params)
{
    $invoiceId = $params['invoiceid'];
    $amount = $params['amount'];
    $systemUrl = $params['systemurl'];
    $returnUrl = $systemUrl . 'modules/gateways/acb/payacb.php';
    $langPayNow = $params['langpaynow'];
    $endurl = createUrlacb($returnUrl, $invoiceId, $amount);
    $htmlOutput = '<a href="' . $endurl . '"><input type="submit" value="' . $langPayNow . '" /></a>';

    $htmlOutput .= '</form>';

    return $htmlOutput;
}
function createUrlacb($returnUrl, $invoiceId, $amount)
{
    $arr_param = array(
        //	'return_url'		=>	strtolower(urlencode($returnUrl)),
        'orderId'        =>    strval($invoiceId),
        'amount'            =>    strval($amount),
    );
    $data =  "?&orderId=" . $arr_param['orderId'] . "&amount=" . $arr_param['amount'];
    $destinationUrl = $returnUrl . $data;
    $destinationUrl = str_replace("%3a", ":", $destinationUrl);
    $destinationUrl = str_replace("%2f", "/", $destinationUrl);
    return $destinationUrl;
}
