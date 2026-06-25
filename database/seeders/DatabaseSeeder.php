<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== ADMIN USER =====
        User::firstOrCreate(
            ['email' => 'admin@riaspesta.com'],
            [
                'name'     => 'Admin Rias Pesta',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'phone'    => '08127603567',
            ]
        );

        // ===== CUSTOMER DUMMY =====
        User::firstOrCreate(
            ['email' => 'customer@demo.com'],
            [
                'name'     => 'Customer Demo',
                'password' => Hash::make('password'),
                'role'     => 'customer',
                'phone'    => '08123456789',
            ]
        );

        // ===== PAKET LAYANAN ASLI =====
        $packages = [
            [
                'name'        => 'Silver',
                'category'    => 'paket',
                'price'       => 8000000,
                'is_active'   => true,
                'image'       => 'packages/silver.jpeg',
                'description' => "DEKORASI: Pelaminan + Pentas 4M, Kain Dinding, Lighting Pelaminan, Wedding Gate, Wedding Sign, Kotak Duit (1).\n\nTENDA: Tenda Standar 4x6 (3), Kursi + Sampul (80), Pentas Orgen, Meja Hidangan (1), Meja Pondok (1), Meja Tamu (1), Pintu Masuk Lembayung (1), Janur (1).\n\nATTIRE & MAKEUP: Baju Akad 1 Pasang, Baju Resepsi 1 Pasang, Aksesories Pengantin, Makeup Pengantin Akad + Resepsi, Makeup Touch Up Resepsi, Makeup Orang Tua (2).",
            ],
            [
                'name'        => 'Gold',
                'category'    => 'paket',
                'price'       => 14000000,
                'is_active'   => true,
                'image'       => 'packages/gold.jpeg',
                'description' => "DEKORASI: Pelaminan + Pentas 4x4/4x6, Mini Garden, Lighting Pelaminan, Wedding Gate, Wedding Sign, Dekorasi Set Akad Nikah, Kotak Duit (1).\n\nTENDA: Tenda Semi Balon 4x6 (3), Kursi + Sampul (100), Pentas Orgen, Blower (2), Karpet Jalan ±20M, Kain Dinding, Meja Hidangan (2), Meja Pondok (2), Meja Tamu (1), Pintu Masuk Kerucut (1), Janur (1).\n\nATTIRE & MAKEUP: Baju Akad 1 Pasang, Baju Resepsi 1 Pasang, Aksesories Pengantin, Makeup Pengantin Akad + Resepsi, Makeup Touch Up Resepsi, Makeup Orang Tua (2).\n\nINCLUDE: Orgen Sound s/d 17.00 WIB, Henna, MC Akad.",
            ],
            [
                'name'        => 'Platinum I',
                'category'    => 'paket',
                'price'       => 16500000,
                'is_active'   => true,
                'image'       => 'packages/platinum_I.jpeg',
                'description' => "DEKORASI: Pelaminan + Pentas 6M, Mini Garden, Lighting Pelaminan, Dekor Kamar, Wedding Gate, Wedding Sign, Dekorasi Set Akad Nikah, Kotak Duit (2).\n\nTENDA: Tenda Semi Balon 4x6 (4), Kursi + Sampul (150), Pentas Orgen, Blower (3), Full Karpet, Kain Dinding, Meja Bulat (2), Meja Hidangan (2), Meja Pondok (2), Meja Tamu (2), Pintu Masuk Kerucut (1), Janur (1).\n\nATTIRE & MAKEUP: Baju Akad 1 Pasang, Baju Resepsi 2 Pasang, Baju Besan 2 Pasang, Aksesories Pengantin, Makeup Pengantin Akad + Resepsi, Makeup Touch Up Resepsi, Makeup Orang Tua (2).\n\nINCLUDE: Orgen Sound s/d 17.00 WIB, Henna, MC Akad.",
            ],
            [
                'name'        => 'Platinum II',
                'category'    => 'paket',
                'price'       => 18000000,
                'is_active'   => true,
                'image'       => 'packages/platinum_II.jpeg',
                'description' => "DEKORASI: Pelaminan + Pentas 6M, Mini Garden, Lighting Pelaminan, Dekor Kamar, Wedding Gate, Wedding Sign, Dekorasi Set Akad Nikah, Kotak Duit (2).\n\nTENDA: Tenda Balon 4x6 (4), Kursi + Sampul (150), Pentas Orgen, Blower (3), Full Karpet, Kain Dinding, Meja Bulat (2), Meja Hidangan (2), Meja Pondok (2), Meja Tamu (2), Pintu Masuk Kerucut (1), Janur (1).\n\nATTIRE & MAKEUP: Baju Akad 1 Pasang, Baju Resepsi 2 Pasang, Baju Besan 2 Pasang, Aksesories Pengantin, Makeup Pengantin Akad + Resepsi, Makeup Touch Up Resepsi, Makeup Orang Tua (2).\n\nINCLUDE: Orgen Sound s/d 17.00 WIB, Henna, MC Akad.",
            ],
            [
                'name'        => 'Diamond I',
                'category'    => 'paket',
                'price'       => 20000000,
                'is_active'   => true,
                'image'       => 'packages/diamond_I.jpeg',
                'description' => "DEKORASI: Pelaminan + Pentas 6M, Mini Garden, Lighting Pelaminan, Dekor Kamar, Wedding Gate, Wedding Sign, Dekorasi Set Akad Nikah, Kotak Duit (2).\n\nTENDA: Tenda Semi Balon 6x6 (4), Kursi + Sampul (200), Pentas Orgen, Blower (4), Full Karpet, Full Kain Dinding, Meja Bulat (3), Meja Hidangan (2), Meja Pondok (2), Meja Tamu (2), Pintu Masuk Kerucut (2), Janur (1).\n\nATTIRE & MAKEUP: Baju Akad 1 Pasang, Baju Resepsi 2 Pasang, Baju Besan 2 Pasang, Aksesories Pengantin, Makeup Pengantin Akad + Resepsi, Makeup Touch Up Resepsi, Makeup Orang Tua (2).\n\nINCLUDE: Orgen Sound s/d 17.00 WIB, Henna, MC Akad.",
            ],
            [
                'name'        => 'Diamond II',
                'category'    => 'paket',
                'price'       => 22000000,
                'is_active'   => true,
                'image'       => 'packages/diamond_II.jpeg',
                'description' => "DEKORASI: Pelaminan + Pentas 6M, Mini Garden, Lighting Pelaminan, Dekor Kamar, Wedding Gate, Wedding Sign, Dekorasi Set Akad Nikah, Kotak Duit (2).\n\nTENDA: Tenda Balon 6x6 (4), Kursi + Sampul (200), Pentas Orgen, Blower (4), Full Karpet, Full Kain Dinding, Meja Bulat (3), Meja Hidangan (2), Meja Pondok (2), Meja Tamu (2), Pintu Masuk Kerucut (2), Janur (1).\n\nATTIRE & MAKEUP: Baju Akad 1 Pasang, Baju Resepsi 2 Pasang, Baju Besan 2 Pasang, Aksesories Pengantin, Makeup Pengantin Akad + Resepsi, Makeup Touch Up Resepsi, Makeup Orang Tua (2).\n\nINCLUDE: Orgen Sound s/d 17.00 WIB, Henna, MC Akad.",
            ],
        ];

        foreach ($packages as $pkg) {
            Package::firstOrCreate(
                ['name' => $pkg['name']],
                $pkg
            );
        }
    }
}