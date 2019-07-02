<?php

namespace TSG\Surprise\Test\Unit\Plugin;


use Magento\Framework\Serialize\Serializer\Json;
use \PHPUnit\Framework\TestCase;
use \TSG\Surprise\Plugin\RepresentSurprise;
use Magento\Catalog\Model\Product;
use Magento\Quote\Model\Quote\Item;
use Magento\Quote\Model\Quote\Item\Option;

class RepresentSurpriseTest extends TestCase
{


    private $product;

    private $quoteItem;

    private $serializerMock;

    private $jsonMock;

    private $option;

    private $repsentProduct;

    private $serializedDataConverter;

    public function setUp (): void
    {
        $this->quoteItem = $this->createMock(Item::class);

        $this->product = $this->createMock(Product::class);

        $this->option = $this->createMock(Option::class);

        $this->serializerMock = $this->createPartialMock(
            Json::class,
            ['serialize', 'unserialize']
        );

        $this->repsentProduct = new RepresentSurprise(
            $this->serializerMock
        );

    }


    /**
     * @dataProvider additionProvider
     */
    public function testRepresentProduct($OptionsByCode, $CustomOptions, $value, $result, $expected)
    {
        $this->quoteItem->expects($this->any())
            ->method('getOptionsByCode')
            ->willReturn(array($OptionsByCode => $this->option));
        $this->product->expects($this->any())
            ->method('getCustomOptions')
            ->willReturn(array($CustomOptions => $this->option));
        $this->option->expects($this->any())
            ->method('getValue')
            ->willReturn($value);

        $this->serializerMock->expects($this->any())
            ->method('unserialize')
            ->with($value)
            ->will(
                $this->returnCallback(
                    function ($value) {
                        return json_decode($value, true);
                    }
                )
            );

        $this->assertEquals($expected, $this->repsentProduct->afterRepresentProduct($this->quoteItem,$result,$this->product));
    }

    public function additionProvider()
    {
        return [
            'result false'      =>  ['qoute','product','{}',false,false],
            'surprise quote'    =>  ['info_buyRequest','product','{"is_surprise":"1"}',true,false],
            'surprise item'     =>  ['qoute','info_buyRequest','{"is_surprise":"1"}',true,false],
            'not surprise '     =>  ['info_buyRequest','info_buyRequest','{"product":"1"}',true,true],
        ];
    }



}


