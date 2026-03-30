<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\DTO\Request\AddCartItemRequest as AddCartItemRequestDto;
use App\DTO\Request\UpdateCartItemRequest as UpdateCartItemRequestDto;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\CartItemRepository;
use App\Repository\ProductRepository;
use App\Service\CartService;
use LogicException;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionProperty;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

#[AllowMockObjectsWithoutExpectations]
final class CartServiceTest extends TestCase
{
    private ProductRepository&MockObject $productRepository;
    private CartItemRepository&MockObject $cartItemRepository;
    private CartService $cartService;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->cartItemRepository = $this->createMock(CartItemRepository::class);

        $this->cartService = new CartService(
            $this->productRepository,
            $this->cartItemRepository,
        );
    }

    public function testGetCartReturnsUserCart(): void
    {
        $user = new User();
        $cart = new Cart($user);
        $user->setCart($cart);

        $this->assertSame($cart, $this->cartService->getCart($user));
    }

    public function testGetCartThrowsWhenUserHasNoCart(): void
    {
        $user = new User();

        $this->expectException(LogicException::class);

        $this->cartService->getCart($user);
    }

    /**
     * @throws ReflectionException
     */
    public function testAddItemCreatesNewCartItem(): void
    {
        $product = $this->createProduct();
        $cart = $this->createCart();

        $request = new AddCartItemRequestDto();
        $request->productId = (string) $product->getId();
        $request->quantity = 2;

        $this->productRepository->method('find')->willReturn($product);
        $this->cartItemRepository->expects($this->once())->method('save');

        $result = $this->cartService->addItem($cart, $request);

        $this->assertCount(1, $result->getItems());
        $this->assertSame(2, $result->getItems()->first()->getQuantity());
    }

    /**
     * @throws ReflectionException
     */
    public function testAddItemIncreasesQuantityWhenProductAlreadyInCart(): void
    {
        $product = $this->createProduct();
        $cart = $this->createCart();

        $existingItem = new CartItem($cart, $product, 2);
        $cart->getItems()->add($existingItem);

        $request = new AddCartItemRequestDto();
        $request->productId = (string) $product->getId();
        $request->quantity = 3;

        $this->productRepository->method('find')->willReturn($product);
        $this->cartItemRepository->expects($this->once())->method('save');

        $this->cartService->addItem($cart, $request);

        $this->assertCount(1, $cart->getItems());
        $this->assertSame(5, $cart->getItems()->first()->getQuantity());
    }

    public function testAddItemThrowsWhenProductNotFound(): void
    {
        $cart = $this->createCart();

        $request = new AddCartItemRequestDto();
        $request->productId = (string) Uuid::v7();
        $request->quantity = 1;

        $this->productRepository->method('find')->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->cartService->addItem($cart, $request);
    }

    /**
     * @throws ReflectionException
     */
    public function testUpdateItemChangesQuantity(): void
    {
        $product = $this->createProduct();
        $cart = $this->createCart();

        $cartItem = new CartItem($cart, $product, 1);
        $itemId = Uuid::v7();
        $this->setId($cartItem, $itemId);
        $cart->getItems()->add($cartItem);

        $request = new UpdateCartItemRequestDto();
        $request->quantity = 5;

        $this->cartItemRepository->expects($this->once())->method('save');

        $this->cartService->updateItem($cart, (string) $itemId, $request);

        $this->assertSame(5, $cartItem->getQuantity());
    }

    public function testUpdateItemThrowsWhenItemNotFound(): void
    {
        $cart = $this->createCart();

        $request = new UpdateCartItemRequestDto();
        $request->quantity = 5;

        $this->expectException(NotFoundHttpException::class);

        $this->cartService->updateItem($cart, (string) Uuid::v7(), $request);
    }

    /**
     * @throws ReflectionException
     */
    public function testRemoveItemDeletesCartItem(): void
    {
        $product = $this->createProduct();
        $cart = $this->createCart();

        $cartItem = new CartItem($cart, $product, 1);
        $itemId = Uuid::v7();
        $this->setId($cartItem, $itemId);
        $cart->getItems()->add($cartItem);

        $this->cartItemRepository->expects($this->once())->method('remove');

        $this->cartService->removeItem($cart, (string) $itemId);
    }

    public function testRemoveItemThrowsWhenItemNotFound(): void
    {
        $cart = $this->createCart();

        $this->expectException(NotFoundHttpException::class);

        $this->cartService->removeItem($cart, (string) Uuid::v7());
    }

    private function createCart(): Cart
    {
        $user = new User();
        $cart = new Cart($user);
        $user->setCart($cart);

        return $cart;
    }

    /**
     * @throws ReflectionException
     */
    private function createProduct(): Product
    {
        $product = new Product();
        $product->setName('Test Product');
        $product->setArticleNumber('TEST-001');
        $product->setPrice('99.99');

        $this->setId($product, Uuid::v7());

        return $product;
    }

    /**
     * @throws ReflectionException
     */
    private function setId(object $entity, Uuid $id): void
    {
        $reflection = new ReflectionProperty($entity, 'id');
        $reflection->setValue($entity, $id);
    }
}
