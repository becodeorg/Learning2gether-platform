<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191023141931 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('SET FOREIGN_KEY_CHECKS=0;');

        $this->addSql('ALTER TABLE language ADD code VARCHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE user ADD language_id INT NOT NULL, ADD password_hash VARCHAR(255) NOT NULL, ADD is_partner TINYINT(1) NOT NULL, ADD badgr_key VARCHAR(255) NOT NULL, ADD username VARCHAR(255) NOT NULL, ADD avatar VARCHAR(255) DEFAULT NULL, ADD created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64982F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64982F1BAF4 ON user (language_id)');

        $this->addSql('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE language DROP code');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64982F1BAF4');
        $this->addSql('DROP INDEX IDX_8D93D64982F1BAF4 ON user');
        $this->addSql('ALTER TABLE user DROP language_id, DROP password_hash, DROP is_partner, DROP badgr_key, DROP username, DROP avatar, DROP created');
    }
}
