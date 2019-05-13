<?php

namespace TSG\Surprise\Model\Product;

class Link extends \Magento\Catalog\Model\Product\Link
{

    const LINK_TYPE_SURPRISE = 7;

    public function useSurpriseLinks()
    {
        $this->setLinkTypeId(self::LINK_TYPE_SURPRISE);
        return $this;
    }

}