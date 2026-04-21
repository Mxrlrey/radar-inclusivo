<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            [
                'name' => 'Ana Beatriz Oliveira',
                'document' => '10010010011',
                'birth_date' => '2013-05-12',
                'gender' => 'female',
                'email' => 'ana.beatriz@escola.exemplo.com',
                'phone' => '(77) 99910-1001',
                'address' => 'Rua das Acacias, 101 - Centro',
                'registration' => 'ALU0001',
                'entry_date' => '2024-02-05',
                'is_active' => true,
            ],
            [
                'name' => 'Bruno Henrique Souza',
                'document' => '20020020022',
                'birth_date' => '2012-08-25',
                'gender' => 'male',
                'email' => 'bruno.henrique@escola.exemplo.com',
                'phone' => '(77) 99910-1002',
                'address' => 'Rua das Palmeiras, 88 - Sao Jose',
                'registration' => 'ALU0002',
                'entry_date' => '2023-02-13',
                'is_active' => true,
            ],
            [
                'name' => 'Carla Dias Martins',
                'document' => '30030030033',
                'birth_date' => '2014-01-15',
                'gender' => 'female',
                'email' => 'carla.dias@escola.exemplo.com',
                'phone' => '(77) 99910-1003',
                'address' => 'Travessa Primavera, 45 - Alto da Boa Vista',
                'registration' => 'ALU0003',
                'entry_date' => '2024-03-04',
                'is_active' => true,
            ],
            [
                'name' => 'Daniel Ferreira Lima',
                'document' => '40040040044',
                'birth_date' => '2011-11-30',
                'gender' => 'male',
                'email' => 'daniel.ferreira@escola.exemplo.com',
                'phone' => '(77) 99910-1004',
                'address' => 'Rua do Colegio, 230 - Centro',
                'registration' => 'ALU0004',
                'entry_date' => '2022-02-14',
                'is_active' => true,
            ],
            [
                'name' => 'Eduarda Costa Silva',
                'document' => '50050050055',
                'birth_date' => '2013-03-22',
                'gender' => 'female',
                'email' => 'eduarda.costa@escola.exemplo.com',
                'phone' => '(77) 99910-1005',
                'address' => 'Avenida Principal, 500 - Sao Geraldo',
                'registration' => 'ALU0005',
                'entry_date' => '2023-07-17',
                'is_active' => true,
            ],
            [
                'name' => 'Felipe Augusto Rocha',
                'document' => '60060060066',
                'birth_date' => '2012-07-08',
                'gender' => 'male',
                'email' => 'felipe.augusto@escola.exemplo.com',
                'phone' => '(77) 99910-1006',
                'address' => 'Rua do Mercado, 67 - Centro',
                'registration' => 'ALU0006',
                'entry_date' => '2023-02-06',
                'is_active' => true,
            ],
            [
                'name' => 'Giovanna Mendes Vaz',
                'document' => '70070070077',
                'birth_date' => '2014-09-18',
                'gender' => 'female',
                'email' => 'giovanna.mendes@escola.exemplo.com',
                'phone' => '(77) 99910-1007',
                'address' => 'Rua das Flores, 170 - Jardim das Acacias',
                'registration' => 'ALU0007',
                'entry_date' => '2024-02-19',
                'is_active' => true,
            ],
            [
                'name' => 'Hugo Leonardo Gomes',
                'document' => '80080080088',
                'birth_date' => '2010-12-05',
                'gender' => 'male',
                'email' => 'hugo.leonardo@escola.exemplo.com',
                'phone' => '(77) 99910-1008',
                'address' => 'Rua da Feira, 15 - Vila Nova',
                'registration' => 'ALU0008',
                'entry_date' => '2021-08-09',
                'is_active' => true,
            ],
            [
                'name' => 'Isabela Santos Reis',
                'document' => '90090090099',
                'birth_date' => '2013-10-10',
                'gender' => 'female',
                'email' => 'isabela.santos@escola.exemplo.com',
                'phone' => '(77) 99910-1009',
                'address' => 'Rua do Cruzeiro, 91 - Santa Rita',
                'registration' => 'ALU0009',
                'entry_date' => '2023-01-30',
                'is_active' => true,
            ],
            [
                'name' => 'Joao Pedro Almeida',
                'document' => '11011011011',
                'birth_date' => '2012-02-28',
                'gender' => 'male',
                'email' => 'joao.pedro@escola.exemplo.com',
                'phone' => '(77) 99910-1010',
                'address' => 'Rua Dois de Julho, 302 - Centro',
                'registration' => 'ALU0010',
                'entry_date' => '2022-03-07',
                'is_active' => true,
            ],
            [
                'name' => 'Larissa Nunes Rocha',
                'document' => '12012012012',
                'birth_date' => '2015-04-11',
                'gender' => 'female',
                'email' => 'larissa.nunes@escola.exemplo.com',
                'phone' => '(77) 99910-1011',
                'address' => 'Avenida do Estudante, 12 - Ipiranga',
                'registration' => 'ALU0011',
                'entry_date' => '2024-06-03',
                'is_active' => true,
            ],
            [
                'name' => 'Mateus Oliveira Prado',
                'document' => '13013013013',
                'birth_date' => '2011-06-19',
                'gender' => 'male',
                'email' => 'mateus.oliveira@escola.exemplo.com',
                'phone' => '(77) 99910-1012',
                'address' => 'Rua da Biblioteca, 75 - Sao Francisco',
                'registration' => 'ALU0012',
                'entry_date' => '2022-07-25',
                'is_active' => true,
            ],
        ];

        foreach ($students as $studentData) {
            $person = Person::updateOrCreate(
                ['document' => $studentData['document']],
                [
                    'name' => $studentData['name'],
                    'birth_date' => Carbon::parse($studentData['birth_date'])->format('Y-m-d'),
                    'gender' => $studentData['gender'],
                    'email' => $studentData['email'],
                    'phone' => $studentData['phone'],
                    'address' => $studentData['address'],
                ]
            );

            Student::updateOrCreate(
                ['registration' => $studentData['registration']],
                [
                    'person_id' => $person->id,
                    'entry_date' => Carbon::parse($studentData['entry_date'])->format('Y-m-d'),
                    'is_active' => $studentData['is_active'],
                ]
            );
        }

        $this->command->info(count($students) . ' alunos criados/atualizados com sucesso.');
    }
}
