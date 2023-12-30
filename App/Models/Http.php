<?php

namespace App\Models;

class Http {
    public static function obtainJsonError(string $errorString) {
        $json = [
            'msg_error' => $errorString
        ];

        return json_encode($json);
    }

    public static function obtainJsonSuccess(string $successString, $content = []) {
        $json = [
            'msg_success' => $successString
        ];

        return json_encode($json);
    }
}