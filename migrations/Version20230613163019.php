<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230613163019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE particulier (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, datenaissance DATE DEFAULT NULL, adresseemail1 VARCHAR(255) NOT NULL, adresseemail2 VARCHAR(255) DEFAULT NULL, adresseemail3 VARCHAR(255) DEFAULT NULL, numerotelephoneportable1 VARCHAR(255) NOT NULL, numerotelephoneportable2 VARCHAR(255) DEFAULT NULL, numerotelephonefixe1 VARCHAR(255) DEFAULT NULL, numerotelephonefixe2 VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', commentaire LONGTEXT DEFAULT NULL, actif TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professionnel (id INT AUTO_INCREMENT NOT NULL, nomsociete VARCHAR(255) NOT NULL, tvaintra VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) DEFAULT NULL, siren VARCHAR(255) DEFAULT NULL, adresseemail1 VARCHAR(255) NOT NULL, adresseemail2 VARCHAR(255) DEFAULT NULL, adresseemail3 VARCHAR(255) DEFAULT NULL, numerotelephoneportable1 VARCHAR(255) NOT NULL, numerotelephoneportable2 VARCHAR(255) DEFAULT NULL, numerotelephonefixe1 VARCHAR(255) DEFAULT NULL, numerotelephonefixe2 VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', commentaire LONGTEXT DEFAULT NULL, actif TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE particulier');
        $this->addSql('DROP TABLE professionnel');
    }
}
