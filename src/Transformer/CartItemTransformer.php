<?php

declare(strict_types=1);

namespace App\Transformer;

use App\DTO\Response\CartItemResponse as CartItemResponseDto;
use App\DTO\Response\ProductResponse as ProductResponseDto;
use App\Entity\CartItem;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class CartItemTransformer
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function toCartItemResponseDto(CartItem $cartItem): CartItemResponseDto
    {
        $product = $cartItem->getProduct();
        $id = (string) $cartItem->getId();

        return new CartItemResponseDto(
            id: $id,
            product: new ProductResponseDto(
                id: (string) $product->getId(),
                articleNumber: $product->getArticleNumber(),
                name: $product->getName(),
                price: $product->getPrice(),
            ),
            quantity: $cartItem->getQuantity(),
            price: $cartItem->getPrice(),
            _links: [
                'update' => [
                    'href' => $this->urlGenerator->generate(
                        'app_cart_updateitem',
                        ['id' => $id]),
                    'method' => 'PATCH',
                ],
                'delete' => [
                    'href' => $this->urlGenerator->generate('app_cart_removeitem',
                        ['id' => $id]),
                    'method' => 'DELETE',
                ],
            ],
        );
    }
}
