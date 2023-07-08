<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230706210930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ligne_devis ADD materiaux_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne_devis ADD CONSTRAINT FK_888B2F1B806EBBB2 FOREIGN KEY (materiaux_id) REFERENCES materiaux (id)');
        $this->addSql('CREATE INDEX IDX_888B2F1B806EBBB2 ON ligne_devis (materiaux_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ligne_devis DROP FOREIGN KEY FK_888B2F1B806EBBB2');
        $this->addSql('DROP INDEX IDX_888B2F1B806EBBB2 ON ligne_devis');
        $this->addSql('ALTER TABLE ligne_devis DROP materiaux_id');
    }
}
