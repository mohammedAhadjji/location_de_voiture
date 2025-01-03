<?php
namespace App\Extensions\Doctrine;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class MatchAgainst extends FunctionNode
{
    /** @var array list of \Doctrine\ORM\Query\AST\PathExpression */
    protected $pathExp = [];
    /** @var string */
    protected $against = null;
    /** @var bool */
    protected $booleanMode = false;
    /** @var bool */
    protected $queryExpansion = false;

    public function parse(Parser $parser)
    {
        // match
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        // first Path Expression is mandatory
        $this->pathExp[] = $parser->StateFieldPathExpression();
        // Subsequent Path Expressions are optional
        $lexer = $parser->getLexer();
        while ($lexer->isNextToken(Lexer::T_COMMA)) {
            $parser->match(Lexer::T_COMMA);
            $this->pathExp[] = $parser->StateFieldPathExpression();
        }
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
        // against
        if (strtolower($lexer->lookahead->value) !== 'against') {
            $parser->syntaxError('against');
        }
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->against = $parser->StringPrimary();
        if ($lexer->isNextToken(Lexer::T_IDENTIFIER)) {
            $value = strtolower($lexer->lookahead->value);
            if ($value === 'boolean') {
                $parser->match(Lexer::T_IDENTIFIER);
                $this->booleanMode = true;
            } elseif ($value === 'expand') {
                $parser->match(Lexer::T_IDENTIFIER);
                $this->queryExpansion = true;
            }
        }
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $walker)
    {
        $fields = [];
        foreach ($this->pathExp as $pathExp) {
            $fields[] = $pathExp->dispatch($walker);
        }
        $against = $walker->walkStringPrimary($this->against)
            . ($this->booleanMode ? ' IN BOOLEAN MODE' : '')
            . ($this->queryExpansion ? ' WITH QUERY EXPANSION' : '');
        return sprintf('MATCH (%s) AGAINST (%s)', implode(', ', $fields), $against);
    }
}
