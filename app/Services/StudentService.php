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

            $person = Person::create(
                $this->makePersonData($data)
            );

            $student = Student::create(
                $this->makeStudentData($data, $person)
            );

            return $student;
        });
    }

    public function update(Student $student, array $data): Student
    {
        return DB::transaction(function () use ($student, $data) {
            $person = $student->person;

            $personData = $this->makePersonData($data, $person);
            $studentData = $this->makeStudentData($data, $person, $student);

            $person->update($personData);
            $student->update($studentData);

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


    private function makePersonData(array $data, ?Person $person = null): array
    {
        $personData = [
            'name' => $data['name'] ?? $person?->name,
            'email' => $data['email'] ?? $person?->email,
            'document' => $data['document'] ?? $person?->document,
            'birth_date' => $data['birth_date'] ?? $person?->birth_date,
            'gender' => $data['gender'] ?? $person?->gender,
            'phone' => $data['phone'] ?? $person?->phone,
            'address' => $data['address'] ?? $person?->address,
        ];

        // upload de foto
        if (!empty($data['photo'])) {
            if ($person?->photo) {
                Storage::disk('public')->delete($person->photo);
            }

            $personData['photo'] = $data['photo']->store('photos/students', 'public');
        }

        if (!empty($data['remove_photo'])) {
            if ($person?->photo) {
                Storage::disk('public')->delete($person->photo);
            }

            $personData['photo'] = null;
        }

        return $personData;
    }

    private function makeStudentData(array $data, Person $person, ?Student $student = null): array
    {
        return [
            'person_id' => $person->id,
            'registration' => $data['registration'] ?? $student?->registration,
            'entry_date' => $data['entry_date'] ?? $student?->entry_date,
            'is_active' => $data['is_active'] ?? $student?->is_active ?? true,
        ];
    }
}
