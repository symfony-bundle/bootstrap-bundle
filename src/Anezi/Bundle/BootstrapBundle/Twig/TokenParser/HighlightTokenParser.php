<?php

namespace Anezi\Bundle\BootstrapBundle\Twig\TokenParser;

use Anezi\Bundle\BootstrapBundle\Twig\Node\HighlightNode;

class HighlightTokenParser extends \Twig_TokenParser
{
    /**
     * Parses a token and returns a node.
     *
     * @param \Twig_Token $token A Twig_Token instance
     * @return \Twig_NodeInterface A Twig_NodeInterface instance
     * @throws \Twig_Error_Syntax
     */
    public function parse(\Twig_Token $token)
    {
        $lineNo = $token->getLine();
        $stream = $this->parser->getStream();

        $vars = new \Twig_Node_Expression_Array(array(), $lineNo);
        $domain = null;
        $locale = null;

        $language = null;

        if (!$stream->test(\Twig_Token::BLOCK_END_TYPE)) {
            if ($stream->test('html')) {
                $language = $this->parser->getExpressionParser()->parseExpression();
            }

            if ($stream->test('bash')) {
                $language = $this->parser->getExpressionParser()->parseExpression();
            }

            if ($stream->test('js')) {
                $language = $this->parser->getExpressionParser()->parseExpression();
            }

            if ($stream->test('scss')) {
                $language = $this->parser->getExpressionParser()->parseExpression();
            } elseif (!$stream->test(\Twig_Token::BLOCK_END_TYPE)) {
                throw new \Twig_Error_Syntax(
                    'Unexpected language. Highlight was looking for the "html", "bash", "js", or "scss".',
                    $stream->getCurrent()->getLine(),
                    $stream->getFilename()
                );
            }
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        $body = $this->parser->subparse(array($this, 'decideTransFork'), true);

        if (!$body instanceof \Twig_Node && !$body instanceof \Twig_Node_Text && !$body instanceof \Twig_Node_Expression) {
            throw new \Twig_Error_Syntax(
                'A message inside a trans tag must be a simple text.',
                $body->getLine(),
                $stream->getFilename()
            );
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        if(is_null($language)) {
            throw new \Exception('Need language');
        }

        return new HighlightNode($body, $lineNo, $this->getTag(), $language->getAttribute('name'));
    }

    public function decideTransFork($token)
    {
        return $token->test(array('endhighlight'));
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag()
    {
        return 'highlight';
    }
}
