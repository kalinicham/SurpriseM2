<?php

namespace TSG\Surprise\Plugin;

class RepresentSurprise
{
    public function afterRepresentProduct($product, $result): bool
    {
        if ($result) {
            if ($product->getCustomPrice() !== null) {
               return false;
            }
        }
        return $result;
    }
}