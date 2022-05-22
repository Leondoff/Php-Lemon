<?php

declare(strict_types=1);

namespace Lemon\Terminal;

use Lemon\Kernel\Lifecycle;
use Lemon\Terminal\Commands\Command;
use Lemon\Terminal\Commands\Dispatcher;
use Lemon\Terminal\IO\Output;

class Terminal
{
    private Dispatcher $commands;

    private Output $output; 

    public function __construct(
        private Lifecycle $lifecycle  
    ) {
        $this->commands = new Dispatcher();
        $this->output = new Output();
    }

    public function command(string $signature, callable $action, string $description = ''): Command
    {
        $command = new Command($signature, $action, $description);
        $this->commands->add($command);
        return $command;
    }

    public function out(mixed $content): void 
    {
        echo $this->output->out($content);
    }

    public function ask(mixed $prompt): string
    {
        return readline($this->output->out($prompt));
    }

    public function run(array $arguments): void
    {
        $result = $this->commands->dispatch($arguments);

        if (is_string($result)) {
            echo $result;
            return;
        }

        $this->lifecycle->call($result[0], $result[1]);
    }
}
