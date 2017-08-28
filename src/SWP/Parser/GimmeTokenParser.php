<?php

namespace SWP\Parser;

use SWP\Node\GimmeNode;

/**
 * Parser for gimme/endgimme blocks.
 */
class GimmeTokenParser extends \Twig_TokenParser
{
    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag()
    {
        return 'gimme';
    }

    /**
     * @param \Twig_Token $token
     *
     * @return bool
     */
    public function decideCacheEnd(\Twig_Token $token)
    {
        return $token->test('endgimme');
    }

    /**
     * Parses a token and returns a node.
     *
     * @return \Twig_Node A Twig_Node instance
     *
     * @throws \Twig_Error_Syntax
     */
    public function parse(\Twig_Token $token)
    {
        $lineNumber = $token->getLine();
        $stream = $this->parser->getStream();

        $annotation = $this->parser->getExpressionParser()->parseAssignmentExpression();
        $parameters = null;
        if ($stream->nextIf(\Twig_Token::NAME_TYPE, 'with')) {
            $parameters = $this->parser->getExpressionParser()->parseExpression();
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideCacheEnd'], true);
        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new GimmeNode($annotation, $parameters, $body, $lineNumber, $this->getTag());
    }
}
