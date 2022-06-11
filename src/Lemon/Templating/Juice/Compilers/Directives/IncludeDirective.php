<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Factory;
use Lemon\Templating\Juice\Token;

class IncludeDirective implements Directive
{
    public function __construct(
        private Factory $factory
    ) {
    }

    public function compileOpenning(Token $token, array $stack): string
    {
        $tokens = token_get_all('<?php '.$token->content[1]); // TODO better manipulation
        if (2 != count($tokens)) {
            throw new CompilerException('Directive include takes exactly 1 argument', $token->line);
        }
        if (T_CONSTANT_ENCAPSED_STRING != $tokens[1][0]) {
            throw new CompilerException('Argument 1 of directive include has to be string', $token->line);
        }

        $template = $this->factory->make(substr($tokens[1][1], 1, -1));

        return '<?php include \''.$template->compiled_path.'\' ?>';
    }
}