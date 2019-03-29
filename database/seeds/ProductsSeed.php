<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ProductsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->dropColunms();
    }
    
    public function dropColunms(){
        
         Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['image']);
         });
        
    }
}
