<?php
namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Paket Silver',
                'category' => 'basic',
                'description' => 'Paket ekonomis untuk pernikahan sederhana namun tetap berkesan.',
                'price' => 8000000,
                'max_guests' => 100,
                'includes' => [
                    'Rias pengantin 1 kali',
                    'Dekorasi pelaminan sederhana',
                    'Busana pengantin 1 set',
                    'Dokumentasi foto 2 jam',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Paket Gold',
                'category' => 'standard',
                'description' => 'Paket terlaris dengan layanan lengkap untuk pernikahan impian Anda.',
                'price' => 15000000,
                'max_guests' => 200,
                'includes' => [
                    'Rias pengantin 2 kali (akad + resepsi)',
                    'Dekorasi pelaminan modern',
                    'Busana pengantin 2 set',
                    'Dokumentasi foto & video 5 jam',
                    'Undangan digital 100 pcs',
                    'Wedding coordinator hari H',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Paket Platinum',
                'category' => 'premium',
                'description' => 'Paket premium all-inclusive untuk pernikahan mewah dan tak terlupakan.',
                'price' => 28000000,
                'max_guests' => 500,
                'includes' => [
                    'Rias pengantin full day',
                    'Dekorasi pelaminan premium',
                    'Busana pengantin 3 set',
                    'Dokumentasi foto & video full day',
                    'Undangan fisik + digital 200 pcs',
                    'Wedding coordinator 2 hari',
                    'Photo booth',
                    'Souvenir 100 pcs',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($packages as $pkg) {
            Package::create($pkg);
        }
    }
}