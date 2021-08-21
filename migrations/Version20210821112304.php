<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210821112304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_competences (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, competence_id INT NOT NULL, niveau INT NOT NULL, appetence INT NOT NULL, INDEX IDX_723BBE5BA76ED395 (user_id), INDEX IDX_723BBE5B15761DAB (competence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_competences ADD CONSTRAINT FK_723BBE5BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_competences ADD CONSTRAINT FK_723BBE5B15761DAB FOREIGN KEY (competence_id) REFERENCES competences (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_competences DROP FOREIGN KEY FK_723BBE5B15761DAB');
        $this->addSql('DROP TABLE competences');
        $this->addSql('DROP TABLE user_competences');
    }
}
