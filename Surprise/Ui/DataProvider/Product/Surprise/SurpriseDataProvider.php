<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace TSG\Surprise\Ui\DataProvider\Product\Surprise;

use Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider;

/**
 * Class CrossSellDataProvider
 *
 * @api
 * @since 101.0.0
 */
class SurpriseDataProvider extends ProductDataProvider
{

    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
//        $filter->setConditionType('eq');
//        $filter->setField('is_surprise');
//        $filter->setValue('1');
//        parent::addFilter($filter);

        //$this->getRequest()->getParam('id');

        $this->getCollection()
            ->addFieldToFilter('is_surprise',['eq' => '1'])
            ->addFieldToFilter('entity_id',['nin' => '10']);
    }
}
