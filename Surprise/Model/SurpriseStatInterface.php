<?php
namespace TSG\Surprise\Model;
interface SurpriseStatInterface
{
    public function getProductId();

    public function getLinkedProductId();

    public function getCountSold();

    public function setProductId();

    public function setLinkedProductId();

    public function setCountSold();
}