<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            FirstUserSeeder::class,
            DepositStatusesSeeder::class,
            DepositActionsSeeder::class,
        ]);
        $this->call(AccountsTableSeeder::class);
        $this->call(TransactionsTableSeeder::class);
    }
}
