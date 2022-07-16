<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220714161202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande CHANGE n_commande n_commande INT NOT NULL');
        $this->addSql('ALTER TABLE commande_burger CHANGE quantite quantite INT NOT NULL');
        $this->addSql('ALTER TABLE commande_menu CHANGE quantite quantite INT NOT NULL');
        $this->addSql('DROP INDEX IDX_29A5EC27157B6845 ON produit');
        $this->addSql('ALTER TABLE produit DROP produit_commades_id');
        $this->addSql('ALTER TABLE taille CHANGE prix prix DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande CHANGE n_commande n_commande VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE commande_burger CHANGE quantite quantite VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE commande_menu CHANGE quantite quantite VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit ADD produit_commades_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_29A5EC27157B6845 ON produit (produit_commades_id)');
        $this->addSql('ALTER TABLE taille CHANGE prix prix DOUBLE PRECISION NOT NULL');
    }
}
