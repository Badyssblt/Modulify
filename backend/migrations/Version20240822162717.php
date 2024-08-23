<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240822162717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_asset (user_id INT NOT NULL, asset_id INT NOT NULL, INDEX IDX_E06DA104A76ED395 (user_id), INDEX IDX_E06DA1045DA1941 (asset_id), PRIMARY KEY(user_id, asset_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_asset ADD CONSTRAINT FK_E06DA104A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_asset ADD CONSTRAINT FK_E06DA1045DA1941 FOREIGN KEY (asset_id) REFERENCES asset (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_asset DROP FOREIGN KEY FK_E06DA104A76ED395');
        $this->addSql('ALTER TABLE user_asset DROP FOREIGN KEY FK_E06DA1045DA1941');
        $this->addSql('DROP TABLE user_asset');
    }
}
