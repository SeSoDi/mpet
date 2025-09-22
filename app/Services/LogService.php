<?php

namespace App\Services;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LogService
{
    /**
     * Log levels
     */
    const LEVEL_DEBUG = 'DEBUG';
    const LEVEL_INFO = 'INFO';
    const LEVEL_WARNING = 'WARNING';
    const LEVEL_ERROR = 'ERROR';
    const LEVEL_CRITICAL = 'CRITICAL';

    /**
     * Common channels
     */
    const CHANNEL_AUTH = 'auth';
    const CHANNEL_USER_MANAGEMENT = 'user_management';
    const CHANNEL_ROLE_PERMISSION = 'role_permission';
    const CHANNEL_SYSTEM = 'system';
    const CHANNEL_API = 'api';

    /**
     * Log an action with context
     */
    public static function log(
        string $level,
        string $channel,
        string $message,
        array $context = [],
        ?int $userId = null,
        ?string $userName = null
    ): Log {
        // Get current user if not provided
        if ($userId === null && Auth::check()) {
            $userId = Auth::id();
            $userName = Auth::user()->name;
        }

        // Add request context if available
        if (app()->bound('request')) {
            $request = app('request');
            $context = array_merge([
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ], $context);
        }

        return Log::create([
            'level' => $level,
            'channel' => $channel,
            'message' => $message,
            'context' => $context,
            'user_id' => $userId,
            'user_name' => $userName,
            'logged_at' => now(),
        ]);
    }

    /**
     * Log user authentication events
     */
    public static function auth(string $action, ?string $email = null, array $context = []): Log
    {
        $messages = [
            'login' => 'Usuario inició sesión',
            'logout' => 'Usuario cerró sesión',
            'failed_login' => 'Intento de inicio de sesión fallido',
            'register' => 'Usuario se registró',
            'password_reset' => 'Usuario solicitó restablecimiento de contraseña',
            'password_changed' => 'Usuario cambió su contraseña',
            'two_factor_initiated' => 'Autenticación de dos factores iniciada',
        ];

        $message = $messages[$action] ?? "Acción de autenticación: {$action}";
        
        if ($email) {
            $message .= " - {$email}";
        }

        return self::log(
            self::LEVEL_INFO,
            self::CHANNEL_AUTH,
            $message,
            array_merge(['action' => $action], $context)
        );
    }

    /**
     * Log user management events
     */
    public static function userAction(string $action, $targetUser, array $context = []): Log
    {
        $messages = [
            'created' => 'Usuario creado',
            'updated' => 'Usuario actualizado',
            'deleted' => 'Usuario eliminado',
            'restored' => 'Usuario restaurado',
            'role_assigned' => 'Rol asignado al usuario',
            'role_removed' => 'Rol removido del usuario',
        ];

        $message = $messages[$action] ?? "Acción en usuario: {$action}";
        $userName = is_object($targetUser) ? $targetUser->name : $targetUser;
        $userId = is_object($targetUser) ? $targetUser->id : null;
        
        $message .= " - {$userName}";

        return self::log(
            self::LEVEL_INFO,
            self::CHANNEL_USER_MANAGEMENT,
            $message,
            array_merge([
                'action' => $action,
                'target_user_id' => $userId,
                'target_user_name' => $userName,
            ], $context)
        );
    }

    /**
     * Log system errors
     */
    public static function error(string $message, \Throwable $exception = null, array $context = []): Log
    {
        if ($exception) {
            $context = array_merge([
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ], $context);
        }

        return self::log(
            self::LEVEL_ERROR,
            self::CHANNEL_SYSTEM,
            $message,
            $context
        );
    }

    /**
     * Log info messages
     */
    public static function info(string $channel, string $message, array $context = []): Log
    {
        return self::log(self::LEVEL_INFO, $channel, $message, $context);
    }

    /**
     * Log warning messages
     */
    public static function warning(string $channel, string $message, array $context = []): Log
    {
        return self::log(self::LEVEL_WARNING, $channel, $message, $context);
    }

    /**
     * Log debug messages
     */
    public static function debug(string $channel, string $message, array $context = []): Log
    {
        return self::log(self::LEVEL_DEBUG, $channel, $message, $context);
    }

    /**
     * Log critical messages
     */
    public static function critical(string $channel, string $message, array $context = []): Log
    {
        return self::log(self::LEVEL_CRITICAL, $channel, $message, $context);
    }
}