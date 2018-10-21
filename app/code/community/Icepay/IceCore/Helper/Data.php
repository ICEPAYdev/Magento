<?php

/**
 *  ICEPAY Core - Core Helper
 *  @version 1.0.0
 *  @author Olaf Abbenhuis
 *  @copyright ICEPAY <www.icepay.com>
 *  
 *  Disclaimer:
 *  The merchant is entitled to change de ICEPAY plug-in code,
 *  any changes will be at merchant's own risk.
 *  Requesting ICEPAY support for a modified plug-in will be
 *  charged in accordance with the standard ICEPAY tariffs.
 * 
 */
class Icepay_IceCore_Helper_Data extends Mage_Core_Helper_Abstract {
    /* Install values */

    public $title = "Core";
    public $version = "1.2.14";
    public $id = "003";
    public $fingerprint = "003";
    public $compatibility_oldest_version = "1.5.0.0";
    public $compatibility_latest_version = "1.9.3.10";
    public $section = "icecore";
    public $serial_required = "0";
    public $imageDir = "adminhtml/default/default/icepay/images/";
    public $imageExtension = "png";
    public $defaultLocale = "en_EN";

    public function isAdminPage()
    {
        return Mage::app()->getStore()->isAdmin();
    }

    public function hasSOAP()
    {
        return class_exists('SoapClient');
    }

    public function isCompatible($fromVersion, $toVersion)
    {
        $mVersion = Mage::getVersion();

        return ((version_compare($mVersion, $toVersion, '<=')) && version_compare($mVersion, $fromVersion, '>='));
    }

    public function makeArray($obj)
    {
        if (is_array($obj))
            return $obj;

        $arr = array();
        array_push($arr, $obj);
        return $arr;
    }

    public function isModuleInstalled($name)
    {
        $modules = Mage::getConfig()->getNode('modules')->children();
        $modulesArray = (array) $modules;

        return isset($modulesArray[$name]);
    }

    public function adminGetFrontScope()
    {
        /* default/websites/stores */
        return Mage::app()->getFrontController()->getRequest()->getParam('store');
    }

    /* note that this only works in default/stores scope
     * If a 0 is returned, it'll be default (admin) store scope ID
     */

    public function adminGetStoreScopeID()
    {
        if (Mage::app()->getStore($this->getStoreFromRequest())->isAdmin())
            return Mage::app()->getStore()->getId();
        
        return Mage::app()->getStore($this->getStoreFromRequest())->getId();
    }

    public function getStoreFromRequest()
    {
        return Mage::app()->getFrontController()->getRequest()->getParam('store');
    }

    public function adminGetStore()
    {
        return Mage::app()->getStore($this->adminGetStoreScopeID());
    }

    public function getStoreFrontURL($action = "result")
    {
        if (Mage::app()->getStore()->getStoreInUrl())
            return Mage::app()->getStore($this->adminGetStoreScopeID())->getUrl("icepay/processing/" . $action, array('_secure' => true));
        return Mage::app()->getStore()->getUrl("icepay/processing/" . $action, array('_secure' => true));
    }

    public function getStoreScopeID()
    {
        return (Mage::app()->getStore()->getId() == NULL || Mage::app()->getStore()->isAdmin()) ? $this->adminGetStoreScopeID() : Mage::app()->getStore()->getId();
    }

    public function getConfig($str)
    {
        return Mage::app()->getStore($this->getStoreScopeID())->getConfig($str);
    }

    public function getConfigForStore($storeID, $config)
    {
        return Mage::app()->getStore($storeID)->getConfig($config);
    }

    public function getMerchantIDForStore($storeID)
    {
        return Mage::app()->getStore($storeID)->getConfig(Icepay_IceCore_Model_Config::MERCHANTID);
    }

    public function getSecretcodeForStore($storeID)
    {
        return Mage::app()->getStore($storeID)->getConfig(Icepay_IceCore_Model_Config::SECRETCODE);
    }

    public function cleanArray($arr)
    {
        foreach ($arr as $key => $val)
            $arr[$key] = trim($val);
        return array_unique($arr);
    }

    public function setImageDir($str)
    {
        $this->imageDir = $str;
    }

    public function getLangISO2($default = false)
    {
        $locale = explode("_", ($default) ? $this->defaultLocale : Mage::app()->getLocale()->getLocale());
        if (is_array($locale) && !empty($locale)) {
            return strtoupper($locale[0]);
        }
        return "EN";
    }

    public function log($str)
    {
        Mage::log($str, null, 'icepay.log');
    }

    /* Checks */

    public function validateMerchantID($val)
    {
        $return = array('val' => false, 'msg' => $this->__("Merchant ID is properly configured"));

        if (!$val) {
            $return['msg'] = $this->__("Merchant ID not set for this storeview");
            return $return;
        }

        if (strlen($val) != 5) {
            $return['msg'] = $this->__("Merchant ID does not contain 5 digits");
            return $return;
        }

        if (!is_numeric($val)) {
            $return['msg'] = $this->__("Merchant ID is not numeric");
            return $return;
        }

        $return['val'] = true;
        return $return;
    }

    public function toIcon($code, $title = "")
    {
        $code = strtolower($code);
        if (!$img = $this->getImage("{$code}.{$this->imageExtension}"))
            return false;
        return "<img src=\"{$img}\" id=\"ICEPAY_{$code}\" title=\"{$title}\" >";
    }

    public function cleanString($string)
    {
        $string = trim($string);
        return $string;
    }

    public function getImage($img)
    {
        $skinDir = Mage::getModel('core/config')->getOptions()->getSkinDir();
        
        // Get image in lang folder
        if (file_exists($skinDir . '/' . $this->imageDir . strtolower($this->getLangISO2()) . "/" . $img))
            return $this->getImageURL() . strtolower($this->getLangISO2()) . "/" . $img;

        // Get image in standard lang folder
        if (file_exists($skinDir . '/' . $this->imageDir . strtolower($this->getLangISO2(true)) . "/" . $img))
            return $this->getImageURL() . strtolower($this->getLangISO2(true)) . "/" . $img;

        // Get standard image
        if (file_exists($skinDir . '/' . $this->imageDir . $img))
            return $this->getImageURL() . $img;

        //Hmm still no image, lets return the nologo image
        if (file_exists($skinDir . '/' . $this->imageDir . 'nologo.png'))
            return $this->getImageURL() . 'nologo.png';

        return false;
    }

    public function getImageURL()
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . $this->imageDir;
    }

    public function validateSecretCode($val)
    {
        $return = array('val' => false, 'msg' => $this->__("SecretCode is properly configured"));

        if (!$val) {
            $return['msg'] = $this->__("SecretCode not set for this storeview");
            return $return;
        }

        if (strlen($val) != 40) {
            $return['msg'] = $this->__("SecretCode does not contain 40 characters");
            return $return;
        }

        $return['val'] = true;

        return $return;
    }

    public function getTransactionDescription($default)
    {
        $data = Mage::getSingleton('checkout/session')->getData('icepay_checkout_session');
        if($data && isset($data['icepay_transaction_description']))
        {
            return $data['icepay_transaction_description'];
        }
        else
        {
            return ($statement = $this->getConfig(Icepay_IceCore_Model_Config::TRANSDESCR)) ? $statement : $default;
        }
    }

    public function formatTotal($number)
    {
        return round($number * 100);
    }

    public function generateChecksum($arr)
    {
        return sha1(implode("|", $arr));
    }

}