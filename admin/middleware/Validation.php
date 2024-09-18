<?php

class Validator
{
    public function validate($data, $rules)
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $fieldValue = $data[$field] ?? null;

            if (strpos($rule, 'required') !== false && empty($fieldValue)) {
                $errors[$field] = ucfirst($field) . ' is required';
            }

            if (strpos($rule, 'min:') !== false) {
                $minValue = explode(':', $rule)[1];
                if (strlen($fieldValue) < $minValue) {
                    $errors[$field] = ucfirst($field) . ' must be at least ' . $minValue . ' characters long';
                }
            }

            if (strpos($rule, 'email') !== false && !filter_var($fieldValue, FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = ucfirst($field) . ' must be a valid email';
            }

            if (strpos($rule, 'integer') !== false && !is_numeric($fieldValue)) {
                $errors[$field] = ucfirst($field) . ' must be an integer';
            }
        }

        return $errors;
    }
}