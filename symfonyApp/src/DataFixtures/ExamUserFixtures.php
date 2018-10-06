<?php
/**
 * Created by PhpStorm.
 * User: Jamie
 * Date: 02.10.2018
 * Time: 9.25
 */

namespace App\DataFixtures;

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



        $manager->flush();
    }
}