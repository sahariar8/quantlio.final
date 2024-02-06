<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TestDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = 'test_details';
        $file = dirname(__FILE__) . '/files/testDetails.xlsx';

        $array = Excel::toArray([], $file);

        $testDetails = $array[0];

        foreach ($testDetails as $key => $item) {
            if ($key == 0) continue;

            // print_r($item);
            DB::table($table)->insert([
                'print_order'=>$item[0],
                'test_name' => $item[1],
                'class' => $item[2],
                'description' => $item[4] != '' ? "Metabolite of " . $item[4] : null,
                'parent' => $item[4],
                'metabolite' => $item[5]
            ]);
        }
    }
}
