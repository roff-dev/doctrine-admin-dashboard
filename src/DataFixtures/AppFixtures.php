<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Employee;
use App\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Create companies
        $companies = [];
        for ($i = 1; $i <= 10; $i++) {
            $company = new Company();
            $company->setName('Company ' . $i);
            $company->setEmail('company' . $i . '@example.com');
            $company->setWebsite('https://company' . $i . '.com');
            $manager->persist($company);
            $companies[] = $company; //Create array of companies to use for Employees
        }
        
        // Create employees with random company associations
        $firstNames = ['John', 'Jane', 'Michael', 'Emma', 'David', 'Sarah', 'Robert', 'Lisa', 'William', 'Emily'];
        $lastNames = ['Smith', 'Johnson', 'Brown', 'Davis', 'Wilson', 'Miller', 'Jones', 'Garcia', 'Martinez', 'Taylor'];
        
        // Create 3 employees for each company
        foreach ($companies as $company) {
            for ($i = 0; $i < 3; $i++) {
                $employee = new Employee();
                $employee->setFirstName($firstNames[array_rand($firstNames)]);
                $employee->setLastName($lastNames[array_rand($lastNames)]);
                $employee->setEmail(strtolower($employee->getFirstName()) . '.' . strtolower($employee->getLastName()) . '@example.com');
                $employee->setPhone('07' . rand(700, 999) . ' ' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT));
                $employee->setCompany($company);
                $manager->persist($employee);
            }
        }
        
        $manager->flush();
    }
}

?>