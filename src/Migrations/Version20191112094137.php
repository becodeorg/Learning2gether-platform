<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191112094137 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

//        $this->addSql('ALTER TABLE user_learning_module ADD CONSTRAINT FK_D80A015EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
//        $this->addSql('ALTER TABLE user_learning_module ADD CONSTRAINT FK_D80A015E55E9F8F6 FOREIGN KEY (learning_module_id) REFERENCES learning_module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pwd_reset_token DROP FOREIGN KEY FK_62C7D88EA832C1C9');
        $this->addSql('DROP INDEX UNIQ_62C7D88EA832C1C9 ON pwd_reset_token');
        $this->addSql('ALTER TABLE pwd_reset_token CHANGE email_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE pwd_reset_token ADD CONSTRAINT FK_62C7D88EA832C1C9 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62C7D88EA832C1C9 ON pwd_reset_token (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pwd_reset_token DROP FOREIGN KEY FK_62C7D88EA832C1C9');
        $this->addSql('DROP INDEX UNIQ_62C7D88EA832C1C9 ON pwd_reset_token');
        $this->addSql('ALTER TABLE pwd_reset_token CHANGE user_id  email_id INT NOT NULL');
        $this->addSql('ALTER TABLE pwd_reset_token ADD CONSTRAINT FK_62C7D88EA832C1C9 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62C7D88EA832C1C9 ON pwd_reset_token (user_id)');
//        $this->addSql('ALTER TABLE user_learning_module DROP FOREIGN KEY FK_D80A015EA76ED395');
//        $this->addSql('ALTER TABLE user_learning_module DROP FOREIGN KEY FK_D80A015E55E9F8F6');
    }
}
