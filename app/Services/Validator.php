<?php

namespace App\Services;

use DateTime;
use PDO;

class Validator
{
    private array $data;
    private array $rules;
    private array $errors = [];
    private PDO $db;

    public function __construct(array $data, array $rules, ?PDO $db = null)
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->db = $db ?? Database::getConnection();
    }

    public static function make(array $data, array $rules, ?PDO $db = null): self
    {
        $validator = new self($data, $rules, $db);
        $validator->validate();
        return $validator;
    }

    public function validate(): bool
    {
        $this->errors = [];

        foreach ($this->rules as $field => $fieldRules) {
            $rulesArray = is_array($fieldRules) ? $fieldRules : explode('|', $fieldRules);
            $value = $this->data[$field] ?? null;

            foreach ($rulesArray as $ruleDefinition) {
                $ruleParts = explode(':', $ruleDefinition, 2);
                $rule = $ruleParts[0];
                $param = $ruleParts[1] ?? null;

                $this->applyRule($field, $value, $rule, $param);

                // If field is not required and empty, skip other rules
                if ($rule === 'required' && isset($this->errors[$field])) {
                    break;
                }
                if ($value === null || $value === '') {
                    if ($rule !== 'required') {
                        continue;
                    }
                }
            }
        }

        return empty($this->errors);
    }

    private function applyRule(string $field, $value, string $rule, ?string $param): void
    {
        switch ($rule) {
            case 'required':
                if ($value === null || (is_string($value) && trim($value) === '') || (is_array($value) && empty($value))) {
                    $this->addError($field, "Bidang " . $this->formatFieldName($field) . " wajib diisi.");
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "Format " . $this->formatFieldName($field) . " tidak valid.");
                }
                break;

            case 'min':
                $min = (int)$param;
                if (!empty($value)) {
                    if (is_numeric($value) && (float)$value < $min) {
                        $this->addError($field, "Nilai " . $this->formatFieldName($field) . " minimal {$min}.");
                    } elseif (is_string($value) && mb_strlen($value) < $min) {
                        $this->addError($field, "Panjang " . $this->formatFieldName($field) . " minimal {$min} karakter.");
                    }
                }
                break;

            case 'max':
                $max = (int)$param;
                if (!empty($value)) {
                    if (is_numeric($value) && (float)$value > $max) {
                        $this->addError($field, "Nilai " . $this->formatFieldName($field) . " maksimal {$max}.");
                    } elseif (is_string($value) && mb_strlen($value) > $max) {
                        $this->addError($field, "Panjang " . $this->formatFieldName($field) . " maksimal {$max} karakter.");
                    }
                }
                break;

            case 'same':
                $otherValue = $this->data[$param] ?? null;
                if ($value !== $otherValue) {
                    $this->addError($field, "Konfirmasi " . $this->formatFieldName($field) . " tidak cocok dengan " . $this->formatFieldName($param) . ".");
                }
                break;

            case 'in':
                $allowed = explode(',', $param);
                if (!empty($value) && !in_array((string)$value, $allowed, true)) {
                    $this->addError($field, "Pilihan " . $this->formatFieldName($field) . " tidak valid.");
                }
                break;

            case 'date':
                if (!empty($value)) {
                    $d = DateTime::createFromFormat('Y-m-d', $value);
                    if (!$d || $d->format('Y-m-d') !== $value) {
                        $this->addError($field, "Format tanggal pada " . $this->formatFieldName($field) . " harus YYYY-MM-DD.");
                    }
                }
                break;

            case 'time':
                if (!empty($value)) {
                    $match = preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9](?::[0-5][0-9])?$/', $value);
                    if (!$match) {
                        $this->addError($field, "Format waktu pada " . $this->formatFieldName($field) . " tidak valid (JJ:MM).");
                    }
                }
                break;

            case 'url':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->addError($field, "Format URL pada " . $this->formatFieldName($field) . " tidak valid.");
                }
                break;

            case 'integer':
                if (!empty($value) && filter_var($value, FILTER_VALIDATE_INT) === false) {
                    $this->addError($field, "Bidang " . $this->formatFieldName($field) . " harus berupa bilangan bulat.");
                }
                break;

            case 'slug':
                if (!empty($value) && !preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value)) {
                    $this->addError($field, "Format " . $this->formatFieldName($field) . " hanya boleh huruf kecil, angka, dan tanda hubung (-).");
                }
                break;

            case 'unique':
                // Format: unique:table,column[,ignore_id[,id_column]]
                $params = explode(',', $param);
                $table = $params[0];
                $column = $params[1] ?? $field;
                $ignoreId = $params[2] ?? null;
                $idColumn = $params[3] ?? 'id';

                if (!empty($value)) {
                    $sql = "SELECT COUNT(*) FROM `{$table}` WHERE `{$column}` = :val";
                    $bindings = ['val' => $value];

                    if ($ignoreId !== null && $ignoreId !== '' && $ignoreId !== 'NULL') {
                        $sql .= " AND `{$idColumn}` != :ignore_id";
                        $bindings['ignore_id'] = $ignoreId;
                    }

                    $stmt = $this->db->prepare($sql);
                    $stmt->execute($bindings);
                    if ((int)$stmt->fetchColumn() > 0) {
                        $this->addError($field, $this->formatFieldName($field) . " sudah digunakan.");
                    }
                }
                break;
        }
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    private function formatFieldName(string $field): string
    {
        $replacements = [
            'name' => 'nama',
            'email' => 'email',
            'password' => 'kata sandi',
            'password_confirmation' => 'konfirmasi kata sandi',
            'title' => 'judul',
            'slug' => 'slug URL',
            'partner_one_name' => 'nama pasangan pertama',
            'partner_two_name' => 'nama pasangan kedua',
            'event_date' => 'tanggal acara',
            'event_time' => 'waktu acara',
            'venue_name' => 'nama tempat',
            'guest_name' => 'nama tamu',
            'guest_count' => 'jumlah tamu',
            'attendance_status' => 'status kehadiran',
        ];

        return $replacements[$field] ?? str_replace('_', ' ', $field);
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(?string $field = null): ?string
    {
        if ($field !== null) {
            return $this->errors[$field][0] ?? null;
        }

        foreach ($this->errors as $messages) {
            if (!empty($messages[0])) {
                return $messages[0];
            }
        }

        return null;
    }
}
