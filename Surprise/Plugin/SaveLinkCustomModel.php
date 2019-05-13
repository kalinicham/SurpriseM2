<?php
/**
 * Created by PhpStorm.
 * User: kalinich
 * Date: 5/11/19
 * Time: 10:29 AM
 */

namespace TSG\Surprise\Plugin;

use Magento\Setup\Exception;
use TSG\Surprise\Model\Product\LinkFactory;
use TSG\Surprise\Model\SurpriseStatFactory;

class SaveLinkCustomModel
{

    private $productLink;
    private $surpriseStat;

    public function __construct(
        LinkFactory $productLink,
        SurpriseStatFactory $statFactory
    )
    {
        $this->productLink = $productLink;
        $this->surpriseStat = $statFactory;
    }

    public function afterExecute($entityType, $entity)
    {

       $productLink = $this->productLink->create();
       $productLink->useSurpriseLinks();

      foreach ($productLink->getLinkCollection()->addLinkTypeIdFilter() as $item)
       {
           $surpriseStat = $this->surpriseStat->create();
            $surpriseStat->setEntityId($item->getId());
//            $surpriseStat->setLinkedProductId($item->getLinkedProductId());
//            $surpriseStat->setIdFieldName('entity_id');
            //$surpriseStat->setId('1');
           $a = 'stopline';
           try {

              // $surpriseStat->beforeSave();
             //  $surpriseStat->afterSave();
               $surpriseStat->save();

           } catch (Exception $exception) {
               echo $exception->getMessage();
           }


       }

    }

}