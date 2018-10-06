<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181006103701 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Answer DROP FOREIGN KEY answer_question_id');
        $this->addSql('ALTER TABLE Answer DROP correct_answer, CHANGE question_id question_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE AnswerGiven DROP FOREIGN KEY answer_exam_instance_id');
        $this->addSql('ALTER TABLE AnswerGiven DROP FOREIGN KEY answer_given_answer_id');
        $this->addSql('ALTER TABLE AnswerGiven DROP FOREIGN KEY answer_given_exam_question_id');
        $this->addSql('ALTER TABLE AnswerGiven CHANGE answer_id answer_id INT DEFAULT NULL, CHANGE question_id question_id INT DEFAULT NULL, CHANGE exam_instance_id exam_instance_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE AnswerGiven ADD CONSTRAINT FK_4AECB04D2142DF1C FOREIGN KEY (exam_instance_id) REFERENCES examinstance (id)');
        $this->addSql('ALTER TABLE AnswerGiven ADD CONSTRAINT FK_4AECB04DAA334807 FOREIGN KEY (answer_id) REFERENCES answer (id)');
        $this->addSql('ALTER TABLE AnswerGiven ADD CONSTRAINT FK_4AECB04D1E27F6BF FOREIGN KEY (question_id) REFERENCES examquestion (id)');
        $this->addSql('ALTER TABLE Exam DROP FOREIGN KEY exam_creator_id');
        $this->addSql('DROP INDEX exam_user_id_idx ON Exam');
        $this->addSql('ALTER TABLE Exam CHANGE course_id course_id INT DEFAULT NULL, CHANGE creator_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE ExamInstance DROP FOREIGN KEY exam_instance_user_id');
        $this->addSql('ALTER TABLE ExamInstance CHANGE user_id user_id INT DEFAULT NULL, CHANGE exam_id exam_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ExamInstance ADD CONSTRAINT FK_3BDE09CBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ExamQuestion DROP FOREIGN KEY exam_question_exam_id');
        $this->addSql('ALTER TABLE ExamQuestion DROP FOREIGN KEY exam_question_question_id');
        $this->addSql('ALTER TABLE ExamQuestion CHANGE question_id question_id INT DEFAULT NULL, CHANGE exam_id exam_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ExamQuestion ADD CONSTRAINT FK_CF19F15B578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE ExamQuestion ADD CONSTRAINT FK_CF19F15B1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE Question DROP FOREIGN KEY question_course_id');
        $this->addSql('ALTER TABLE Question ADD correct_answer_id INT NOT NULL, CHANGE course_id course_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Question ADD CONSTRAINT FK_B6F7494E591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('DROP INDEX username_UNIQUE ON User');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE answer ADD correct_answer TINYINT(1) NOT NULL, CHANGE question_id question_id INT NOT NULL');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT answer_question_id FOREIGN KEY (question_id) REFERENCES Question (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE answergiven DROP FOREIGN KEY FK_4AECB04D2142DF1C');
        $this->addSql('ALTER TABLE answergiven DROP FOREIGN KEY FK_4AECB04DAA334807');
        $this->addSql('ALTER TABLE answergiven DROP FOREIGN KEY FK_4AECB04D1E27F6BF');
        $this->addSql('ALTER TABLE answergiven CHANGE exam_instance_id exam_instance_id INT NOT NULL, CHANGE answer_id answer_id INT NOT NULL, CHANGE question_id question_id INT NOT NULL');
        $this->addSql('ALTER TABLE answergiven ADD CONSTRAINT answer_exam_instance_id FOREIGN KEY (exam_instance_id) REFERENCES ExamInstance (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE answergiven ADD CONSTRAINT answer_given_answer_id FOREIGN KEY (answer_id) REFERENCES Answer (id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE answergiven ADD CONSTRAINT answer_given_exam_question_id FOREIGN KEY (question_id) REFERENCES ExamQuestion (id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE exam CHANGE course_id course_id INT NOT NULL, CHANGE user_id creator_id INT NOT NULL');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT exam_creator_id FOREIGN KEY (creator_id) REFERENCES User (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX exam_user_id_idx ON exam (creator_id)');
        $this->addSql('ALTER TABLE examinstance DROP FOREIGN KEY FK_3BDE09CBA76ED395');
        $this->addSql('ALTER TABLE examinstance CHANGE exam_id exam_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE examinstance ADD CONSTRAINT exam_instance_user_id FOREIGN KEY (user_id) REFERENCES User (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE examquestion DROP FOREIGN KEY FK_CF19F15B578D5E91');
        $this->addSql('ALTER TABLE examquestion DROP FOREIGN KEY FK_CF19F15B1E27F6BF');
        $this->addSql('ALTER TABLE examquestion CHANGE exam_id exam_id INT NOT NULL, CHANGE question_id question_id INT NOT NULL');
        $this->addSql('ALTER TABLE examquestion ADD CONSTRAINT exam_question_exam_id FOREIGN KEY (exam_id) REFERENCES Exam (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE examquestion ADD CONSTRAINT exam_question_question_id FOREIGN KEY (question_id) REFERENCES Question (id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E591CC992');
        $this->addSql('ALTER TABLE question DROP correct_answer_id, CHANGE course_id course_id INT NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT question_course_id FOREIGN KEY (course_id) REFERENCES Course (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX username_UNIQUE ON user (username)');
    }
}
