<?php

namespace TSG\Surprise\Setup;

use \Magento\Framework\Setup\InstallSchemaInterface;
use \Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{

    public function install (SchemaSetupInterface $setup,ModuleContextInterface $context)
    {

        $installer = $setup;

        $installer->startSetup();

        $table = $installer->getConnection()->newTable(
            $installer->getTable('tsg_product_surprise')
        )->addColumn(
            'id',
            \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
            null,
            array(
                'identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,
            ),
            'Entity ID'
        )->addColumn(
            'product_id',
            \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
            null,
            array(
                'nullable' => false
            ),
            'Product Id'
        )->addColumn(
            'related_product_id',
            \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
            null,
            array(
                'nullable' => false
            ),
            'Related Product Id'
        );

        /*$table->addForeignKey();*/

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}