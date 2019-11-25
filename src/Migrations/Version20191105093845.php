<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191105093845 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE progress (user_id INT NOT NULL, chapter_id INT NOT NULL, INDEX IDX_A18CAB24A76ED395 (user_id), INDEX IDX_A18CAB24579F4768 (chapter_id), PRIMARY KEY(user_id, chapter_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE progress ADD CONSTRAINT FK_A18CAB24A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE progress ADD CONSTRAINT FK_A18CAB24579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id) ON DELETE CASCADE');
        //$this->addSql('ALTER TABLE user CHANGE language_id language_id INT NOT NULL, CHANGE is_partner is_partner TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE progress');
        //$this->addSql('ALTER TABLE user CHANGE language_id language_id INT DEFAULT 1 NOT NULL, CHANGE is_partner is_partner TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
