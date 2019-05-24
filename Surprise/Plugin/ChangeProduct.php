<?php

namespace TSG\Surprise\Plugin;

class ChangeProduct
{


   public function afterSetItem($result)
    {
        $item = $result->getItem();
            if ($item->getOrigData()['price'] == "0.0000") {
                $item->setCustomPrice(0);
                $item->setRowTotal(0);
           }
            $result->setData('item',$item);
        return $result;
    }






}