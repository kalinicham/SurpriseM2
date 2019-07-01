<?php

namespace TSG\Surprise\Test\Unit\Plugin;


use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Serialize\Serializer\Serialize;
use Magento\Framework\Serialize\Serializer\Json;
use \PHPUnit\Framework\TestCase;
use \TSG\Surprise\Plugin\RepresentSurprise;
use Magento\Catalog\Model\Product;
use Magento\Quote\Model\Quote\Item;
use Magento\Quote\Model\Quote\Item\Option;
use Magento\Sales\Setup\SerializedDataConverter;

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

        $this->serializerMock->expects($this->any())
            ->method('unserialize')
            ->will(
                $this->returnCallback(
                    function ($value) {
                        return json_decode($value, true);
                    }
                )
            );

        $this->repsentProduct = new RepresentSurprise(
            $this->serializerMock
        );

    }


    /**
     * @dataProvider additionProvider
     */
    public function testRepresentProduct($valueQuote, $result, $valueProduct, $expected)
    {


        $quoteItem = $this->quoteItem;

        $product = $this->product;

        $quoteItem->expects($this->any())->method('getOptionsByCode')->willReturn(array('info_buyRequest' => $this->option));

        $this->option->expects($this->any())->method('getValue')->willReturn($valueQuote);

        $this->assertEquals($expected, $this->repsentProduct->afterRepresentProduct($quoteItem,$result,$product));
    }

    public function additionProvider()
    {
        return [
            'result false'      =>  ['{}',false,'{}',false],
            'surprise quote' =>  ['{"is_surprise":"1"}',true,'{"4332":"d32"}',false],
            'surprise item' =>  ['{"12":"we"}',true,'{"is_surprise":"1"}',false],
            'not the surprise ' =>  ['{"e324":"324"}',true,'{"324":"dewd"}',true],
        ];
    }



}


