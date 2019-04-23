<?php

/**
 *  ICEPAY Core - SQL methods
 *  @version 1.0.1
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
class Icepay_IceCore_Model_Mysql4_IceCore extends Mage_Core_Model_Mysql4_Abstract {

    protected $modules = null;

    public function _construct()
    {
        // Note that the basicgateway_id refers to the key field in the database table.
        $this->_init('icecore/icecore', 'icecore_id');
    }

    public function getModules()
    {
        $modules = array();

        $mod_namespace = $this->getModuleData("title");

        foreach ($mod_namespace as $module) {
            try {
                $class = sprintf("Icepay_Ice%s_Helper_Data", $module["value"]);
                if (class_exists($class)) {
                    $helper = new $class();
                    array_push($modules, array(
                        'id' => $helper->id,
                        'name' => $helper->title,
                        'compatible' => sprintf("Magento %s - %s", $helper->compatibility_oldest_version, $helper->compatibility_latest_version),
                        'compatibleFrom' => $helper->compatibility_oldest_version,
                        'compatibleTo' => $helper->compatibility_latest_version,
                        'active' => $this->getModuleConfiguration("active", $helper->section),
                        'serial' => $this->getModuleConfiguration("serial", $helper->section),
                        'serialreq' => $helper->serial_required,
                        'version' => $helper->version,
                        'fingerprint' => $helper->fingerprint,
                        'namespace' => $helper->section
                    ));
                }
            } catch (Exception $e) {
                Mage::helper("icecore")->log($e->getMessage());
            };
        }
        return $modules;
    }

    public function isActive($namespace)
    {
        return ($this->getModuleConfiguration("active", $namespace) == "1");
    }

    public function getModulesConfiguration()
    {
        $moduleData = $this->getModules();

        for ($i = 0; $i < count($moduleData); $i++) {
            $moduleData[$i]["active"] = $this->getModuleConfiguration("active", $moduleData[$i]["namespace"]);
            $moduleData[$i]["serial"] = $this->getModuleConfiguration("serial", $moduleData[$i]["namespace"]);
        };

        return $moduleData;
    }

    public function getModuleData($unique = "title")
    {
        $conn = $this->_getReadAdapter();
        $select = $conn
                ->select()
                ->from($this->getTable('core/config_data'), array('scope', 'scope_id', 'path', 'value'))
                ->where(new Zend_Db_Expr("path LIKE 'iceadvanced/module/" . $unique . "'"))
                ->order('path');
        $data = $conn->fetchAll($select);

        return $data;
    }

    public function getModuleConfiguration($config = "active", $namespace = "icecore")
    {
        $conn = $this->_getReadAdapter();
        $select = $conn
                ->select()
                ->from($this->getTable('core/config_data'), array('scope', 'scope_id', 'path', 'value'))
                ->where(new Zend_Db_Expr("path = 'icecore/" . $namespace . "/" . $config . "'"));
        $data = $conn->fetchRow($select);
        return $data["value"];
    }

    protected function getAuthID()
    {
        if ($this->modules == null)
            $this->modules = $this->getModules();
        $str = "";
        foreach ($this->modules as $module) {
            if ($module["serialreq"] == "1" && $module["active"] == "1")
                $str.= $module["id"];
        }

        return $str;
    }

    public function getAuthKey($storeID = 0)
    {
        if ($this->modules == null)
            $this->modules = $this->getModules();
        $arr = array();
        foreach ($this->modules as $module) {
            if ($module["serialreq"] == "1" && $module["active"] == "1") {
                array_push($arr, sprintf("[%s,%s,%s]", trim($module["id"]), trim($module["fingerprint"]), trim($module["serial"])));
            }
        }
        $str = sprintf("%s,[%s]", Mage::helper('icecore')->getMerchantIDForStore($storeID), implode(",", $arr));
        return sha1($str);
    }

    /* @sales_order_payment_place_end event */

    public function savePayment(Varien_Event_Observer $observer)
    {
        $payment = $observer->getPayment();
        $order = $payment->getOrder();
        $pmName = $payment->getMethodInstance()->getCode();

        $param = Mage::app()->getFrontController()->getRequest()->getParam('payment');        
       
        $paymentMethod = (isset($param[$pmName . '_paymentmethod'])) ? $param[$pmName . '_paymentmethod'] : $param['method'];
        $issuer = isset($param[$pmName . '_issuer']) ? $param[$pmName . '_issuer'] : '0';
        
        $country = (isset($param[$pmName . '_country'])) ? $param[$pmName . '_country'] : $order->getBillingAddress()->getCountryId();
        if ($country == "00")
            $country = $order->getBillingAddress()->getCountryId();


        if( Mage::getStoreConfig(Icepay_IceCore_Model_Config::USEBASECURRENCY, $order->getStore()->getId()))
        {
            $amount = $order->getBaseGrandTotal();
            $currency = $order->getBaseCurrencyCode();
        }
        else
        {
            $amount = $order->getGrandTotal();
            $currency = $order->getOrderCurrencyCode();
        }


        $ice_payment = array(
            'ic_merchantid' => Mage::helper('icecore')->getMerchantIDForStore($order->getStore()->getId()),
            'ic_currency' => $currency,
            'ic_amount' => Mage::helper('icecore')->formatTotal($amount),
            'ic_description' => Mage::helper('icecore')->getTransactionDescription($order->getRealOrderId()),
            'ic_country' => $country,
            'ic_language' => Mage::helper("icecore")->getLangISO2(),
            'ic_reference' => $order->getRealOrderId(),
            'ic_paymentmethod' => $paymentMethod,
            'ic_issuer' => $issuer,
            'ic_orderid' => $order->getRealOrderId(),
            'ic_moduleid' => $this->getAuthID(),
            'ic_authkey' => $this->getAuthKey($order->getStore()->getId())
        );
        
        $data = array(
            'order_id' => $order->getRealOrderId(),
            'model' => $pmName,
            'transaction_data' => urlencode(serialize($ice_payment)),
            'store_id' => $order->getStore()->getId(),
            'status' => Icepay_IceCore_Model_Config::STATUS_NEW,
            'update_time' => now(),
            'creation_time' => now()
        );

        $this->_getWriteAdapter()->insert($this->getTable('icepay_transactions'), $data);
    }

    public function loadPaymentByID($id)
    {
        $conn = $this->_getReadAdapter();
        $select = $conn
                ->select()
                ->from($this->getTable('icepay_transactions'), array('transaction_data', 'transaction_id', 'status', 'store_id', 'model', 'order_id'))
                ->where(new Zend_Db_Expr("order_id = '" . $id . "'"));
        $data = $conn->fetchRow($select);
        return $data;
    }

    public function changeStatus(array $data)
    {
        $where = $this->_getReadAdapter()->quoteInto('order_id = ?', $data["order_id"]);
        $this->_getWriteAdapter()->update(
                $this->getTable('icepay_transactions'), $data, $where);
    }

}