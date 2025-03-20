<?php

namespace Modules\HR\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Management\Entities\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            ['name' => 'Без департамента', 'company_id' => 1],
            ['name' => 'Департамент внедрения информационных технологий в сфере агропромышленного комплекса', 'company_id' => 1],
            ['name' => 'Департамент внедрения информационных технологий в сферах туризма и культуры', 'company_id' => 1],
            ['name' => 'Депатрамент стратегического планирования', 'company_id' => 1],
            ['name' => 'Департамент по техническим вопросам', 'company_id' => 1],
            ['name' => 'Депатрамент электронного правительства', 'company_id' => 1],
            ['name' => 'Группа информационного обеспечения и развития правительственного портала Республики Узбекистан в сети интернет', 'company_id' => 1],
            ['name' => 'Департамент оцифровки законотворческой деятельности и развития системы «Электронный парламент»', 'company_id' => 1],
            ['name' => 'Ресурсный центр сети ZiyoNET', 'company_id' => 1],
            ['name' => 'Отделение Ресурсного центра сети ZiyoNET при МВССО', 'company_id' => 1],
            ['name' => 'Отделение Ресурсного центра сети ZiyoNET при МНО', 'company_id' => 1],
            ['name' => 'Департамент по реализации проектов для бизнеса', 'company_id' => 1],
        ];
//
//        foreach ($departments as $department) {
//            Department::create($department);
//        }

    }


}
