<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181003114516 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY answer_question_id');
        $this->addSql('ALTER TABLE answer CHANGE question_id question_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE answergiven CHANGE answer_id answer_id INT DEFAULT NULL, CHANGE question_id question_id INT DEFAULT NULL, CHANGE exam_instance_id exam_instance_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exam CHANGE course_id course_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE examinstance DROP FOREIGN KEY exam_instance_user_id');
        $this->addSql('ALTER TABLE examinstance CHANGE user_id user_id INT DEFAULT NULL, CHANGE exam_id exam_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE examinstance ADD CONSTRAINT FK_3BDE09CBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE examquestion DROP FOREIGN KEY exam_question_exam_id');
        $this->addSql('ALTER TABLE examquestion CHANGE question_id question_id INT DEFAULT NULL, CHANGE exam_id exam_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE examquestion ADD CONSTRAINT FK_CF19F15B578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY question_course_id');
        $this->addSql('ALTER TABLE question CHANGE course_id course_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE user CHANGE password password VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE answer CHANGE question_id question_id INT NOT NULL');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT answer_question_id FOREIGN KEY (question_id) REFERENCES question (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE answergiven CHANGE exam_instance_id exam_instance_id INT NOT NULL, CHANGE answer_id answer_id INT NOT NULL, CHANGE question_id question_id INT NOT NULL');
        $this->addSql('ALTER TABLE exam CHANGE course_id course_id INT NOT NULL');
        $this->addSql('ALTER TABLE examinstance DROP FOREIGN KEY FK_3BDE09CBA76ED395');
        $this->addSql('ALTER TABLE examinstance CHANGE exam_id exam_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE examinstance ADD CONSTRAINT exam_instance_user_id FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE examquestion DROP FOREIGN KEY FK_CF19F15B578D5E91');
        $this->addSql('ALTER TABLE examquestion CHANGE exam_id exam_id INT NOT NULL, CHANGE question_id question_id INT NOT NULL');
        $this->addSql('ALTER TABLE examquestion ADD CONSTRAINT exam_question_exam_id FOREIGN KEY (exam_id) REFERENCES exam (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E591CC992');
        $this->addSql('ALTER TABLE question CHANGE course_id course_id INT NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT question_course_id FOREIGN KEY (course_id) REFERENCES course (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE password password VARCHAR(45) NOT NULL COLLATE utf8_general_ci');
    }
}
