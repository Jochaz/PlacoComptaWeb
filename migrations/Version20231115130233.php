<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231115130233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE acompte (id INT AUTO_INCREMENT NOT NULL, devis_id INT DEFAULT NULL, mode_reglement_id INT DEFAULT NULL, montant DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_CE996BEC41DEFADA (devis_id), INDEX IDX_CE996BECE04B7BE2 (mode_reglement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adresse_document (id INT AUTO_INCREMENT NOT NULL, ligne1 VARCHAR(255) NOT NULL, ligne2 VARCHAR(255) DEFAULT NULL, ligne3 VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, cp VARCHAR(255) DEFAULT NULL, boite_postale VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adresse_facturation (id INT AUTO_INCREMENT NOT NULL, ligne1 VARCHAR(255) NOT NULL, ligne2 VARCHAR(255) DEFAULT NULL, ligne3 VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, cp VARCHAR(255) DEFAULT NULL, boite_postale VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_materiaux (id INT AUTO_INCREMENT NOT NULL, tvadefaut_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, plus_utilise TINYINT(1) DEFAULT NULL, INDEX IDX_3A9A69228A0E141B (tvadefaut_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, particulier_id INT DEFAULT NULL, professionnel_id INT DEFAULT NULL, adresse_chantier_id INT DEFAULT NULL, adresse_facturation_id INT DEFAULT NULL, num_devis VARCHAR(255) NOT NULL, date_devis DATE DEFAULT NULL, date_signature DATE DEFAULT NULL, num_dossier VARCHAR(255) DEFAULT NULL, objet LONGTEXT DEFAULT NULL, remise DOUBLE PRECISION DEFAULT NULL, prix_ht DOUBLE PRECISION DEFAULT NULL, prix_ttc DOUBLE PRECISION DEFAULT NULL, tvaautoliquidation TINYINT(1) DEFAULT NULL, plusutilise TINYINT(1) NOT NULL, INDEX IDX_8B27C52BA89E0E67 (particulier_id), INDEX IDX_8B27C52B8A49CC82 (professionnel_id), INDEX IDX_8B27C52B1ECEFD72 (adresse_chantier_id), INDEX IDX_8B27C52B5BBD1224 (adresse_facturation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE echeance (id INT AUTO_INCREMENT NOT NULL, facture_id INT DEFAULT NULL, mode_reglement_id INT DEFAULT NULL, montant DOUBLE PRECISION DEFAULT NULL, is_regle TINYINT(1) NOT NULL, INDEX IDX_40D9893B7F2DEE08 (facture_id), INDEX IDX_40D9893BE04B7BE2 (mode_reglement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entete_document (id INT AUTO_INCREMENT NOT NULL, ligne1_gauche VARCHAR(255) NOT NULL, ligne2_gauche VARCHAR(255) DEFAULT NULL, ligne3_gauche VARCHAR(255) DEFAULT NULL, ligne1_droite VARCHAR(255) NOT NULL, ligne2_droite VARCHAR(255) DEFAULT NULL, ligne3_droite VARCHAR(255) DEFAULT NULL, ligne4_gauche VARCHAR(255) DEFAULT NULL, ligne4_droite VARCHAR(255) DEFAULT NULL, ville_fait_a VARCHAR(255) NOT NULL, numero_tel_fixe VARCHAR(255) DEFAULT NULL, numero_fax VARCHAR(255) DEFAULT NULL, numero_tel_portable VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, particulier_id INT DEFAULT NULL, professionnel_id INT DEFAULT NULL, adresse_chantier_id INT DEFAULT NULL, adresse_facturation_id INT DEFAULT NULL, devis_id INT DEFAULT NULL, num_facture VARCHAR(255) NOT NULL, date_facture DATE DEFAULT NULL, date_paiement_facture DATE DEFAULT NULL, num_dossier VARCHAR(255) DEFAULT NULL, objet LONGTEXT DEFAULT NULL, remise DOUBLE PRECISION DEFAULT NULL, prix_ht DOUBLE PRECISION DEFAULT NULL, prix_ttc DOUBLE PRECISION DEFAULT NULL, tvaautoliquidation TINYINT(1) DEFAULT NULL, plusutilise TINYINT(1) NOT NULL, is_editer TINYINT(1) DEFAULT NULL, INDEX IDX_FE866410A89E0E67 (particulier_id), INDEX IDX_FE8664108A49CC82 (professionnel_id), INDEX IDX_FE8664101ECEFD72 (adresse_chantier_id), INDEX IDX_FE8664105BBD1224 (adresse_facturation_id), UNIQUE INDEX UNIQ_FE86641041DEFADA (devis_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne_devis (id INT AUTO_INCREMENT NOT NULL, tva_id INT NOT NULL, devis_id INT DEFAULT NULL, materiaux_id INT DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, prix_unitaire DOUBLE PRECISION DEFAULT NULL, qte INT DEFAULT NULL, remise DOUBLE PRECISION DEFAULT NULL, INDEX IDX_888B2F1B4D79775F (tva_id), INDEX IDX_888B2F1B41DEFADA (devis_id), INDEX IDX_888B2F1B806EBBB2 (materiaux_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne_facture (id INT AUTO_INCREMENT NOT NULL, facture_id INT DEFAULT NULL, tva_id INT NOT NULL, materiaux_id INT DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, prix_unitaire DOUBLE PRECISION DEFAULT NULL, qte INT DEFAULT NULL, remise DOUBLE PRECISION DEFAULT NULL, INDEX IDX_611F5A297F2DEE08 (facture_id), INDEX IDX_611F5A294D79775F (tva_id), INDEX IDX_611F5A29806EBBB2 (materiaux_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE materiaux (id INT AUTO_INCREMENT NOT NULL, tva_id INT NOT NULL, categorie_id INT NOT NULL, unite_mesure_id INT NOT NULL, designation VARCHAR(255) NOT NULL, prix_achat DOUBLE PRECISION DEFAULT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, plus_utilise TINYINT(1) DEFAULT NULL, INDEX IDX_97C566254D79775F (tva_id), INDEX IDX_97C56625BCF5E72D (categorie_id), INDEX IDX_97C56625C75A06BF (unite_mesure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mode_reglement (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modele_piece (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, plus_utilise TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modele_piece_materiaux (modele_piece_id INT NOT NULL, materiaux_id INT NOT NULL, INDEX IDX_49BC7723ACD7F1AA (modele_piece_id), INDEX IDX_49BC7723806EBBB2 (materiaux_id), PRIMARY KEY(modele_piece_id, materiaux_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parametrage_devis (id INT AUTO_INCREMENT NOT NULL, prefixe VARCHAR(255) NOT NULL, annee_en_cours TINYINT(1) NOT NULL, numero_agenerer INT NOT NULL, nombre_caractere_total INT NOT NULL, completion_avec_zero TINYINT(1) NOT NULL, type_document VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parametrage_facture (id INT AUTO_INCREMENT NOT NULL, prefixe VARCHAR(255) NOT NULL, annee_en_cours TINYINT(1) NOT NULL, numero_agenerer INT NOT NULL, nombre_caractere_total INT NOT NULL, completion_avec_zero TINYINT(1) NOT NULL, type_document VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE particulier (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, datenaissance DATE DEFAULT NULL, adresseemail1 VARCHAR(255) NOT NULL, adresseemail2 VARCHAR(255) DEFAULT NULL, adresseemail3 VARCHAR(255) DEFAULT NULL, numerotelephoneportable1 VARCHAR(255) NOT NULL, numerotelephoneportable2 VARCHAR(255) DEFAULT NULL, numerotelephonefixe1 VARCHAR(255) DEFAULT NULL, numerotelephonefixe2 VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', commentaire LONGTEXT DEFAULT NULL, actif TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professionnel (id INT AUTO_INCREMENT NOT NULL, nomsociete VARCHAR(255) NOT NULL, tvaintra VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) DEFAULT NULL, siren VARCHAR(255) DEFAULT NULL, adresseemail1 VARCHAR(255) NOT NULL, adresseemail2 VARCHAR(255) DEFAULT NULL, adresseemail3 VARCHAR(255) DEFAULT NULL, numerotelephoneportable1 VARCHAR(255) NOT NULL, numerotelephoneportable2 VARCHAR(255) DEFAULT NULL, numerotelephonefixe1 VARCHAR(255) DEFAULT NULL, numerotelephonefixe2 VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', commentaire LONGTEXT DEFAULT NULL, actif TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tva (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE DEFAULT NULL, taux DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unite_mesure (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, abreviation VARCHAR(255) NOT NULL, num_ordre INT DEFAULT NULL, plus_utilise TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE acompte ADD CONSTRAINT FK_CE996BEC41DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('ALTER TABLE acompte ADD CONSTRAINT FK_CE996BECE04B7BE2 FOREIGN KEY (mode_reglement_id) REFERENCES mode_reglement (id)');
        $this->addSql('ALTER TABLE categorie_materiaux ADD CONSTRAINT FK_3A9A69228A0E141B FOREIGN KEY (tvadefaut_id) REFERENCES tva (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BA89E0E67 FOREIGN KEY (particulier_id) REFERENCES particulier (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B8A49CC82 FOREIGN KEY (professionnel_id) REFERENCES professionnel (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B1ECEFD72 FOREIGN KEY (adresse_chantier_id) REFERENCES adresse_document (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B5BBD1224 FOREIGN KEY (adresse_facturation_id) REFERENCES adresse_facturation (id)');
        $this->addSql('ALTER TABLE echeance ADD CONSTRAINT FK_40D9893B7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE echeance ADD CONSTRAINT FK_40D9893BE04B7BE2 FOREIGN KEY (mode_reglement_id) REFERENCES mode_reglement (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410A89E0E67 FOREIGN KEY (particulier_id) REFERENCES particulier (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664108A49CC82 FOREIGN KEY (professionnel_id) REFERENCES professionnel (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664101ECEFD72 FOREIGN KEY (adresse_chantier_id) REFERENCES adresse_document (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664105BBD1224 FOREIGN KEY (adresse_facturation_id) REFERENCES adresse_facturation (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641041DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('ALTER TABLE ligne_devis ADD CONSTRAINT FK_888B2F1B4D79775F FOREIGN KEY (tva_id) REFERENCES tva (id)');
        $this->addSql('ALTER TABLE ligne_devis ADD CONSTRAINT FK_888B2F1B41DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('ALTER TABLE ligne_devis ADD CONSTRAINT FK_888B2F1B806EBBB2 FOREIGN KEY (materiaux_id) REFERENCES materiaux (id)');
        $this->addSql('ALTER TABLE ligne_facture ADD CONSTRAINT FK_611F5A297F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE ligne_facture ADD CONSTRAINT FK_611F5A294D79775F FOREIGN KEY (tva_id) REFERENCES tva (id)');
        $this->addSql('ALTER TABLE ligne_facture ADD CONSTRAINT FK_611F5A29806EBBB2 FOREIGN KEY (materiaux_id) REFERENCES materiaux (id)');
        $this->addSql('ALTER TABLE materiaux ADD CONSTRAINT FK_97C566254D79775F FOREIGN KEY (tva_id) REFERENCES tva (id)');
        $this->addSql('ALTER TABLE materiaux ADD CONSTRAINT FK_97C56625BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_materiaux (id)');
        $this->addSql('ALTER TABLE materiaux ADD CONSTRAINT FK_97C56625C75A06BF FOREIGN KEY (unite_mesure_id) REFERENCES unite_mesure (id)');
        $this->addSql('ALTER TABLE modele_piece_materiaux ADD CONSTRAINT FK_49BC7723ACD7F1AA FOREIGN KEY (modele_piece_id) REFERENCES modele_piece (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modele_piece_materiaux ADD CONSTRAINT FK_49BC7723806EBBB2 FOREIGN KEY (materiaux_id) REFERENCES materiaux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE acompte DROP FOREIGN KEY FK_CE996BEC41DEFADA');
        $this->addSql('ALTER TABLE acompte DROP FOREIGN KEY FK_CE996BECE04B7BE2');
        $this->addSql('ALTER TABLE categorie_materiaux DROP FOREIGN KEY FK_3A9A69228A0E141B');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52BA89E0E67');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B8A49CC82');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B1ECEFD72');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B5BBD1224');
        $this->addSql('ALTER TABLE echeance DROP FOREIGN KEY FK_40D9893B7F2DEE08');
        $this->addSql('ALTER TABLE echeance DROP FOREIGN KEY FK_40D9893BE04B7BE2');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410A89E0E67');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664108A49CC82');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664101ECEFD72');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664105BBD1224');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641041DEFADA');
        $this->addSql('ALTER TABLE ligne_devis DROP FOREIGN KEY FK_888B2F1B4D79775F');
        $this->addSql('ALTER TABLE ligne_devis DROP FOREIGN KEY FK_888B2F1B41DEFADA');
        $this->addSql('ALTER TABLE ligne_devis DROP FOREIGN KEY FK_888B2F1B806EBBB2');
        $this->addSql('ALTER TABLE ligne_facture DROP FOREIGN KEY FK_611F5A297F2DEE08');
        $this->addSql('ALTER TABLE ligne_facture DROP FOREIGN KEY FK_611F5A294D79775F');
        $this->addSql('ALTER TABLE ligne_facture DROP FOREIGN KEY FK_611F5A29806EBBB2');
        $this->addSql('ALTER TABLE materiaux DROP FOREIGN KEY FK_97C566254D79775F');
        $this->addSql('ALTER TABLE materiaux DROP FOREIGN KEY FK_97C56625BCF5E72D');
        $this->addSql('ALTER TABLE materiaux DROP FOREIGN KEY FK_97C56625C75A06BF');
        $this->addSql('ALTER TABLE modele_piece_materiaux DROP FOREIGN KEY FK_49BC7723ACD7F1AA');
        $this->addSql('ALTER TABLE modele_piece_materiaux DROP FOREIGN KEY FK_49BC7723806EBBB2');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE acompte');
        $this->addSql('DROP TABLE adresse_document');
        $this->addSql('DROP TABLE adresse_facturation');
        $this->addSql('DROP TABLE categorie_materiaux');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE echeance');
        $this->addSql('DROP TABLE entete_document');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE ligne_devis');
        $this->addSql('DROP TABLE ligne_facture');
        $this->addSql('DROP TABLE materiaux');
        $this->addSql('DROP TABLE mode_reglement');
        $this->addSql('DROP TABLE modele_piece');
        $this->addSql('DROP TABLE modele_piece_materiaux');
        $this->addSql('DROP TABLE parametrage_devis');
        $this->addSql('DROP TABLE parametrage_facture');
        $this->addSql('DROP TABLE particulier');
        $this->addSql('DROP TABLE professionnel');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE tva');
        $this->addSql('DROP TABLE unite_mesure');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
