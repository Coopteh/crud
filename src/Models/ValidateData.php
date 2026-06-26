<?php

namespace App\Models;

class ValidateData
{
    /**
     * Валидирует данные записи.
     *
     * @param array $data Ассоциативный массив [поле => значение]
     * @return array Массив ошибок. Пустой массив = валидация пройдена.
     */
    public function validate(array $data): array
    {
        $errors = [];

        // 1. Валидация названия события
        $errors = array_merge($errors, $this->validateEventName($data['name'] ?? null));

        // 2. Валидация даты события
        $errors = array_merge($errors, $this->validateEventDate($data['date'] ?? null));

        return $errors;
    }

    /**
     * Название события: от 3 до 200 символов.
     */
    private function validateEventName(mixed $value): array
    {
        $errors = [];

        if ($value === null || $value === '') {
            $errors['name'] = 'Название события обязательно для заполнения.';
            return $errors;
        }

        if (!is_string($value)) {
            $errors['name'] = 'Название события должно быть строкой.';
            return $errors;
        }

        $length = mb_strlen($value, 'UTF-8');

        if ($length < 3) {
            $errors['name'] = 'Название события должно содержать не менее 3 символов.';
        } elseif ($length > 200) {
            $errors['name'] = 'Название события должно содержать не более 200 символов.';
        }

        return $errors;
    }

    /**
     * Дата события: год от 2000 до 2200.
     */
    private function validateEventDate(mixed $value): array
    {
        $errors = [];

        if ($value === null || $value === '') {
            $errors['date'] = 'Дата события обязательна для заполнения.';
            return $errors;
        }

        // Пробуем распарсить дату
        $timestamp = strtotime((string)$value);

        if ($timestamp === false) {
            $errors['date'] = 'Дата события имеет неверный формат.';
            return $errors;
        }

        $year = (int)date('Y', $timestamp);

        if ($year < 2000) {
            $errors['date'] = 'Год события не может быть ранее 2000.';
        } elseif ($year > 2200) {
            $errors['date'] = 'Год события не может быть позже 2200.';
        }

        return $errors;
    }
}