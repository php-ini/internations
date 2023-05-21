<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230520190744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating the user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS `user` (`id` INT(10) NOT NULL AUTO_INCREMENT , `name` VARCHAR(50) NOT NULL , `email` VARCHAR(60) NOT NULL , `password` VARCHAR(100) NOT NULL , `roles` VARCHAR(50) NOT NULL DEFAULT '[\"ROLE_USER\"]' , `is_active` TINYINT(1) NOT NULL DEFAULT '1' , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME on update CURRENT_TIMESTAMP NULL , `deleted_at` DATETIME NULL , PRIMARY KEY (`id`), UNIQUE `email` (`email`)) ENGINE = InnoDB");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS `user`');
    }
}
