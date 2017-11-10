<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Employee;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employees')->delete();
        $positions = ['Президент', 'Руководитель отдела', 'Заместитель руководителя отдела', 'Менеджер', 'Программист', 'Стажер'];
        $faker = Faker::create("ru_RU");
        Employee::create([//добавляю президента компании
            'name' => $faker->name,
            'employmentDate' => $faker->dateTimeBetween($startDate = '-10 years', $endDate = 'now'),
            'salary' => $faker->randomFloat(2, 1000, 1000000),
            'position' => $positions[0],
        ]);
        $emplNumberInPosition = 5;//Количество сотрудников с одной должностью
        $emplTotalCount=1;//общее кол-во сотрудников
        for ($i = 1; $i < count($positions); $i++) {
            for ($j = 0; $j < $emplNumberInPosition; $j++) {
                Employee::create([
                    'name' => $faker->name,
                    'employmentDate' => $faker->dateTimeBetween($startDate = '-10 years', $endDate = 'now'),
                    'salary' => $faker->randomFloat(2, 1000, 1000000),
                    'position' => $positions[$i],
                    'head_id' => ($i == 1) ? 1 : random_int($emplTotalCount-$emplNumberInPosition/10+1, $emplTotalCount),
                    //начальник привязывается к случайному элементу из предыдущей должности.
                    //у первых двух должностей начальник известен сразу(null для директора и 1 для руководителя отдела)
                ]);
            }
            $emplTotalCount+=$emplNumberInPosition;
            $emplNumberInPosition*=10; //сотрудников с более низкой должностью на порядок больше
        }
    }
}
