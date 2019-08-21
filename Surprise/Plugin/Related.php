<?php

namespace TSG\Surprise\Plugin;

class Related
{
    public function beforeGetLinkedProducts($provider, $product)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->product = $objectManager->create(\TSG\Surprise\Model\Product::class);
        $currentProduct = $this->product->load($product->getId());
        return [$currentProduct];
    }
}
