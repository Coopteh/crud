<?php

namespace Tests;

use App\Models\ValidateData;
use PHPUnit\Framework\TestCase;

class ValidateDataTest extends TestCase
{
    private ValidateData $validator;

    protected function setUp(): void
    {
        $this->validator = new ValidateData();
    }

    // ==========================================================
    //  Тесты для названия события (name)
    // ==========================================================

    public function testValidName(): void
    {
        $data = [
            'name' => 'Конференция разработчиков',
            'date' => '2024-05-15',
        ];

        $errors = $this->validator->validate($data);
        $this->assertEmpty($errors, 'Валидные данные не должны давать ошибок.');
    }

    public function testNameTooShort(): void
    {
        $data = [
            'name' => 'ab',  // 2 символа — меньше 3
            'date' => '2024-05-15',
        ];

        $errors = $this->validator->validate($data);

        $this->assertArrayHasKey('name', $errors);
        $this->assertStringContainsString('не менее 3', $errors['name']);
    }

    public function testNameExactlyThreeChars(): void
    {
        $data = [
            'name' => 'abc',  // ровно 3 — должно пройти
            'date' => '2024-05-15',
        ];

        $errors = $this->validator->validate($data);
        $this->assertArrayNotHasKey('name', $errors);
    }

    public function testNameTooLong(): void
    {
        $data = [
            'name' => str_repeat('a', 201),  // 201 символ — больше 200
            'date' => '2024-05-15',
        ];

        $errors = $this->validator->validate($data);

        $this->assertArrayHasKey('name', $errors);
        $this->assertStringContainsString('не более 200', $errors['name']);
    }

    public function testNameExactly200Chars(): void
    {
        $data = [
            'name' => str_repeat('a', 200),  // ровно 200 — должно пройти
            'date' => '2024-05-15',
        ];

        $errors = $this->validator->validate($data);
        $this->assertArrayNotHasKey('name', $errors);
    }

    public function testNameIsEmpty(): void
    {
        $data = [
            'name' => '',
            'date' => '2024-05-15',
        ];

        $errors = $this->validator->validate($data);
        $this->assertArrayHasKey('name', $errors);
    }

    public function testNameIsMissing(): void
    {
        $data = [
            'date' => '2024-05-15',
        ];

        $errors = $this->validator->validate($data);
        $this->assertArrayHasKey('name', $errors);
    }

    // ==========================================================
    //  Тесты для даты события (date)
    // ==========================================================

    public function testValidDate(): void
    {
        $data = [
            'name' => 'Конференция',
            'date' => '2024-05-15',
        ];

        $errors = $this->validator->validate($data);
        $this->assertEmpty($errors);
    }

    public function testDateYear2000(): void
    {
        $data = [
            'name' => 'Конференция',
            'date' => '2000-01-01',  // минимальный год
        ];

        $errors = $this->validator->validate($data);
        $this->assertArrayNotHasKey('date', $errors);
    }

    public function testDateYear2200(): void
    {
        $data = [
            'name' => 'Конференция',
            'date' => '2200-12-31',  // максимальный год
        ];

        $errors = $this->validator->validate($data);
        $this->assertArrayNotHasKey('date', $errors);
    }

    public function testDateYearTooEarly(): void
    {
        $data = [
            'name' => 'Конференция',
            'date' => '1999-12-31',  // на 1 год раньше допустимого
        ];

        $errors = $this->validator->validate($data);

        $this->assertArrayHasKey('date', $errors);
        $this->assertStringContainsString('не может быть ранее 2000', $errors['date']);
    }

    public function testDateYearTooLate(): void
    {
        $data = [
            'name' => 'Конференция',
            'date' => '2201-01-01',  // на 1 год позже допустимого
        ];

        $errors = $this->validator->validate($data);

        $this->assertArrayHasKey('date', $errors);
        $this->assertStringContainsString('не может быть позже 2200', $errors['date']);
    }

    public function testDateInvalidFormat(): void
    {
        $data = [
            'name' => 'Конференция',
            'date' => 'not-a-date',
        ];

        $errors = $this->validator->validate($data);
        $this->assertArrayHasKey('date', $errors);
    }

    public function testDateIsEmpty(): void
    {
        $data = [
            'name' => 'Конференция',
            'date' => '',
        ];

        $errors = $this->validator->validate($data);
        $this->assertArrayHasKey('date', $errors);
    }

    public function testDateIsMissing(): void
    {
        $data = [
            'name' => 'Конференция',
        ];

        $errors = $this->validator->validate($data);
        $this->assertArrayHasKey('date', $errors);
    }

    // ==========================================================
    //  Комплексные тесты
    // ==========================================================

    public function testBothFieldsInvalid(): void
    {
        $data = [
            'name' => 'a',           // слишком короткое
            'date' => '1800-01-01',  // слишком ранний год
        ];

        $errors = $this->validator->validate($data);

        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('date', $errors);
        $this->assertCount(2, $errors);
    }

    public function testEmptyData(): void
    {
        $errors = $this->validator->validate([]);

        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('date', $errors);
    }

    public function testUnicodeNameLength(): void
    {
        // Кириллица: "Привет" = 6 символов (не байт!)
        $data = [
            'name' => 'Привет',
            'date' => '2024-05-15',
        ];

        $errors = $this->validator->validate($data);
        $this->assertArrayNotHasKey('name', $errors, 'Длина кириллической строки должна считаться по символам, а не по байтам.');
    }
}