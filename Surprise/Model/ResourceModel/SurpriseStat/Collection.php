<?php

namespace TSG\Surprise\Model\ResourceModel\SurpriseStat;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use TSG\Surprise\Model\SurpriseStat;
use TSG\Surprise\Model\ResourceModel\SurpriseStatResource;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(SurpriseStat::class, SurpriseStatResource::class);
    }

}