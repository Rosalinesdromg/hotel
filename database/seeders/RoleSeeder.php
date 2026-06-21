<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Buat semua role
        $roles = ['customer', 'kasir', 'resepsionis', 'manager', 'ceo'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Buat akun CEO/Admin default
        $ceo = User::firstOrCreate(
            ['email' => 'ceo@lunarhotel.com'],
            [
                'name'     => 'CEO Lunar Hotel',
                'password' => Hash::make('password123'),
                'phone'    => '08100000001',
            ]
        );
        $ceo->assignRole('ceo');

        // Buat akun Manager default
        $manager = User::firstOrCreate(
            ['email' => 'manager@lunarhotel.com'],
            [
                'name'     => 'Manager Lunar Hotel',
                'password' => Hash::make('password123'),
                'phone'    => '08100000002',
            ]
        );
        $manager->assignRole('manager');

        // Buat akun Resepsionis default
        $resep = User::firstOrCreate(
            ['email' => 'resepsionis@lunarhotel.com'],
            [
                'name'     => 'Resepsionis 1',
                'password' => Hash::make('password123'),
                'phone'    => '08100000003',
            ]
        );
        $resep->assignRole('resepsionis');

        // Buat akun Kasir default
        $kasir = User::firstOrCreate(
            ['email' => 'kasir@lunarhotel.com'],
            [
                'name'     => 'Kasir 1',
                'password' => Hash::make('password123'),
                'phone'    => '08100000004',
            ]
        );
        $kasir->assignRole('kasir');
    }
}