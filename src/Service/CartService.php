<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Request\AddCartItemRequest as AddCartItemRequestDto;
use App\DTO\Request\UpdateCartItemRequest as UpdateCartItemRequestDto;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\User;
use App\Repository\CartItemRepository;
use App\Repository\ProductRepository;
use LogicException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

final readonly class CartService
{
    public function __construct(
        private ProductRepository $productRepository,
        private CartItemRepository $cartItemRepository,
    ) {
    }

    public function getCart(User $user): Cart
    {
        return $user->getCart() ?? throw new LogicException('User has no cart.');
    }

    public function addItem(Cart $cart, AddCartItemRequestDto $request): Cart
    {
        $product = $this->productRepository->find($request->getProductId());

        if (null === $product) {
            throw new NotFoundHttpException('Product not found.');
        }

        $existingItem = null;
        foreach ($cart->getItems() as $item) {
            if ($item->getProduct()->getId()->equals($product->getId())) {
                $existingItem = $item;
                break;
            }
        }

        if ($existingItem instanceof CartItem) {
            $existingItem->setQuantity($existingItem->getQuantity() + $request->getQuantity());
            $this->cartItemRepository->save($existingItem);

            return $cart;
        }

        $cartItem = new CartItem($cart, $product, $request->getQuantity());
        $cart->getItems()->add($cartItem);
        $this->cartItemRepository->save($cartItem);

        return $cart;
    }

    /**
     * @throws NotFoundHttpException If the cart item is not found
     */
    public function updateItem(Cart $cart, string $itemId, UpdateCartItemRequestDto $request): Cart
    {
        $cartItem = $this->findItemInCart($cart, $itemId);
        $cartItem->setQuantity($request->getQuantity());
        $this->cartItemRepository->save($cartItem);

        return $cart;
    }

    /**
     * @throws NotFoundHttpException If the cart item is not found
     */
    public function removeItem(Cart $cart, string $itemId): void
    {
        $cartItem = $this->findItemInCart($cart, $itemId);

        $this->cartItemRepository->remove($cartItem);
    }

    /**
     * Find a specific cart item by its ID.
     *
     * Searches through the cart's items collection for a matching UUID.
     * Throws NotFoundHttpException if the item is not found.
     *
     * @param Cart   $cart   The cart to search in
     * @param string $itemId The UUID of the cart item to find
     *
     * @return CartItem The found cart item
     *
     * @throws NotFoundHttpException If the cart item is not found
     */
    private function findItemInCart(Cart $cart, string $itemId): CartItem
    {
        $uuid = Uuid::fromString($itemId);

        foreach ($cart->getItems() as $item) {
            if ($item->getId()->equals($uuid)) {
                return $item;
            }
        }

        throw new NotFoundHttpException('Cart item not found.');
    }
}
