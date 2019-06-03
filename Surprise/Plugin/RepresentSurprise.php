<?php

namespace TSG\Surprise\Plugin;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Serialize\Serializer\Json;

class RepresentSurprise
{

    protected $serializer;

    public function __construct(
        Json $serializer = null
    )
    {
        $this->serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
    }


    public function afterRepresentProduct($product, $result): bool
    {
        if ($result) {
            foreach ($product->getOptionsByCode() as $key => $option)
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
}