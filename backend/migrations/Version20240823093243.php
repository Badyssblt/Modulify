<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240823093243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_asset (category_id INT NOT NULL, asset_id INT NOT NULL, INDEX IDX_EA9C151512469DE2 (category_id), INDEX IDX_EA9C15155DA1941 (asset_id), PRIMARY KEY(category_id, asset_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_asset ADD CONSTRAINT FK_EA9C151512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_asset ADD CONSTRAINT FK_EA9C15155DA1941 FOREIGN KEY (asset_id) REFERENCES asset (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_asset DROP FOREIGN KEY FK_EA9C151512469DE2');
        $this->addSql('ALTER TABLE category_asset DROP FOREIGN KEY FK_EA9C15155DA1941');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_asset');
    }
}
