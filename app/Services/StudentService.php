<?php

namespace App\Services;

use App\Models\Person;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentService
{
    public function store(array $data): Student
    {
        return DB::transaction(function () use ($data) {
            // 1. Tratamento da Foto
            if (isset($data['photo'])) {
                $data['photo'] = $data['photo']->store('photos/students', 'public');
            }

            // 2. Criar a Pessoa (Os dados já vêm sanitizados do Request)
            $person = Person::create($data);

            // 3. Criar o Aluno vinculado
            $student = Student::create(array_merge($data, [
                'person_id' => $person->id,
            ]));

            // 4. Sincronizar Deficiências
            $this->syncDeficiencies($student, $data);

            return $student;
        });
    }

    public function update(Student $student, array $data): Student
    {
        return DB::transaction(function () use ($student, $data) {
            $person = $student->person;

            // Gerenciamento de Foto (Upload/Remoção)
            if (!empty($data['remove_photo']) || isset($data['photo'])) {
                if ($person->photo) {
                    Storage::disk('public')->delete($person->photo);
                }
                $data['photo'] = isset($data['photo'])
                    ? $data['photo']->store('photos/students', 'public')
                    : null;
            }

            $person->update($data);
            $student->update($data);

            $this->syncDeficiencies($student, $data);

            return $student;
        });
    }

    public function delete(Student $student): void
    {
        DB::transaction(function () use ($student) {
            $person = $student->person;

            if ($person->photo) {
                Storage::disk('public')->delete($person->photo);
            }

            $student->delete();
            $person->delete();
        });
    }

    /**
     * Sincroniza a relação Many-to-Many com Deficiências
     */
    private function syncDeficiencies(Student $student, array $data): void
    {
        if (isset($data['deficiencies'])) {
            $student->deficiencies()->sync($data['deficiencies']);
        }
    }
}
