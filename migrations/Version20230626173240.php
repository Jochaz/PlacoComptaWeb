<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230626173240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entete_document CHANGE numero_tel_fixe numero_tel_fixe VARCHAR(255) DEFAULT NULL, CHANGE numero_fax numero_fax VARCHAR(255) DEFAULT NULL, CHANGE numero_tel_portable numero_tel_portable VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entete_document CHANGE numero_tel_fixe numero_tel_fixe VARCHAR(15) DEFAULT NULL, CHANGE numero_fax numero_fax VARCHAR(15) DEFAULT NULL, CHANGE numero_tel_portable numero_tel_portable VARCHAR(15) NOT NULL');
    }
}
