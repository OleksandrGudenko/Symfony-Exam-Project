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

        $dummyUser = new User();
        $dummyUser->setUsername('TestUser');
        $dummyUser->setPassword(password_hash('test', PASSWORD_BCRYPT));
        $dummyUser->setFirstname('Jamie');
        $dummyUser->setLastname('Burns');
        $dummyUser->setId('0');
        $dummyUser->setTeacher('1');
        $manager->persist($dummyUser);


        $manager->flush();
    }
}

#password_hash(password: 'test_password', algo: PASSWORD_BCRYPT))