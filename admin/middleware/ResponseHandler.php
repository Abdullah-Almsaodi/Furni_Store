<?php

class ResponseHandler
{
    public function handleSuccess($data, $message = '')
    {
        echo json_encode([
            'success' => true,
            'data' => $data,
            'message' => $message
        ], JSON_PRETTY_PRINT);
    }

    public function handleError($errors, $message = 'Validation failed')
    {
        echo json_encode([
            'success' => false,
            'errors' => $errors['errors'],
            'message' => $message
        ], JSON_PRETTY_PRINT);
    }
}