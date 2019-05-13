<?php

namespace TSG\Surprise\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class SurpriseStatResource extends AbstractDb
{

    protected function _construct()
    {
        $this->_init('tsg_product_surprise', 'id');
    }

}