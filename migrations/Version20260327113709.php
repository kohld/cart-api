<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260327113709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_items ADD quantity INT NOT NULL');
        $this->addSql('ALTER TABLE cart_items ADD price NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE cart_items ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE cart_items ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE cart_items ADD product_id UUID NOT NULL');
        $this->addSql('ALTER TABLE cart_items ADD CONSTRAINT FK_BEF484454584665A FOREIGN KEY (product_id) REFERENCES products (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_BEF484454584665A ON cart_items (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_items DROP CONSTRAINT FK_BEF484454584665A');
        $this->addSql('DROP INDEX IDX_BEF484454584665A');
        $this->addSql('ALTER TABLE cart_items DROP quantity');
        $this->addSql('ALTER TABLE cart_items DROP price');
        $this->addSql('ALTER TABLE cart_items DROP created_at');
        $this->addSql('ALTER TABLE cart_items DROP updated_at');
        $this->addSql('ALTER TABLE cart_items DROP product_id');
    }
}
