<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703214925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE modele_piece_materiaux (modele_piece_id INT NOT NULL, materiaux_id INT NOT NULL, INDEX IDX_49BC7723ACD7F1AA (modele_piece_id), INDEX IDX_49BC7723806EBBB2 (materiaux_id), PRIMARY KEY(modele_piece_id, materiaux_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE modele_piece_materiaux ADD CONSTRAINT FK_49BC7723ACD7F1AA FOREIGN KEY (modele_piece_id) REFERENCES modele_piece (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modele_piece_materiaux ADD CONSTRAINT FK_49BC7723806EBBB2 FOREIGN KEY (materiaux_id) REFERENCES materiaux (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modele_piece_materiaux DROP FOREIGN KEY FK_49BC7723ACD7F1AA');
        $this->addSql('ALTER TABLE modele_piece_materiaux DROP FOREIGN KEY FK_49BC7723806EBBB2');
        $this->addSql('DROP TABLE modele_piece_materiaux');
    }
}
