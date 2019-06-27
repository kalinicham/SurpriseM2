<?php

namespace TSG\Surprise\Test\Unit\Plugin;

use Magento\Framework\Serialize\Serializer\Serialize;
use \PHPUnit\Framework\TestCase;
use \TSG\Surprise\Plugin\RepresentSurprise;
use Magento\Catalog\Model\Product;
use Magento\Quote\Model\Quote\Item;
use Magento\Quote\Model\Quote\Item\Option;


class RepresentSurpriseTest extends TestCase
{


    private $product;

    private $repsentProduct;

    private $quoteItem;

    private $serializerMock;

    private $option;

    public function setUp (): void
    {
        $this->quoteItem = $this->createMock(Item::class);

        $this->product = $this->createMock(Product::class);

        $this->option = $this->createMock(Option::class);

        $this->repsentProduct = new RepresentSurprise();
    }


    /**
     * @dataProvider additionProvider
     */
    public function testRepresentProduct($valueQuote, $result, $valueProduct, $expected)
    {


        $quoteItem = $this->quoteItem;


        $product = $this->product;


        $quoteItem->expects($this->any())->method('getOptionsByCode')->willReturn(
            array('info_buyRequest' => $this->option
            )
        );

        $this->option->expects($this->any())->method('getValue')->willReturn('');

        $this->assertEquals($expected, $this->repsentProduct->afterRepresentProduct($quoteItem,$result,$product));
    }

    public function additionProvider()
    {
        return [
            'result false'      =>  ['',false,'',false],
            'not the surprise ' =>  ['',true,'',true],
        ];
    }



}


