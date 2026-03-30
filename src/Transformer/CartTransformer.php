<?php

declare(strict_types=1);

namespace App\Transformer;

use App\DTO\Response\CartResponse as CartResponseDto;
use App\Entity\Cart;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class CartTransformer
{
    public function __construct(
        private CartItemTransformer $cartItemTransformer,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function toCartResponseDto(Cart $cart): CartResponseDto
    {
        $items = array_map(
            fn ($item) => $this->cartItemTransformer->toCartItemResponseDto($item),
            $cart->getItems()->toArray(),
        );

        $total = '0.00';
        foreach ($cart->getItems() as $item) {
            $total = bcadd($total, bcmul($item->getPrice(), (string) $item->getQuantity(), 2), 2);
        }

        return new CartResponseDto(
            id: (string) $cart->getId(),
            items: $items,
            total: $total,
            _links: [
                'self' => [
                    'href' => $this->urlGenerator->generate('app_cart_show'),
                    'method' => 'GET',
                ],
                'addItem' => [
                    'href' => $this->urlGenerator->generate('app_cart_additem'),
                    'method' => 'POST',
                ],
            ],
        );
    }
}
