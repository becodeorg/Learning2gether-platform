<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191023130906 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chapter ADD learning_module_id INT NOT NULL, ADD quiz_id INT NOT NULL');
        $this->addSql('ALTER TABLE chapter ADD CONSTRAINT FK_F981B52E55E9F8F6 FOREIGN KEY (learning_module_id) REFERENCES learning_module (id)');
        $this->addSql('ALTER TABLE chapter ADD CONSTRAINT FK_F981B52E853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('CREATE INDEX IDX_F981B52E55E9F8F6 ON chapter (learning_module_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F981B52E853CD175 ON chapter (quiz_id)');
        $this->addSql('ALTER TABLE chapter_page ADD chapter_id INT NOT NULL');
        $this->addSql('ALTER TABLE chapter_page ADD CONSTRAINT FK_38FBC2B5579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id)');
        $this->addSql('CREATE INDEX IDX_38FBC2B5579F4768 ON chapter_page (chapter_id)');
        $this->addSql('ALTER TABLE quiz_question ADD quiz_id INT NOT NULL');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('CREATE INDEX IDX_6033B00B853CD175 ON quiz_question (quiz_id)');
        $this->addSql('ALTER TABLE quiz_answer ADD quiz_question_id INT NOT NULL');
        $this->addSql('ALTER TABLE quiz_answer ADD CONSTRAINT FK_3799BA7C3101E51F FOREIGN KEY (quiz_question_id) REFERENCES quiz_question (id)');
        $this->addSql('CREATE INDEX IDX_3799BA7C3101E51F ON quiz_answer (quiz_question_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52E55E9F8F6');
        $this->addSql('ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52E853CD175');
        $this->addSql('DROP INDEX IDX_F981B52E55E9F8F6 ON chapter');
        $this->addSql('DROP INDEX UNIQ_F981B52E853CD175 ON chapter');
        $this->addSql('ALTER TABLE chapter DROP learning_module_id, DROP quiz_id');
        $this->addSql('ALTER TABLE chapter_page DROP FOREIGN KEY FK_38FBC2B5579F4768');
        $this->addSql('DROP INDEX IDX_38FBC2B5579F4768 ON chapter_page');
        $this->addSql('ALTER TABLE chapter_page DROP chapter_id');
        $this->addSql('ALTER TABLE quiz_answer DROP FOREIGN KEY FK_3799BA7C3101E51F');
        $this->addSql('DROP INDEX IDX_3799BA7C3101E51F ON quiz_answer');
        $this->addSql('ALTER TABLE quiz_answer DROP quiz_question_id');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY FK_6033B00B853CD175');
        $this->addSql('DROP INDEX IDX_6033B00B853CD175 ON quiz_question');
        $this->addSql('ALTER TABLE quiz_question DROP quiz_id');
    }
}
