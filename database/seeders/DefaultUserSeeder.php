<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // List of users to be created
        $users = [
            [
                'prenom' => 'Karima',
                'nom' => 'ANIA',
                'email' => 'kania@tourisme.gov.ma',
                'password' => 'KaAn@2025',
                'role' => 'Administrateur'
            ],
            [
                'prenom' => 'Chaimae',
                'nom' => 'EMRAN',
                'email' => 'cemran@tourisme.gov.ma',
                'password' => 'ChEm@2025',
                'role' => 'Économe'
            ],
            [
                'prenom' => 'Jaouad',
                'nom' => 'BOUNIF',
                'email' => 'jbounif@tourisme.gov.ma',
                'password' => 'JaBo@2025',
                'role' => 'Gestionnaire'
            ],
            [
                'prenom' => 'Leila',
                'nom' => 'SAIED',
                'email' => 'leilasajed17@gmail.com',
                'password' => 'LeSa@2025',
                'role' => 'Économe'
            ],
            [
                'prenom' => 'Fatima',
                'nom' => 'CABOUR',
                'email' => 'fatimacabour@gmail.com',
                'password' => 'FaCa@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Chafia',
                'nom' => 'MRIDA',
                'email' => 'cmrida@tourisme.gov.ma',
                'password' => 'ChMr@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Sellamia',
                'nom' => 'ATTI',
                'email' => 'satti@tourisme.gov.ma',
                'password' => 'SeAt@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Souad',
                'nom' => 'NASRI',
                'email' => 'snasri@tourisme.gov.ma',
                'password' => 'SoNa@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Fatima',
                'nom' => 'AFRI',
                'email' => 'fafri@tourisme.gov.ma',
                'password' => 'FaAf@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Mustapha',
                'nom' => 'LABAAJ',
                'email' => 'mlabaaj@tourisme.gov.ma',
                'password' => 'MuLa@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Hassan',
                'nom' => 'JADDOUR',
                'email' => 'hjaddour@tourisme.gov.ma',
                'password' => 'HaJa@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Mohammed',
                'nom' => 'AIT BELLA',
                'email' => 'maitbella@tourisme.gov.ma',
                'password' => 'MoAi@2025',
                'role' => 'Gestionnaire'
            ],
            [
                'prenom' => 'Hicham',
                'nom' => 'JID',
                'email' => 'hjid@tourisme.gov.ma',
                'password' => 'HiJi@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Amina',
                'nom' => 'BELBACHA',
                'email' => 'abelbacha@tourisme.gov.ma',
                'password' => 'AmBe@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Malika',
                'nom' => 'OUAOQA',
                'email' => 'mouaoqa@tourisme.gov.ma',
                'password' => 'MaOu@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Mina',
                'nom' => 'BAGHDI',
                'email' => 'mbeghdi70@gmail.com',
                'password' => 'MiBa@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Khadija',
                'nom' => 'AISSAOUI',
                'email' => 'aissaouikhadija18@gmail.com',
                'password' => 'KhAi@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Bassma',
                'nom' => 'SOUHADI',
                'email' => 'souhadibassmaa@gmail.com',
                'password' => 'BaSo@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Ihssane',
                'nom' => 'ATTIF',
                'email' => 'ihssaneattif@gmail.com',
                'password' => 'IhAt@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Fatiha',
                'nom' => 'HAIMOUDI',
                'email' => 'fatihahaimoudi@gmail.com',
                'password' => 'FaHa@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Soufian',
                'nom' => 'ZIANI',
                'email' => 'sziani40@gmail.com',
                'password' => 'SoZi@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Jamal',
                'nom' => 'SEHOUL',
                'email' => 'jamal.sehoul@gmail.com',
                'password' => 'JaSe@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Fatima',
                'nom' => 'EL AZMI',
                'email' => 'felazmi@tourisme.gov.ma',
                'password' => 'FaEl@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Zineb',
                'nom' => 'FOUGNAR',
                'email' => 'zfougnar@tourisme.gov.ma',
                'password' => 'ZiFo@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Sara',
                'nom' => 'MOUFKI',
                'email' => 'smoufki@tourisme.gov.ma',
                'password' => 'SaMo@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Hajar',
                'nom' => 'ABADA',
                'email' => 'habada@tourisme.gov.ma',
                'password' => 'HaAb@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'El Houssaine',
                'nom' => 'OUMADDOUCH',
                'email' => 'eoumaddouch@tourisme.gov.ma',
                'password' => 'OuEl@2025',
                'role' => 'Utilisateur'
            ],
            [
                'prenom' => 'Hassan',
                'nom' => 'DRIOUCH',
                'email' => 'hdriouch@tourisme.gov.ma',
                'password' => 'HaDr@2025',
                'role' => 'Utilisateur'
            ]
        ];

        // Create each user and assign role
        foreach ($users as $userData) {
            $user = User::create([
                'prenom' => $userData['prenom'],
                'nom' => $userData['nom'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password'])
            ]);
            
            $user->assignRole($userData['role']);
        }
    }
}