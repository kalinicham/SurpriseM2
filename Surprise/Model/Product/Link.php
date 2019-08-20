<?php

namespace TSG\Surprise\Model\Product;

use Magento\Catalog\Model\Product\Link as ProductLink;

class Link extends ProductLink
{
    const LINK_TYPE_SURPRISE = 7;

    /**
     * @return
     */
    public function useSurpriseLinks()
    {
        $this->setLinkTypeId(self::LINK_TYPE_SURPRISE);
        return $this;
    }
}
