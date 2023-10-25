<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231016144844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE echeance (id INT AUTO_INCREMENT NOT NULL, facture_id INT DEFAULT NULL, mode_reglement_id INT DEFAULT NULL, montant DOUBLE PRECISION DEFAULT NULL, is_regle TINYINT(1) NOT NULL, INDEX IDX_40D9893B7F2DEE08 (facture_id), INDEX IDX_40D9893BE04B7BE2 (mode_reglement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE echeance ADD CONSTRAINT FK_40D9893B7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE echeance ADD CONSTRAINT FK_40D9893BE04B7BE2 FOREIGN KEY (mode_reglement_id) REFERENCES mode_reglement (id)');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410E04B7BE2');
        $this->addSql('DROP INDEX IDX_FE866410E04B7BE2 ON facture');
        $this->addSql('ALTER TABLE facture DROP mode_reglement_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE echeance DROP FOREIGN KEY FK_40D9893B7F2DEE08');
        $this->addSql('ALTER TABLE echeance DROP FOREIGN KEY FK_40D9893BE04B7BE2');
        $this->addSql('DROP TABLE echeance');
        $this->addSql('ALTER TABLE facture ADD mode_reglement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410E04B7BE2 FOREIGN KEY (mode_reglement_id) REFERENCES mode_reglement (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_FE866410E04B7BE2 ON facture (mode_reglement_id)');
    }
}
