<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231028204927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE acompte (id INT AUTO_INCREMENT NOT NULL, devis_id INT DEFAULT NULL, mode_reglement_id INT DEFAULT NULL, montant DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_CE996BEC41DEFADA (devis_id), INDEX IDX_CE996BECE04B7BE2 (mode_reglement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE acompte ADD CONSTRAINT FK_CE996BEC41DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('ALTER TABLE acompte ADD CONSTRAINT FK_CE996BECE04B7BE2 FOREIGN KEY (mode_reglement_id) REFERENCES mode_reglement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE acompte DROP FOREIGN KEY FK_CE996BEC41DEFADA');
        $this->addSql('ALTER TABLE acompte DROP FOREIGN KEY FK_CE996BECE04B7BE2');
        $this->addSql('DROP TABLE acompte');
    }
}
