<?php

/**
 *  ICEPAY Core - Configuration constants
 * @author Olaf Abbenhuis
 * @copyright ICEPAY <www.icepay.com>
 *
 *  Disclaimer:
 *  The merchant is entitled to change de ICEPAY plug-in code,
 *  any changes will be at merchant's own risk.
 *  Requesting ICEPAY support for a modified plug-in will be
 *  charged in accordance with the standard ICEPAY tariffs.
 *
 */
class Icepay_IceCore_Model_Config
{
    /* Config constants */

    const MERCHANTID = "icecore/settings/merchant_id";
    const SECRETCODE = "icecore/settings/merchant_secret";
    const TRANSDESCR = "icecore/core_options/transaction_descr";
    const AUTOINVOICE = "icecore/core_options/order_autoinvoice";
    const AUTOREFUND = "icecore/core_options/order_autorefund";
    const OVERRIDEREDIRECTURLS 	= "icecore/core_options/override_redirect_urls";
    const USEBASECURRENCY 	= "icecore/core_options/use_base_currency";

    const STATUS_NEW = "NEW";
    const STATUS_OPEN = "OPEN";
    const STATUS_ERROR = "ERR";
    const STATUS_SUCCESS = "OK";
    const STATUS_REFUND = "REFUND";
    const STATUS_CHARGEBACK = "CBACK";
    const STATUS_AUTH = "AUTHORIZED";
    const STATUS_VERSION_CHECK = "VCHECK";

    const STATUS_MAGENTO_NEW = "icecore_new";
    const STATUS_MAGENTO_OPEN = "icecore_open";
    const STATUS_MAGENTO_ERROR = "icecore_err";
    const STATUS_MAGENTO_SUCCESS = "icecore_ok";
    const STATUS_MAGENTO_REFUND = "icecore_refund";
    const STATUS_MAGENTO_CHARGEBACK = "icecore_cback";
    const STATUS_MAGENTO_AUTHORIZED = "icecore_open";


}
