<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;
class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name'=>'draft' , 'id' =>1],
            ['name'=>'publish' , 'id' =>2],
            ['name'=>'schedule' , 'id' =>3]
        ];
        Status::insert($data);
    }
}
