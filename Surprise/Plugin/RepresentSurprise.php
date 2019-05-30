<?php

namespace TSG\Surprise\Plugin;

class RepresentSurprise
{
    public function afterRepresentProduct($result)
    {
        if ($result->getCustomPrice() === null) {
           return false;
        }
    }
}