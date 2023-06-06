<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230606171134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie_materiaux (id INT AUTO_INCREMENT NOT NULL, tvadefaut_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_3A9A69228A0E141B (tvadefaut_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE materiaux (id INT AUTO_INCREMENT NOT NULL, tva_id INT NOT NULL, categorie_id INT NOT NULL, unite_mesure_id INT NOT NULL, designation VARCHAR(255) NOT NULL, prix_achat DOUBLE PRECISION DEFAULT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, INDEX IDX_97C566254D79775F (tva_id), INDEX IDX_97C56625BCF5E72D (categorie_id), INDEX IDX_97C56625C75A06BF (unite_mesure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tva (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE DEFAULT NULL, taux DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unite_mesure (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, abreviation VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categorie_materiaux ADD CONSTRAINT FK_3A9A69228A0E141B FOREIGN KEY (tvadefaut_id) REFERENCES tva (id)');
        $this->addSql('ALTER TABLE materiaux ADD CONSTRAINT FK_97C566254D79775F FOREIGN KEY (tva_id) REFERENCES tva (id)');
        $this->addSql('ALTER TABLE materiaux ADD CONSTRAINT FK_97C56625BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_materiaux (id)');
        $this->addSql('ALTER TABLE materiaux ADD CONSTRAINT FK_97C56625C75A06BF FOREIGN KEY (unite_mesure_id) REFERENCES unite_mesure (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie_materiaux DROP FOREIGN KEY FK_3A9A69228A0E141B');
        $this->addSql('ALTER TABLE materiaux DROP FOREIGN KEY FK_97C566254D79775F');
        $this->addSql('ALTER TABLE materiaux DROP FOREIGN KEY FK_97C56625BCF5E72D');
        $this->addSql('ALTER TABLE materiaux DROP FOREIGN KEY FK_97C56625C75A06BF');
        $this->addSql('DROP TABLE categorie_materiaux');
        $this->addSql('DROP TABLE materiaux');
        $this->addSql('DROP TABLE tva');
        $this->addSql('DROP TABLE unite_mesure');
    }
}
