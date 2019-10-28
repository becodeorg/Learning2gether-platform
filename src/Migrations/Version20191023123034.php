<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191023123034 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quiz_question (id INT AUTO_INCREMENT NOT NULL, question_number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_answer (id INT AUTO_INCREMENT NOT NULL, is_correct TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_answer_translation (id INT AUTO_INCREMENT NOT NULL, language_id INT NOT NULL, quiz_answer_id INT NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_15E13DB82F1BAF4 (language_id), INDEX IDX_15E13DBAC5339E1 (quiz_answer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_question_translation (id INT AUTO_INCREMENT NOT NULL, language_id INT NOT NULL, quiz_question_id INT NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_1B6A958382F1BAF4 (language_id), INDEX IDX_1B6A95833101E51F (quiz_question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quiz_answer_translation ADD CONSTRAINT FK_15E13DB82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE quiz_answer_translation ADD CONSTRAINT FK_15E13DBAC5339E1 FOREIGN KEY (quiz_answer_id) REFERENCES quiz_answer (id)');
        $this->addSql('ALTER TABLE quiz_question_translation ADD CONSTRAINT FK_1B6A958382F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE quiz_question_translation ADD CONSTRAINT FK_1B6A95833101E51F FOREIGN KEY (quiz_question_id) REFERENCES quiz_question (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quiz_question_translation DROP FOREIGN KEY FK_1B6A95833101E51F');
        $this->addSql('ALTER TABLE quiz_answer_translation DROP FOREIGN KEY FK_15E13DBAC5339E1');
        $this->addSql('DROP TABLE quiz_question');
        $this->addSql('DROP TABLE quiz_answer');
        $this->addSql('DROP TABLE quiz_answer_translation');
        $this->addSql('DROP TABLE quiz_question_translation');
        $this->addSql('DROP TABLE quiz');
    }
}
