<?php

namespace TSG\Surprise\Model\ProductLink\CollectionProvider;

class Surprise
{

    public function getLinkedProducts(\Magento\Catalog\Model\Product $product)
    {
        return $product->getSurpriseProduct();
    }


}