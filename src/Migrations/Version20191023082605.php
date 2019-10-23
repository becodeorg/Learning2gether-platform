<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191023082605 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE learning_module (id INT AUTO_INCREMENT NOT NULL, is_published TINYINT(1) NOT NULL, badge VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE learning_module_translation (id INT AUTO_INCREMENT NOT NULL, learning_module_id INT NOT NULL, language_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_C96B23F555E9F8F6 (learning_module_id), INDEX IDX_C96B23F582F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE learning_module_translation ADD CONSTRAINT FK_C96B23F555E9F8F6 FOREIGN KEY (learning_module_id) REFERENCES learning_module (id)');
        $this->addSql('ALTER TABLE learning_module_translation ADD CONSTRAINT FK_C96B23F582F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');

        $this->addSql("insert into language (name) values ('English')");
        $this->addSql("insert into language (name) values ('Español')");
        $this->addSql("insert into language (name) values ('Français')");
        $this->addSql("insert into language (name) values ('Íslenska')");
        $this->addSql("insert into language (name) values ('български')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE learning_module_translation DROP FOREIGN KEY FK_C96B23F555E9F8F6');
        $this->addSql('ALTER TABLE learning_module_translation DROP FOREIGN KEY FK_C96B23F582F1BAF4');
        $this->addSql('DROP TABLE learning_module');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE learning_module_translation');
    }
}
