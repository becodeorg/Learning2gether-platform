<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191106132556 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_post (user_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_200B2044A76ED395 (user_id), INDEX IDX_200B20444B89032C (post_id), PRIMARY KEY(user_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
      //  $this->addSql('CREATE TABLE user_learning_module (user_id INT NOT NULL, learning_module_id INT NOT NULL, INDEX IDX_D80A015EA76ED395 (user_id), INDEX IDX_D80A015E55E9F8F6 (learning_module_id), PRIMARY KEY(user_id, learning_module_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_post ADD CONSTRAINT FK_200B2044A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_post ADD CONSTRAINT FK_200B20444B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
       // $this->addSql('ALTER TABLE user_learning_module ADD CONSTRAINT FK_D80A015EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
       // $this->addSql('ALTER TABLE user_learning_module ADD CONSTRAINT FK_D80A015E55E9F8F6 FOREIGN KEY (learning_module_id) REFERENCES learning_module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post RENAME INDEX post_topic_id_fk TO IDX_5A8A6C8D1F55203D');
        $this->addSql('ALTER TABLE user DROP password_hash, DROP badgr_key');
        $this->addSql('ALTER TABLE learning_module ADD image VARCHAR(255) NOT NULL, ADD type VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_post');
        $this->addSql('DROP TABLE user_learning_module');
        $this->addSql('ALTER TABLE learning_module DROP image, DROP type');
        $this->addSql('ALTER TABLE post RENAME INDEX idx_5a8a6c8d1f55203d TO post_topic_id_fk');
        $this->addSql('ALTER TABLE user ADD password_hash VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD badgr_key VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
