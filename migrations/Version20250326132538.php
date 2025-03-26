<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250326132538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, couleur VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_fichier (id INT AUTO_INCREMENT NOT NULL, tag_id INT DEFAULT NULL, fichier_id INT DEFAULT NULL, INDEX IDX_A94778B5BAD26311 (tag_id), INDEX IDX_A94778B5F915CFE (fichier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tag_fichier ADD CONSTRAINT FK_A94778B5BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
        $this->addSql('ALTER TABLE tag_fichier ADD CONSTRAINT FK_A94778B5F915CFE FOREIGN KEY (fichier_id) REFERENCES fichier (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag_fichier DROP FOREIGN KEY FK_A94778B5BAD26311');
        $this->addSql('ALTER TABLE tag_fichier DROP FOREIGN KEY FK_A94778B5F915CFE');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_fichier');
    }
}
