<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DiskonSeeder extends Seeder
{
    public function run()
    {
        $diskonData = [
            [
                'tanggal'    => '2025-07-03', 
                'nominal'    => 100000,
                'created_at' => date("Y-m-d H:i:s"),
               
            ],
            [
                'tanggal'    => '2025-07-04',
                'nominal'    => 200000, 
                'created_at' => date("Y-m-d H:i:s"),
                
            ],
            [
                'tanggal'    => '2025-07-05',
                'nominal'    => 300000, 
                'created_at' => date("Y-m-d H:i:s"),
                
            ],
            [
                'tanggal'    => '2025-07-06', 
                'nominal'    => 100000, 
                'created_at' => date("Y-m-d H:i:s"),
                
            ],
             [
                'tanggal'    => '2025-07-07', 
                'nominal'    => 300000, 
                'created_at' => date("Y-m-d H:i:s"),
                
             ],
             [
                'tanggal'    => '2025-07-08', 
                'nominal'    => 100000, 
                'created_at' => date("Y-m-d H:i:s"),
               
            ],
            [
                'tanggal'    => '2025-07-09', 
                'nominal'    => 200000, 
                'created_at' => date("Y-m-d H:i:s"),
                
            ],
            [
                'tanggal'    => '2025-07-10', 
                'nominal'    => 200000, 
                'created_at' => date("Y-m-d H:i:s"),
                
            ],
            [
                'tanggal'    => '2025-07-11', 
                'nominal'    => 300000, 
                'created_at' => date("Y-m-d H:i:s"),
                
            ],
            [
                'tanggal'    => '2025-07-12', 
                'nominal'    => 100000, 
                'created_at' => date("Y-m-d H:i:s"),
                
            ],
        ];

        $this->db->table('diskon')->insertBatch($diskonData);
    }
}