<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250326131556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, couleur VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_fichier (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, fichier_id_id INT DEFAULT NULL, INDEX IDX_A94778B59D86650F (user_id_id), INDEX IDX_A94778B5481D2A6F (fichier_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tag_fichier ADD CONSTRAINT FK_A94778B59D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tag_fichier ADD CONSTRAINT FK_A94778B5481D2A6F FOREIGN KEY (fichier_id_id) REFERENCES fichier (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag_fichier DROP FOREIGN KEY FK_A94778B59D86650F');
        $this->addSql('ALTER TABLE tag_fichier DROP FOREIGN KEY FK_A94778B5481D2A6F');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_fichier');
    }
}
