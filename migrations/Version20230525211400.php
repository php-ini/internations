<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230525211400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create many-to-many relation between `role` and `user` tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS `role_user` (`role_id` INT(10) NOT NULL , `user_id` INT(10) NOT NULL , PRIMARY KEY (`role_id`, `user_id`)) ENGINE = InnoDB");
        $this->addSql("ALTER TABLE `role_user` ADD CONSTRAINT `fk.role_user.role_id` FOREIGN KEY (`role_id`) REFERENCES `role`(`id`) ON DELETE CASCADE ON UPDATE CASCADE");
        $this->addSql("ALTER TABLE `role_user` ADD CONSTRAINT `fk.role_user.user_id` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE IF EXISTS `role_user`");
    }
}
