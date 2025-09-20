<?php

namespace Raketa\BackendTestTask\Application\Http\Controller;

use Iterator;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\Stream;
use PHPUnit\Framework\TestCase;
use Raketa\BackendTestTask\Application\Http\View\ProductsView;
use Raketa\BackendTestTask\Domain\Model\Product;
use Raketa\BackendTestTask\Domain\Model\ProductCategory;
use Raketa\BackendTestTask\Domain\Repository\CategoryRepositoryInterface;
use Raketa\BackendTestTask\Domain\Repository\ProductRepositoryInterface;
use Ramsey\Uuid\Uuid;

/**
 * @covers GetProductsRestfullController
 */
class GetProductsRestfullControllerTest extends TestCase
{
    private GetProductsRestfullController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = new GetProductsRestfullController(
            new ProductsView($productRepository = $this->createMock(ProductRepositoryInterface::class)),
            $categoryRepository = $this->createMock(CategoryRepositoryInterface::class),
        );

        $category = new ProductCategory(1, 'electronics');
        $product = new Product(
            Uuid::fromString("41414243-4445-4646-2d31-3233342d3536"),
            true,
            $category->getName(),
            'Test product',
            null,
            'thumbnail.jpg',
            100.500,
        );

        $categoryRepository->expects($this->any())
            ->method('getByName')
            ->with($category->getName())
            ->willReturn($category);

        $productRepository
            ->expects($this->any())
            ->method('getByCategory')
            ->with($category)
            ->willReturn([$product]);
    }

    /**
     * @dataProvider dataProviderGetWithCategory
     */
    public function testGetWithCategory(?string $reqBody, int $expectedHttpStatus, string $expectedBody): void
    {
        $stream = Stream::create($reqBody);
        $request = new Request('GET', '/', body: $stream);

        $res = $this->controller->get($request);

        self::assertEquals($expectedHttpStatus, $res->getStatusCode());
        self::assertEquals('application/json; charset=utf-8', $res->getHeaderLine('Content-Type'));
        self::assertEquals($expectedBody, $res->getBody()->getContents());
    }

    public static function dataProviderGetWithCategory(): Iterator
    {
        yield 'unknown category' => ['', 404, ''];

        yield 'electronics category' => [json_encode(['category' => 'electronics']), 200, json_encode([[
            "uuid" => "41414243-4445-4646-2d31-3233342d3536",
            "category" => "electronics",
            "name" => "Test product",
            "description" => null,
            "thumbnail" => "thumbnail.jpg",
            "price" => 100.5,
        ]], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)];
    }

}
