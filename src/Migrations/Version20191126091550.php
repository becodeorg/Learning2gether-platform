<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191126091550 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question ADD chapter_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E579F4768 ON question (chapter_id)');
        $this->addSql('ALTER TABLE category ADD learning_module_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C155E9F8F6 FOREIGN KEY (learning_module_id) REFERENCES learning_module (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C155E9F8F6 ON category (learning_module_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C155E9F8F6');
        $this->addSql('DROP INDEX UNIQ_64C19C155E9F8F6 ON category');
        $this->addSql('ALTER TABLE category DROP learning_module_id');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E579F4768');
        $this->addSql('DROP INDEX IDX_B6F7494E579F4768 ON question');
        $this->addSql('ALTER TABLE question DROP chapter_id');
    }
}
