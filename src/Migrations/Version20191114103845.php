<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191114103845 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question RENAME INDEX idx_9d40de1b82f1baf4 TO IDX_B6F7494E82F1BAF4');
        $this->addSql('ALTER TABLE question RENAME INDEX idx_9d40de1bb03a8386 TO IDX_B6F7494EB03A8386');
       $this->addSql('ALTER TABLE question RENAME INDEX idx_9d40de1b12469de2 TO IDX_B6F7494E12469DE2');
       $this->addSql('ALTER TABLE chapter_translation ADD description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE category ADD learning_module_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C155E9F8F6 FOREIGN KEY (learning_module_id) REFERENCES learning_module (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C155E9F8F6 ON category (learning_module_id)');
        $this->addSql('ALTER TABLE user_post ADD CONSTRAINT FK_200B2044A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_post ADD CONSTRAINT FK_200B20444B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C155E9F8F6');
        $this->addSql('DROP INDEX UNIQ_64C19C155E9F8F6 ON category');
        $this->addSql('ALTER TABLE category DROP learning_module_id');
        $this->addSql('ALTER TABLE chapter_translation DROP description');
        $this->addSql('ALTER TABLE question RENAME INDEX idx_b6f7494e82f1baf4 TO IDX_9D40DE1B82F1BAF4');
        $this->addSql('ALTER TABLE question RENAME INDEX idx_b6f7494e12469de2 TO IDX_9D40DE1B12469DE2');
        $this->addSql('ALTER TABLE question RENAME INDEX idx_b6f7494eb03a8386 TO IDX_9D40DE1BB03A8386');
        $this->addSql('ALTER TABLE user_post DROP FOREIGN KEY FK_200B2044A76ED395');
        $this->addSql('ALTER TABLE user_post DROP FOREIGN KEY FK_200B20444B89032C');
    }
}
