<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191104141202 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE created created DATETIME NOT NULL');

        $this->addSql('UPDATE l2g.language SET name = \'English\', code = \'en\' WHERE id = 1');
        $this->addSql('UPDATE l2g.language SET name = \'Español\', code = \'es\' WHERE id = 2');
        $this->addSql('UPDATE l2g.language SET name = \'Français\', code = \'fr\' WHERE id = 3');
        $this->addSql('UPDATE l2g.language SET name = \'Íslenska\', code = \'is\' WHERE id = 4');
        $this->addSql('UPDATE l2g.language SET name = \'български\', code = \'bg\' WHERE id = 5');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
