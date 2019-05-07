<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace TSG\Surprise\Ui\DataProvider\Product\Surprise;

use Magento\Catalog\Ui\DataProvider\Product\Related\AbstractDataProvider;

class SurpriseDataProvider extends AbstractDataProvider
{

    protected function getLinkType()
    {
      return 'suprise';   // TODO: Implement getLinkType() method.
    }

    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        $this->getCollection()
            ->addFieldToFilter('is_surprise',array('eq'=>'1'));

        parent::addFilter($filter);
    }


}
