<?php

echo json_encode([
    'error' => [
        'code' => $code,
        'message' => $message,
        'debug' => [
            'name' => $name,
            'message' => $message,
            'traces' => $traces,
        ],
    ]]);
