<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231001173556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture CHANGE objet objet LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne_facture ADD tva_id INT NOT NULL, ADD materiaux_id INT NOT NULL, ADD designation VARCHAR(255) DEFAULT NULL, ADD prix_unitaire DOUBLE PRECISION DEFAULT NULL, ADD qte INT DEFAULT NULL, ADD remise DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne_facture ADD CONSTRAINT FK_611F5A294D79775F FOREIGN KEY (tva_id) REFERENCES tva (id)');
        $this->addSql('ALTER TABLE ligne_facture ADD CONSTRAINT FK_611F5A29806EBBB2 FOREIGN KEY (materiaux_id) REFERENCES materiaux (id)');
        $this->addSql('CREATE INDEX IDX_611F5A294D79775F ON ligne_facture (tva_id)');
        $this->addSql('CREATE INDEX IDX_611F5A29806EBBB2 ON ligne_facture (materiaux_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture CHANGE objet objet VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne_facture DROP FOREIGN KEY FK_611F5A294D79775F');
        $this->addSql('ALTER TABLE ligne_facture DROP FOREIGN KEY FK_611F5A29806EBBB2');
        $this->addSql('DROP INDEX IDX_611F5A294D79775F ON ligne_facture');
        $this->addSql('DROP INDEX IDX_611F5A29806EBBB2 ON ligne_facture');
        $this->addSql('ALTER TABLE ligne_facture DROP tva_id, DROP materiaux_id, DROP designation, DROP prix_unitaire, DROP qte, DROP remise');
    }
}
