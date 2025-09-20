<?php

namespace Raketa\BackendTestTask\Application\Http\View;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Raketa\BackendTestTask\Domain\Model\Product;
use Raketa\BackendTestTask\Domain\Model\ProductCategory;
use Raketa\BackendTestTask\Domain\Repository\ProductRepositoryInterface;
use Ramsey\Uuid\Lazy\LazyUuidFromString;

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
        $category = new  ProductCategory(1, 'electronics');
        $product = new Product(
            LazyUuidFromString::fromBytes('AABCDEFF-1234-5678-90AB-CDEF12345678'),
            true,
            $category->getName(),
            'Test product',
            null,
            'thumbnail.jpg',
            100.500,
        );

        $this->productRepository
            ->expects($this->once())
            ->method('getByCategory')
            ->with($category)
            ->willReturn([$product]);

        $result = $this->productsView->toArray($category);

        $this->assertCount(1, $result);
        $this->assertEquals([
            'uuid' => $product->getUuid()->toString(),
            'category' => $category->getName(),
            'description' => null,
            'thumbnail' => 'thumbnail.jpg',
            'price' => 100.5,
        ], $result[0]);
    }
}
