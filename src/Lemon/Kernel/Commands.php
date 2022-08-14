<?php

declare(strict_types=1);

namespace Lemon\Kernel;

use Closure;
use Lemon\Config\Config;
use Lemon\Support\Filesystem;
use Lemon\Terminal\Terminal;

/**
 * Class providing fundamental commands.
 */
class Commands
{
    private const COMMANDS = [
        ['serve {port?} {url?}', 'serve', 'Starts development server'],
        ['template:clear', 'clearTemplates', 'Clears cached views'],
        ['cache:clear', 'clearCache', 'Clears cached data'],
        ['log:clear', 'clearLogs', 'Clears log'],
        ['clear', 'clear', 'Clears cached data, views and logs'],
        ['down', 'down', 'Puts app into maintenance mode'],
        ['up', 'up', 'Puts app back from maintenance mode'],
    ];

    public function __construct(
        private Terminal $terminal,
        private Config $config,
        private Application $lifecycle
    ) {
    }

    public function load(): void
    {
        foreach (self::COMMANDS as $command) {
            $this->terminal->command($command[0], Closure::fromCallable([$this, $command[1]]), $command[2]);
        }
    }

    public function serve($port = 8000, $url = 'localhost'): void
    {
        exec('php -S '.$url.':'.$port.' -t '.$this->lifecycle->directory.DIRECTORY_SEPARATOR.'public');
    }

    public function clearTemplates(): void
    {
        Filesystem::delete($this->config->file('templating.cached'));
    }

    public function clearCache(): void
    {
        Filesystem::delete($this->config->file('cache.storage'));
    }

    public function clearLogs(): void
    {
        Filesystem::delete($this->config->file('logging.file'));
    }

    public function clear(): void
    {
        $this->clearTemplates();
        $this->clearCache();
        $this->clearLogs();
    }

    public function down(): void
    {
        $this->lifecycle->down();
    }

    public function up(): void
    {
        $this->lifecycle->up();
    }
}
