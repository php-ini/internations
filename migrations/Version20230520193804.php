<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230520193804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the many-to-many relations for user_group';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS `user_group` (`id` INT NOT NULL AUTO_INCREMENT , `user_id` INT(10) NOT NULL , `group_id` INT(10) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB");
        $this->addSql("ALTER TABLE `user_group` ADD UNIQUE(`user_id`, `group_id`);");
        $this->addSql("ALTER TABLE `user_group` ADD CONSTRAINT `fk.user_group.user_id` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE");
        $this->addSql("ALTER TABLE `user_group` ADD CONSTRAINT `fk.user_group.group_id` FOREIGN KEY (`group_id`) REFERENCES `groups`(`id`) ON DELETE CASCADE ON UPDATE CASCADE");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS `user_group`');
    }
}
