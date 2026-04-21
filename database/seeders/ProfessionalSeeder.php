<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\Position;
use App\Models\Professional;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProfessionalSeeder extends Seeder
{
    public function run(): void
    {
        $this->ensurePositionsExist();

        $professionals = [
            [
                'name' => 'Adriany Oliveira',
                'document' => '41041041041',
                'birth_date' => '1990-05-10',
                'gender' => 'female',
                'email' => 'adriany.oliveira@radar.exemplo.com',
                'phone' => '(77) 99820-1001',
                'address' => 'Rua do Colegio, 10 - Centro',
                'position' => 'Professor AEE',
                'registration' => 'PROF0001',
                'entry_date' => '2021-02-01',
                'is_active' => true,
                'is_admin' => false,
            ],
            [
                'name' => 'Joao Santos',
                'document' => '42042042042',
                'birth_date' => '1988-08-22',
                'gender' => 'male',
                'email' => 'joao.santos@radar.exemplo.com',
                'phone' => '(77) 99820-1002',
                'address' => 'Rua Sete de Setembro, 85 - Centro',
                'position' => 'Coordenador do NAPNE',
                'registration' => 'PROF0002',
                'entry_date' => '2020-03-15',
                'is_active' => true,
                'is_admin' => true,
            ],
            [
                'name' => 'Paula Mendes',
                'document' => '43043043043',
                'birth_date' => '1992-11-30',
                'gender' => 'female',
                'email' => 'paula.mendes@radar.exemplo.com',
                'phone' => '(77) 99820-1003',
                'address' => 'Avenida da Educacao, 420 - Sao Jose',
                'position' => 'Psicólogo',
                'registration' => 'PROF0003',
                'entry_date' => '2022-01-10',
                'is_active' => true,
                'is_admin' => false,
            ],
            [
                'name' => 'Ricardo Alves',
                'document' => '44044044044',
                'birth_date' => '1987-02-14',
                'gender' => 'male',
                'email' => 'ricardo.alves@radar.exemplo.com',
                'phone' => '(77) 99820-1004',
                'address' => 'Rua Nova, 118 - Ipiranga',
                'position' => 'Assistente Social',
                'registration' => 'PROF0004',
                'entry_date' => '2021-08-02',
                'is_active' => true,
                'is_admin' => false,
            ],
            [
                'name' => 'Camila Pitanga',
                'document' => '45045045045',
                'birth_date' => '1991-09-18',
                'gender' => 'female',
                'email' => 'camila.pitanga@radar.exemplo.com',
                'phone' => '(77) 99820-1005',
                'address' => 'Rua das Flores, 201 - Primavera',
                'position' => 'Intérprete de Libras',
                'registration' => 'PROF0005',
                'entry_date' => '2023-02-06',
                'is_active' => true,
                'is_admin' => false,
            ],
            [
                'name' => 'Marcos Palmeira',
                'document' => '46046046046',
                'birth_date' => '1985-04-03',
                'gender' => 'male',
                'email' => 'marcos.palmeira@radar.exemplo.com',
                'phone' => '(77) 99820-1006',
                'address' => 'Avenida da Serra, 509 - Bela Vista',
                'position' => 'Terapeuta Ocupacional',
                'registration' => 'PROF0006',
                'entry_date' => '2020-07-20',
                'is_active' => true,
                'is_admin' => false,
            ],
            [
                'name' => 'Fernanda Lima',
                'document' => '47047047047',
                'birth_date' => '1989-12-08',
                'gender' => 'female',
                'email' => 'fernanda.lima@radar.exemplo.com',
                'phone' => '(77) 99820-1007',
                'address' => 'Rua do Sol, 142 - Centro',
                'position' => 'Fonoaudiólogo',
                'registration' => 'PROF0007',
                'entry_date' => '2022-09-12',
                'is_active' => true,
                'is_admin' => false,
            ],
            [
                'name' => 'Mariana Ximenes',
                'document' => '48048048048',
                'birth_date' => '1993-07-27',
                'gender' => 'female',
                'email' => 'mariana.ximenes@radar.exemplo.com',
                'phone' => '(77) 99820-1008',
                'address' => 'Rua da Matriz, 33 - Centro',
                'position' => 'Professor AEE',
                'registration' => 'PROF0008',
                'entry_date' => '2024-01-22',
                'is_active' => true,
                'is_admin' => false,
            ],
        ];

        foreach ($professionals as $professionalData) {
            $position = Position::where('name', $professionalData['position'])->first();

            if (!$position) {
                throw new \RuntimeException("Cargo nao encontrado para o seeder de profissionais: {$professionalData['position']}");
            }

            $person = Person::updateOrCreate(
                ['document' => $professionalData['document']],
                [
                    'name' => $professionalData['name'],
                    'birth_date' => $professionalData['birth_date'],
                    'gender' => $professionalData['gender'],
                    'email' => $professionalData['email'],
                    'phone' => $professionalData['phone'],
                    'address' => $professionalData['address'],
                ]
            );

            $professional = Professional::updateOrCreate(
                ['registration' => $professionalData['registration']],
                [
                    'person_id' => $person->id,
                    'position_id' => $position->id,
                    'entry_date' => $professionalData['entry_date'],
                    'is_active' => $professionalData['is_active'],
                ]
            );

            User::updateOrCreate(
                ['email' => $professionalData['email']],
                [
                    'name' => $professionalData['name'],
                    'professional_id' => $professional->id,
                    'password' => 'napne2026',
                    'is_admin' => $professionalData['is_admin'],
                    'is_active' => $professionalData['is_active'],
                ]
            );
        }

        $this->command->info(count($professionals) . ' profissionais criados/atualizados com sucesso.');
    }

    private function ensurePositionsExist(): void
    {
        if (Position::count() === 0) {
            $this->call(PositionSeeder::class);
        }
    }
}
