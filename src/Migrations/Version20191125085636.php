<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191125085636 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE learning_module (id INT AUTO_INCREMENT NOT NULL, is_published TINYINT(1) NOT NULL, badge VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, src VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_C53D045FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chapter (id INT AUTO_INCREMENT NOT NULL, learning_module_id INT NOT NULL, quiz_id INT NOT NULL, chapter_number INT NOT NULL, INDEX IDX_F981B52E55E9F8F6 (learning_module_id), UNIQUE INDEX UNIQ_F981B52E853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chapter_page (id INT AUTO_INCREMENT NOT NULL, chapter_id INT NOT NULL, page_number INT NOT NULL, INDEX IDX_38FBC2B5579F4768 (chapter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, language_id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, is_partner TINYINT(1) NOT NULL, username VARCHAR(255) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_8D93D64982F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_post (user_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_200B2044A76ED395 (user_id), INDEX IDX_200B20444B89032C (post_id), PRIMARY KEY(user_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_learning_module (user_id INT NOT NULL, learning_module_id INT NOT NULL, INDEX IDX_D80A015EA76ED395 (user_id), INDEX IDX_D80A015E55E9F8F6 (learning_module_id), PRIMARY KEY(user_id, learning_module_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_chapter (user_id INT NOT NULL, chapter_id INT NOT NULL, INDEX IDX_A18CAB24A76ED395 (user_id), INDEX IDX_A18CAB24579F4768 (chapter_id), PRIMARY KEY(user_id, chapter_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, language_id INT NOT NULL, created_by_id INT NOT NULL, category_id INT NOT NULL, subject VARCHAR(255) NOT NULL, date DATETIME NOT NULL, INDEX IDX_B6F7494E82F1BAF4 (language_id), INDEX IDX_B6F7494EB03A8386 (created_by_id), INDEX IDX_B6F7494E12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_question_translation (id INT AUTO_INCREMENT NOT NULL, language_id INT NOT NULL, quiz_question_id INT NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_1B6A958382F1BAF4 (language_id), INDEX IDX_1B6A95833101E51F (quiz_question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chapter_translation (id INT AUTO_INCREMENT NOT NULL, language_id INT NOT NULL, chapter_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_69A3A4882F1BAF4 (language_id), INDEX IDX_69A3A48579F4768 (chapter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, question_id INT NOT NULL, subject VARCHAR(255) NOT NULL, date DATETIME NOT NULL, INDEX IDX_5A8A6C8DB03A8386 (created_by_id), INDEX IDX_5A8A6C8D1F55203D (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_question (id INT AUTO_INCREMENT NOT NULL, quiz_id INT NOT NULL, question_number INT NOT NULL, INDEX IDX_6033B00B853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chapter_page_translation (id INT AUTO_INCREMENT NOT NULL, language_id INT NOT NULL, chapter_page_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_8219597082F1BAF4 (language_id), INDEX IDX_82195970767767AB (chapter_page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pwd_reset_token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, expires VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_62C7D88EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_translation (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, language_id INT NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_3F2070412469DE2 (category_id), INDEX IDX_3F2070482F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_answer_translation (id INT AUTO_INCREMENT NOT NULL, language_id INT NOT NULL, quiz_answer_id INT NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_15E13DB82F1BAF4 (language_id), INDEX IDX_15E13DBAC5339E1 (quiz_answer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_answer (id INT AUTO_INCREMENT NOT NULL, quiz_question_id INT NOT NULL, is_correct TINYINT(1) NOT NULL, INDEX IDX_3799BA7C3101E51F (quiz_question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE learning_module_translation (id INT AUTO_INCREMENT NOT NULL, learning_module_id INT NOT NULL, language_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_C96B23F555E9F8F6 (learning_module_id), INDEX IDX_C96B23F582F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chapter ADD CONSTRAINT FK_F981B52E55E9F8F6 FOREIGN KEY (learning_module_id) REFERENCES learning_module (id)');
        $this->addSql('ALTER TABLE chapter ADD CONSTRAINT FK_F981B52E853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE chapter_page ADD CONSTRAINT FK_38FBC2B5579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64982F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE user_post ADD CONSTRAINT FK_200B2044A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_post ADD CONSTRAINT FK_200B20444B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_learning_module ADD CONSTRAINT FK_D80A015EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_learning_module ADD CONSTRAINT FK_D80A015E55E9F8F6 FOREIGN KEY (learning_module_id) REFERENCES learning_module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_chapter ADD CONSTRAINT FK_A18CAB24A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_chapter ADD CONSTRAINT FK_A18CAB24579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE quiz_question_translation ADD CONSTRAINT FK_1B6A958382F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE quiz_question_translation ADD CONSTRAINT FK_1B6A95833101E51F FOREIGN KEY (quiz_question_id) REFERENCES quiz_question (id)');
        $this->addSql('ALTER TABLE chapter_translation ADD CONSTRAINT FK_69A3A4882F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE chapter_translation ADD CONSTRAINT FK_69A3A48579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D1F55203D FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE chapter_page_translation ADD CONSTRAINT FK_8219597082F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE chapter_page_translation ADD CONSTRAINT FK_82195970767767AB FOREIGN KEY (chapter_page_id) REFERENCES chapter_page (id)');
        $this->addSql('ALTER TABLE pwd_reset_token ADD CONSTRAINT FK_62C7D88EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE category_translation ADD CONSTRAINT FK_3F2070412469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE category_translation ADD CONSTRAINT FK_3F2070482F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE quiz_answer_translation ADD CONSTRAINT FK_15E13DB82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE quiz_answer_translation ADD CONSTRAINT FK_15E13DBAC5339E1 FOREIGN KEY (quiz_answer_id) REFERENCES quiz_answer (id)');
        $this->addSql('ALTER TABLE quiz_answer ADD CONSTRAINT FK_3799BA7C3101E51F FOREIGN KEY (quiz_question_id) REFERENCES quiz_question (id)');
        $this->addSql('ALTER TABLE learning_module_translation ADD CONSTRAINT FK_C96B23F555E9F8F6 FOREIGN KEY (learning_module_id) REFERENCES learning_module (id)');
        $this->addSql('ALTER TABLE learning_module_translation ADD CONSTRAINT FK_C96B23F582F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52E55E9F8F6');
        $this->addSql('ALTER TABLE user_learning_module DROP FOREIGN KEY FK_D80A015E55E9F8F6');
        $this->addSql('ALTER TABLE learning_module_translation DROP FOREIGN KEY FK_C96B23F555E9F8F6');
        $this->addSql('ALTER TABLE chapter_page DROP FOREIGN KEY FK_38FBC2B5579F4768');
        $this->addSql('ALTER TABLE user_chapter DROP FOREIGN KEY FK_A18CAB24579F4768');
        $this->addSql('ALTER TABLE chapter_translation DROP FOREIGN KEY FK_69A3A48579F4768');
        $this->addSql('ALTER TABLE chapter_page_translation DROP FOREIGN KEY FK_82195970767767AB');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FA76ED395');
        $this->addSql('ALTER TABLE user_post DROP FOREIGN KEY FK_200B2044A76ED395');
        $this->addSql('ALTER TABLE user_learning_module DROP FOREIGN KEY FK_D80A015EA76ED395');
        $this->addSql('ALTER TABLE user_chapter DROP FOREIGN KEY FK_A18CAB24A76ED395');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EB03A8386');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DB03A8386');
        $this->addSql('ALTER TABLE pwd_reset_token DROP FOREIGN KEY FK_62C7D88EA76ED395');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D1F55203D');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E12469DE2');
        $this->addSql('ALTER TABLE category_translation DROP FOREIGN KEY FK_3F2070412469DE2');
        $this->addSql('ALTER TABLE user_post DROP FOREIGN KEY FK_200B20444B89032C');
        $this->addSql('ALTER TABLE quiz_question_translation DROP FOREIGN KEY FK_1B6A95833101E51F');
        $this->addSql('ALTER TABLE quiz_answer DROP FOREIGN KEY FK_3799BA7C3101E51F');
        $this->addSql('ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52E853CD175');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY FK_6033B00B853CD175');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64982F1BAF4');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E82F1BAF4');
        $this->addSql('ALTER TABLE quiz_question_translation DROP FOREIGN KEY FK_1B6A958382F1BAF4');
        $this->addSql('ALTER TABLE chapter_translation DROP FOREIGN KEY FK_69A3A4882F1BAF4');
        $this->addSql('ALTER TABLE chapter_page_translation DROP FOREIGN KEY FK_8219597082F1BAF4');
        $this->addSql('ALTER TABLE category_translation DROP FOREIGN KEY FK_3F2070482F1BAF4');
        $this->addSql('ALTER TABLE quiz_answer_translation DROP FOREIGN KEY FK_15E13DB82F1BAF4');
        $this->addSql('ALTER TABLE learning_module_translation DROP FOREIGN KEY FK_C96B23F582F1BAF4');
        $this->addSql('ALTER TABLE quiz_answer_translation DROP FOREIGN KEY FK_15E13DBAC5339E1');
        $this->addSql('DROP TABLE learning_module');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE chapter');
        $this->addSql('DROP TABLE chapter_page');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_post');
        $this->addSql('DROP TABLE user_learning_module');
        $this->addSql('DROP TABLE user_chapter');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz_question_translation');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE chapter_translation');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE quiz_question');
        $this->addSql('DROP TABLE chapter_page_translation');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE pwd_reset_token');
        $this->addSql('DROP TABLE category_translation');
        $this->addSql('DROP TABLE quiz_answer_translation');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE quiz_answer');
        $this->addSql('DROP TABLE learning_module_translation');
    }
}
