<?php

namespace Tests\Unit;

use App\Rules\Cpf;
use PHPUnit\Framework\TestCase;

class RulesTest extends TestCase
{
    public function test_cpf_rule_accepts_valid_formatted_cpf(): void
    {
        $messages = $this->validateCpf('529.982.247-25');

        $this->assertSame([], $messages);
    }

    public function test_cpf_rule_rejects_invalid_length_and_repeated_digits(): void
    {
        $this->assertSame(['O CPF informado é inválido.'], $this->validateCpf('123'));
        $this->assertSame(['O CPF informado é inválido.'], $this->validateCpf('111.111.111-11'));
    }

    public function test_cpf_rule_rejects_invalid_check_digits(): void
    {
        $this->assertSame(['Este não é um número de CPF válido.'], $this->validateCpf('529.982.247-24'));
    }

    private function validateCpf(string $value): array
    {
        $messages = [];

        (new Cpf())->validate('document', $value, function (string $message) use (&$messages): void {
            $messages[] = $message;
        });

        return $messages;
    }
}
