<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191023120400 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE chapter_page_translation (id INT AUTO_INCREMENT NOT NULL, language_id INT NOT NULL, chapter_page_id INT NOT NULL, title VARCHAR(255) NOT NULL, content JSON DEFAULT NULL, INDEX IDX_8219597082F1BAF4 (language_id), INDEX IDX_82195970767767AB (chapter_page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chapter_page (id INT AUTO_INCREMENT NOT NULL, page_number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chapter_page_translation ADD CONSTRAINT FK_8219597082F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE chapter_page_translation ADD CONSTRAINT FK_82195970767767AB FOREIGN KEY (chapter_page_id) REFERENCES chapter_page (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chapter_page_translation DROP FOREIGN KEY FK_82195970767767AB');
        $this->addSql('DROP TABLE chapter_page_translation');
        $this->addSql('DROP TABLE chapter_page');
    }
}
