<?php

namespace TSG\Surprise\Plugin;

use TSG\Surprise\Model\Product\LinkFactory;
use TSG\Surprise\Model\ResourceModel\SurpriseStatResourceFactory;
use TSG\Surprise\Model\SurpriseStatFactory;
use TSG\Surprise\Model\ResourceModel\SurpriseStat\CollectionFactory;

class SaveLinkCustomModel
{

    private $productLink;
    private $surpriseStat;
    private $statResource;
    private $collectionFactory;

    public function __construct(
        CollectionFactory $collectionFactory,
        SurpriseStatResourceFactory $statResource,
        SurpriseStatFactory $surpriseStat,
        LinkFactory $productLink
     )
    {
        $this->collectionFactory = $collectionFactory;
        $this->statResource = $statResource;
        $this->surpriseStat = $surpriseStat;
        $this->productLink = $productLink;
    }

    public function afterExecute()
    {

        $productLink = $this->productLink->create()->useSurpriseLinks();

        foreach ($productLink->getLinkCollection()->addLinkTypeIdFilter() as $item){

            $collectionFactory = $this->collectionFactory->create()
                ->addFilter('product_id', $item->getProductId())
                ->addFilter('linked_product_id', $item->getLinkedProductId());

            if ($collectionFactory->count() == 0) {
                $statResource = $this->statResource->create();
                    $surpriseStat = $this->surpriseStat->create();
                    $surpriseStat->setProductId($item->getProductId());
                    $surpriseStat->setLinkedProductId($item->getLinkedProductId());
                $statResource->save($surpriseStat);
            }

        }
    }
}