<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateProductsTvaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, insert TVA with id=1, name='tv1', value=0 if it doesn't exist
        DB::table('tvas')->updateOrInsert(  // lowercase 'tvas'
            ['id' => 1],
            [
                'name' => 'tv1',
                'value' => 0.00,  // 'value' not 'valeur'
                'iduser' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        // Check if the foreign key exists before trying to drop it
        $constraintExists = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'products' 
            AND CONSTRAINT_NAME = 'products_id_tva_foreign'
        ");
        
        if (!empty($constraintExists)) {
            // Drop the existing foreign key
            DB::statement('ALTER TABLE products DROP FOREIGN KEY products_id_tva_foreign');
        }
        
        // Modify the column to add default value
        DB::statement('ALTER TABLE products MODIFY COLUMN id_tva BIGINT UNSIGNED NOT NULL DEFAULT 1');
        
        // Re-add the foreign key constraint
        DB::statement('ALTER TABLE products ADD CONSTRAINT products_id_tva_foreign FOREIGN KEY (id_tva) REFERENCES tvas(id)');
    }
}