<?php

namespace TSG\Surprise\Test\Unit\Plugin;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\DataObject\Factory as ObjectFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item;
use PHPUnit\Framework\TestCase;
use \TSG\Surprise\Plugin\AddSurprise;


class AddSurpriseTest extends TestCase
{
    private $requestMock;

    private $productRepositoryMock;

    private $objectFactoryMock;

    private $addSurpirse;

    private $objectManagerHelper;

    private $resultMock;

    public function setUp(): void
    {

        $this->requestMock = $this->createMock(Http::class);

        $this->productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);

        $this->objectFactoryMock = $this->createMock(ObjectFactory::class);

        $this->resultMock = $this->createMock(Cart::class);

        $this->objectManagerHelper = new ObjectManagerHelper($this);

        $this->addSurpirse = $this->objectManagerHelper->getObject(
            AddSurprise::class,
            [
                'request' => $this->requestMock,
                'productRepository' => $this->productRepositoryMock,
                'objectFactory' => $this->objectFactoryMock
            ]

        );


    }


    public function testAddSurprise()
    {

        $param = array(
            'surprise_product' => 10
        );

        $arrayData = [
            'qty' => 1,
            'price' => 0,
            'is_surprise' => 1,
        ];

        $requestSurMock = $this->createMock(Http::class);

        $productMock = $this->createMock(ProductInterface::class);

        $itemMock = $this->createMock(Item::class);

        $quoteMock = $this->createMock(Quote::class);

        $this->requestMock->expects($this->once())
            ->method('getParams')
            ->willReturn($param);

        $this->productRepositoryMock->expects($this->once())
            ->method('getById')
            ->with($param['surprise_product'])
            ->willReturn($productMock);

        $this->objectFactoryMock->expects($this->once())
            ->method('create')
            ->with($arrayData)
            ->willReturn($requestSurMock);

        $this->resultMock->expects($this->once())
            ->method('getQuote')
            ->willReturn($itemMock);

        $quoteMock->expects($this->once())
            ->method('addProduct')
            ->with($param['surprise_product'],$this->requestMock,'option_')
            ->willReturn('sdasdasd');

        $this->addSurpirse->afterAddProduct($this->resultMock);




    }

}