<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221018063850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users_customers DROP FOREIGN KEY FK_42E34C067B3B43D');
        $this->addSql('ALTER TABLE users_customers DROP FOREIGN KEY FK_42E34C0C3568B40');
        $this->addSql('DROP TABLE users_customers');
        $this->addSql('ALTER TABLE users ADD relation_id INT NOT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E93256915B FOREIGN KEY (relation_id) REFERENCES customers (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E93256915B ON users (relation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_customers (users_id INT NOT NULL, customers_id INT NOT NULL, INDEX IDX_42E34C067B3B43D (users_id), INDEX IDX_42E34C0C3568B40 (customers_id), PRIMARY KEY(users_id, customers_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE users_customers ADD CONSTRAINT FK_42E34C067B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_customers ADD CONSTRAINT FK_42E34C0C3568B40 FOREIGN KEY (customers_id) REFERENCES customers (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E93256915B');
        $this->addSql('DROP INDEX IDX_1483A5E93256915B ON users');
        $this->addSql('ALTER TABLE users DROP relation_id');
    }
}
