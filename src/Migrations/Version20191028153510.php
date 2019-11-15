<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191028153510 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

 //       $this->addSql('CREATE TABLE user_post (user_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_200B2044A76ED395 (user_id), INDEX IDX_200B20444B89032C (post_id), PRIMARY KEY(user_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
   //     $this->addSql('ALTER TABLE user_post ADD CONSTRAINT FK_200B2044A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
     //   $this->addSql('ALTER TABLE user_post ADD CONSTRAINT FK_200B20444B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
       // $this->addSql('ALTER TABLE category_translation DROP INDEX UNIQ_3F2070412469DE2, ADD INDEX IDX_3F2070412469DE2 (category_id)');
        $this->addSql('ALTER TABLE post ADD topic_id INT NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D1F55203D FOREIGN KEY (topic_id) REFERENCES question (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D1F55203D ON post (topic_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

       // $this->addSql('DROP TABLE user_post');
        //$this->addSql('ALTER TABLE category_translation DROP INDEX IDX_3F2070412469DE2, ADD UNIQUE INDEX UNIQ_3F2070412469DE2 (category_id)');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D1F55203D');
        $this->addSql('DROP INDEX IDX_5A8A6C8D1F55203D ON post');
        $this->addSql('ALTER TABLE post DROP topic_id');
    }
}
