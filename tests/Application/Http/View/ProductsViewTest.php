<?php

namespace Raketa\BackendTestTask\Application\Http\View;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Raketa\BackendTestTask\Domain\Model\Product;
use Raketa\BackendTestTask\Domain\Repository\ProductRepositoryInterface;
use Ramsey\Uuid\Uuid;

class ProductsViewTest extends TestCase
{
    private ProductsView $productsView;
    private ProductRepositoryInterface&MockObject $productRepository;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->productsView = new ProductsView($this->productRepository);
    }

    public function testToArray(): void
    {
        $product = $this->mockProduct();

        $this->productRepository
            ->expects($this->once())
            ->method('getByCategory')
            ->with($categoryName = 'electronics')
            ->willReturn([$product]);

        $result = $this->productsView->toArray($categoryName);

        $this->assertCount(1, $result);
        $this->assertEquals([
            'uuid' => $product->getUuid()->toString(),
            'category' => 'electronics',
            'description' => 'Test product',
            'thumbnail' => 'thumbnail.jpg',
            'price' => 99.99,
        ], $result[0]);
    }

    private function mockProduct(): Product
    {
        $uuid = Uuid::uuid4();
        $product = $this->createMock(Product::class);
        $product->method('getUuid')->willReturn($uuid);
        $product->method('getCategory')->willReturn('electronics');
        $product->method('getDescription')->willReturn('Test product');
        $product->method('getThumbnail')->willReturn('thumbnail.jpg');
        $product->method('getPrice')->willReturn(99.99);

        return $product;
    }
}
