<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE `icepay_issuerdata` MODIFY `issuer_minimum` TEXT NOT NULL; 
");
$installer->run("
ALTER TABLE `icepay_issuerdata` MODIFY `issuer_maximum` TEXT NOT NULL;
");


/**
 * Change columns
 */
$tables = array(
    $installer->getTable('icepay_transactions') => array(
        'columns' => array(
            'local_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'unsigned'  => true,
                'nullable'  => false,
                'comment'   => 'Local Transaction Id'
            )
        )
    )
);
$installer->getConnection()->modifyTables($tables);

$installer->endSetup();
