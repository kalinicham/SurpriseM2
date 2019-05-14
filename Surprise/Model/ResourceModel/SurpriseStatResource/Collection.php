<?php

namespace TSG\Surprise\Model\ResourceModel\SurpriseStatResource;

use Magento\Framework\Data\SearchResultInterface;
use TSG\Surprise\Model\ResourceModel\SurpriseStat\Collection as ModelCollection;

class Collection extends ModelCollection implements SearchResultInterface
{

    /**
     * Retrieve count of currently loaded items
     *
     * @return int
     */
    public function getTotalCount()
    {
        // TODO: Implement getTotalCount() method.
    }

    /**
     * @return \Magento\Framework\Api\CriteriaInterface
     */
    public function getSearchCriteria()
    {
        // TODO: Implement getSearchCriteria() method.
    }
}