<?php

namespace App\Services;

use App\Models\Log;
use Carbon\Carbon;
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
    const CHANNEL_AUTH = 'Autenticacion';
    const CHANNEL_USER_MANAGEMENT = 'Gestion de Usuarios';
    const CHANNEL_ROLE_PERMISSION = 'Roles y Permisos';
    const CHANNEL_SYSTEM = 'Sistema';
    const CHANNEL_API = 'API';

    /**
     * Business area channels
     */
    const CHANNEL_VENTAS = 'Ventas';
    const CHANNEL_NOMINA = 'Nomina';
    const CHANNEL_CONTABILIDAD = 'Contabilidad';

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

    /**
     * Log ventas area activities
     */
    public static function ventas(string $action, array $data = [], array $context = []): Log
    {
        $messages = [
            'created' => 'Registro de ventas creado',
            'updated' => 'Registro de ventas actualizado',
            'deleted' => 'Registro de ventas eliminado',
            'imported' => 'Datos de ventas importados',
            'exported' => 'Datos de ventas exportados',
        ];

        $message = $messages[$action] ?? "Acción en ventas: {$action}";
        
        // Agregar información del periodo si está disponible
        if (isset($data['periodo_inicio'])) {
            $periodo = Carbon::parse($data['periodo_inicio'])->format('M Y');
            $message .= " - Periodo: {$periodo}";
        }

        return self::log(
            self::LEVEL_INFO,
            self::CHANNEL_VENTAS,
            $message,
            array_merge([
                'action' => $action,
                'data' => $data,
            ], $context)
        );
    }

    /**
     * Log nomina area activities
     */
    public static function nomina(string $action, array $data = [], array $context = []): Log
    {
        $messages = [
            'created' => 'Registro de nómina creado',
            'updated' => 'Registro de nómina actualizado',
            'deleted' => 'Registro de nómina eliminado',
            'processed' => 'Nómina procesada',
            'exported' => 'Nómina exportada',
        ];

        $message = $messages[$action] ?? "Acción en nómina: {$action}";

        return self::log(
            self::LEVEL_INFO,
            self::CHANNEL_NOMINA,
            $message,
            array_merge([
                'action' => $action,
                'data' => $data,
            ], $context)
        );
    }

    /**
     * Log contabilidad area activities
     */
    public static function contabilidad(string $action, array $data = [], array $context = []): Log
    {
        $messages = [
            'created' => 'Registro contable creado',
            'updated' => 'Registro contable actualizado',
            'deleted' => 'Registro contable eliminado',
            'reconciled' => 'Cuenta conciliada',
            'report_generated' => 'Reporte contable generado',
        ];

        $message = $messages[$action] ?? "Acción en contabilidad: {$action}";

        return self::log(
            self::LEVEL_INFO,
            self::CHANNEL_CONTABILIDAD,
            $message,
            array_merge([
                'action' => $action,
                'data' => $data,
            ], $context)
        );
    }
}