<?php
/**
 * Created by PhpStorm.
 * User: Jamie
 * Date: 02.10.2018
 * Time: 9.25
 */

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Answergiven;
use App\Entity\Course;
use App\Entity\Exam;
use App\Entity\Examinstance;
use App\Entity\Examquestion;
use App\Entity\Question;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ExamUserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $teacherUser = new User();
        $teacherUser->setUsername('Teacher');
        $teacherUser->setPassword(password_hash('test', PASSWORD_BCRYPT));
        $teacherUser->setFirstname('Teacherfn');
        $teacherUser->setLastname('Teacherln');
        $teacherUser->setTeacher('1');
        $manager->persist($teacherUser);

        $studentUser = new User();
        $studentUser->setUsername('Student');
        $studentUser->setPassword(password_hash('test', PASSWORD_BCRYPT));
        $studentUser->setFirstname('Studentfn');
        $studentUser->setLastname('Studentln');
        $studentUser->setTeacher('0');
        $manager->persist($studentUser);

        $testCourse = new Course();
        $testCourse->setCourseName('PHP');
        $manager->persist($testCourse);

        $phpQuestion1 = new Question();
        $phpQuestion1->setCourseID(1);
        $phpQuestion1->setQuestion('1,2,3,4? How many bois are in my store?');
        $manager->persist($phpQuestion1);

        $phpAnswer1 = new Answer();
        $phpAnswer1->setQuestion_ID(1);
        $phpAnswer1->setAnswer('3');
        $phpAnswer1->setCorrectAnswer(1);
        $manager->persist($phpAnswer1);

        $phpAnswer2 = new Answer();
        $phpAnswer2->setQuestion_ID(1);
        $phpAnswer2->setAnswer('32');
        $phpAnswer2->setCorrectAnswer(1);
        $manager->persist($phpAnswer2);

        $phpAnswer3 = new Answer();
        $phpAnswer3->setQuestion_ID(1);
        $phpAnswer3->setAnswer('7');
        $phpAnswer3->setCorrectAnswer(1);
        $manager->persist($phpAnswer3);

        $phpExam1 = new Exam();
        $phpExam1->setCourse_ID(1);
        $phpExam1->setCreator_ID(1);
        $phpExam1->setExamName('Last time Bob');
        $manager->persist($phpExam1);

        $phpExamInstance1 = new Examinstance();
        $phpExamInstance1->setUserID(2);
        $phpExamInstance1->setExamID(1);
        $phpExamInstance1->setGrade('3');
        $manager->persist($phpExamInstance1);

        $phpExamQuestion1 = new Examquestion();
        $phpExamQuestion1->setQuestionID(1);
        $phpExamQuestion1->setExamId(1);
        $manager->persist($phpExamQuestion1);

        $phpAnswerGiven1 = new Answergiven();
        $phpAnswerGiven1->setAnswerID(1);
        $phpAnswerGiven1->setQuestionId(1);
        $phpAnswerGiven1->setExamInstanceID(1);
        $manager->persist($phpAnswerGiven1);

        $manager->flush();
    }
}