<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230627075757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, ligne1 VARCHAR(255) NOT NULL, ligne2 VARCHAR(255) DEFAULT NULL, ligne3 VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, cp VARCHAR(255) DEFAULT NULL, boite_postale VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, professionnel_id INT DEFAULT NULL, mode_reglement_id INT NOT NULL, adresse_chantier_id INT DEFAULT NULL, adresse_facturation_id INT DEFAULT NULL, num_devis VARCHAR(255) NOT NULL, date_devis DATE DEFAULT NULL, date_signature DATE DEFAULT NULL, num_dossier VARCHAR(255) DEFAULT NULL, objet LONGTEXT DEFAULT NULL, remise INT NOT NULL, prix_ht INT NOT NULL, prix_ttc INT NOT NULL, tvaautoliquidation TINYINT(1) DEFAULT NULL, plusutilise TINYINT(1) NOT NULL, INDEX IDX_8B27C52B19EB6921 (client_id), INDEX IDX_8B27C52B8A49CC82 (professionnel_id), INDEX IDX_8B27C52BE04B7BE2 (mode_reglement_id), INDEX IDX_8B27C52B1ECEFD72 (adresse_chantier_id), INDEX IDX_8B27C52B5BBD1224 (adresse_facturation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne_devis (id INT AUTO_INCREMENT NOT NULL, tva_id INT NOT NULL, devis_id INT DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, prix_unitaire INT DEFAULT NULL, qte INT DEFAULT NULL, remise INT DEFAULT NULL, INDEX IDX_888B2F1B4D79775F (tva_id), INDEX IDX_888B2F1B41DEFADA (devis_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mode_reglement (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B19EB6921 FOREIGN KEY (client_id) REFERENCES particulier (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B8A49CC82 FOREIGN KEY (professionnel_id) REFERENCES professionnel (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BE04B7BE2 FOREIGN KEY (mode_reglement_id) REFERENCES mode_reglement (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B1ECEFD72 FOREIGN KEY (adresse_chantier_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B5BBD1224 FOREIGN KEY (adresse_facturation_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE ligne_devis ADD CONSTRAINT FK_888B2F1B4D79775F FOREIGN KEY (tva_id) REFERENCES tva (id)');
        $this->addSql('ALTER TABLE ligne_devis ADD CONSTRAINT FK_888B2F1B41DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B19EB6921');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B8A49CC82');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52BE04B7BE2');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B1ECEFD72');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B5BBD1224');
        $this->addSql('ALTER TABLE ligne_devis DROP FOREIGN KEY FK_888B2F1B4D79775F');
        $this->addSql('ALTER TABLE ligne_devis DROP FOREIGN KEY FK_888B2F1B41DEFADA');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE ligne_devis');
        $this->addSql('DROP TABLE mode_reglement');
    }
}
