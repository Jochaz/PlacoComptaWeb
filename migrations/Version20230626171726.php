<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230626171726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entete_document (id INT AUTO_INCREMENT NOT NULL, ligne1_gauche VARCHAR(255) NOT NULL, ligne2_gauche VARCHAR(255) DEFAULT NULL, ligne3_gauche VARCHAR(255) DEFAULT NULL, ligne1_droite VARCHAR(255) NOT NULL, ligne2_droite VARCHAR(255) DEFAULT NULL, ligne3_droite VARCHAR(255) DEFAULT NULL, ligne4_gauche VARCHAR(255) DEFAULT NULL, ligne4_droite VARCHAR(255) DEFAULT NULL, ville_fait_a VARCHAR(255) NOT NULL, numero_tel_fixe VARCHAR(15) DEFAULT NULL, numero_fax VARCHAR(15) DEFAULT NULL, numero_tel_portable VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE entete_document');
    }
}
