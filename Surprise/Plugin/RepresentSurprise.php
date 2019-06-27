<?php

namespace TSG\Surprise\Plugin;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Serialize\Serializer\Json;

class RepresentSurprise
{
    /**
     * @var mixed
     */
    protected $serializer;

    /**
     * RepresentSurprise constructor.
     * @param Json|null $serializer
     */
    public function __construct(
        Json $serializer = null
    )
    {
        $this->serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
    }

    /**
     * @param $quoteItem \Magento\Quote\Model\Quote\Item
     * @param $result bool
     * @param $product \Magento\Catalog\Model\Product
     * @return bool
     */
    public function afterRepresentProduct($quoteItem, $result, $product): bool
    {
        if ($result) {

            foreach ($quoteItem->getOptionsByCode() as $key => $option)
            {
                if ($key = "info_buyRequest") {
                  $param = $this->serializer->unserialize($option->getValue());
                   if (array_key_exists('is_surprise',$param) && $param['is_surprise']) {

                       return false;
                   }
                }
            }

            foreach ($product->getCustomOptions() as $key => $option)
            {
                if ($key = "info_buyRequest") {
                    $param = $this->serializer->unserialize($option->getValue());
                    if (array_key_exists('is_surprise',$param) && $param['is_surprise']) {

                        return false;
                    }
                }
            }

        }



        return $result;
    }

    public function myFunction($resul): bool
    {

        return false;
    }

}