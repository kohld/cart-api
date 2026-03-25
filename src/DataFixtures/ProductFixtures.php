<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Product fixtures.
 */
class ProductFixtures extends Fixture
{
    private const array PRODUCTS = [
        [
            'articleNumber' => 'REF-001',
            'name' => 'Apple iPhone 14 Pro — Refurbished',
            'description' => 'iPhone 14 Pro 256GB Space Black, grade A refurbished. Battery health min. 85%.',
            'price' => '649.99',
        ],
        [
            'articleNumber' => 'REF-002',
            'name' => 'Apple iPhone 13 — Refurbished',
            'description' => 'iPhone 13 128GB Midnight, grade B refurbished. Minor cosmetic signs of use.',
            'price' => '429.99',
        ],
        [
            'articleNumber' => 'REF-003',
            'name' => 'Sony Alpha A7 III — Refurbished',
            'description' => 'Full-frame mirrorless camera body, grade A refurbished. Shutter count under 5000.',
            'price' => '1299.99',
        ],
        [
            'articleNumber' => 'REF-004',
            'name' => 'Canon EOS R50 — Refurbished',
            'description' => 'Compact mirrorless camera body, grade A refurbished. Ideal for beginners.',
            'price' => '549.99',
        ],
        [
            'articleNumber' => 'REF-005',
            'name' => 'Apple MacBook Pro 14" M2 — Refurbished',
            'description' => 'MacBook Pro 14" M2 Pro, 16GB RAM, 512GB SSD, grade A refurbished.',
            'price' => '1599.99',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PRODUCTS as $data) {
            $product = new Product();
            $product->setArticleNumber($data['articleNumber']);
            $product->setName($data['name']);
            $product->setDescription($data['description']);
            $product->setPrice($data['price']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
