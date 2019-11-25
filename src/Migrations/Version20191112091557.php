<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191112091557 extends AbstractMigration
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
        $this->addSql('CREATE TABLE user_learning_module (user_id INT NOT NULL, learning_module_id INT NOT NULL, INDEX IDX_D80A015EA76ED395 (user_id), INDEX IDX_D80A015E55E9F8F6 (learning_module_id), PRIMARY KEY(user_id, learning_module_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pwd_reset_token (id INT AUTO_INCREMENT NOT NULL, email_id INT NOT NULL, selector VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, expires VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_62C7D88EA832C1C9 (email_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_post ADD CONSTRAINT FK_200B2044A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_post ADD CONSTRAINT FK_200B20444B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
//        $this->addSql('ALTER TABLE user_learning_module ADD CONSTRAINT FK_D80A015EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
//        $this->addSql('ALTER TABLE user_learning_module ADD CONSTRAINT FK_D80A015E55E9F8F6 FOREIGN KEY (learning_module_id) REFERENCES learning_module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pwd_reset_token ADD CONSTRAINT FK_62C7D88EA832C1C9 FOREIGN KEY (email_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE completed_modules');
        $this->addSql('DROP TABLE upvote');
        $this->addSql('ALTER TABLE category_translation DROP INDEX UNIQ_3F2070412469DE2, ADD INDEX IDX_3F2070412469DE2 (category_id)');
        $this->addSql('ALTER TABLE user DROP password_hash, DROP badgr_key');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE completed_modules (user_id INT NOT NULL, learning_module_id INT NOT NULL, INDEX IDX_D80A015E55E9F8F6 (learning_module_id), INDEX IDX_D80A015EA76ED395 (user_id), PRIMARY KEY(user_id, learning_module_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE upvote (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE completed_modules ADD CONSTRAINT FK_D80A015E55E9F8F6 FOREIGN KEY (learning_module_id) REFERENCES learning_module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE completed_modules ADD CONSTRAINT FK_D80A015EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user_post');
        $this->addSql('DROP TABLE user_learning_module');
        $this->addSql('DROP TABLE pwd_reset_token');
        $this->addSql('ALTER TABLE category_translation DROP INDEX IDX_3F2070412469DE2, ADD UNIQUE INDEX UNIQ_3F2070412469DE2 (category_id)');
        $this->addSql('ALTER TABLE user ADD password_hash VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD badgr_key VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
