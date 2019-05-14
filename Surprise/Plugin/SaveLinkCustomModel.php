<?php

namespace TSG\Surprise\Plugin;

//use Magento\Framework\ObjectManagerInterface;
use TSG\Surprise\Model\Product\LinkFactory;
use TSG\Surprise\Model\ResourceModel\SurpriseStatResourceFactory;
use TSG\Surprise\Model\SurpriseStatFactory;

class SaveLinkCustomModel
{


    private $productLink;
    private $surpriseStat;
    private $statResource;


    public function __construct(
        SurpriseStatResourceFactory $statResource,
        SurpriseStatFactory $surpriseStat,
        LinkFactory $productLink
    )
    {
        $this->statResource = $statResource;
        $this->surpriseStat = $surpriseStat;
        $this->productLink = $productLink;
    }


    public function afterExecute($entityType, $entity)
    {

        $productLink = $this->productLink->create();
        $productLink->useSurpriseLinks();

        foreach ($productLink->getLinkCollection()->addLinkTypeIdFilter() as $item){
            $surpriseStat = $this->surpriseStat->create();
            $statResource = $this->statResource->create();

            $id = $surpriseStat->getCollection()->addFieldToSelect('entity_id')
                ->addFieldToFilter( 'product_id', array( 'eq' => $item->getProductId()))
                ->addFieldToFilter('linked_product_id', array( 'eq' => $item->getLinkedProductId())
                )->getLastItem()->getData('entity_id');


            if ($id == null) {
                $surpriseStat->setProductId($item->getProductId());
                $surpriseStat->setLinkedProductId($item->getLinkedProductId());
                $statResource->save($surpriseStat);
            }

        }
    }
}