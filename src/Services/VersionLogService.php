<?php

namespace Spinotek\TaskMonitoring\Services;

class VersionLogService
{
    /**
     * Get path to version_logs.json file.
     */
    public static function getFilePath(): string
    {
        return __DIR__ . '/../../data/version_logs.json';
    }

    /**
     * Get all version logs.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getLogs(): array
    {
        $path = self::getFilePath();

        if (!file_exists($path)) {
            return [];
        }

        $content = file_get_contents($path);
        $data = json_decode($content, true);

        return is_array($data) ? $data : [];
    }

    /**
     * Append a new version log entry.
     *
     * @param array{
     *     version: string,
     *     date?: string,
     *     author?: string,
     *     type?: string,
     *     changes: array<string>|string
     * } $data
     * @return array<string, mixed>
     */
    public static function addLog(array $data): array
    {
        $path = self::getFilePath();
        $logs = self::getLogs();

        $changes = is_array($data['changes']) 
            ? array_values(array_filter($data['changes']))
            : array_values(array_filter(array_map('trim', explode("\n", (string)$data['changes']))));

        $newEntry = [
            'version' => $data['version'],
            'date' => $data['date'] ?? date('Y-m-d'),
            'author' => $data['author'] ?? 'AI Agent',
            'type' => $data['type'] ?? 'feature',
            'changes' => $changes,
        ];

        // Prepend so the newest version is at the top
        array_unshift($logs, $newEntry);

        // Ensure directory exists
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        return $newEntry;
    }
}
