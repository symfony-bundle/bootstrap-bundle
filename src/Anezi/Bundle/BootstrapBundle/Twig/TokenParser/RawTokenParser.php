<?php

namespace Anezi\Bundle\BootstrapBundle\Twig\TokenParser;

use Anezi\Bundle\BootstrapBundle\Twig\Node\HighlightNode;

class RawTokenParser extends \Twig_TokenParser
{
    /**
     * Parses a token and returns a node.
     *
     * @param \Twig_Token $token A Twig_Token instance
     *
     * @return \Twig_NodeInterface A Twig_NodeInterface instance
     *
     * @throws \Twig_Error_Syntax
     */
    public function parse(\Twig_Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $vars = new \Twig_Node_Expression_Array(array(), $lineno);
        $domain = null;
        $locale = null;

        if (!$stream->test(\Twig_Token::BLOCK_END_TYPE)) {
            if ($stream->test('html')) {
                // {% trans with vars %}
                //$stream->next();
                $vars = $this->parser->getExpressionParser()->parseExpression();
            }

            if ($stream->test('bash')) {
                $domain = $this->parser->getExpressionParser()->parseExpression();
            }

            if ($stream->test('js')) {
                $domain = $this->parser->getExpressionParser()->parseExpression();
            }

            if ($stream->test('scss')) {
                // {% trans into "fr" %}
                //$stream->next();
                $locale =  $this->parser->getExpressionParser()->parseExpression();
            } elseif (!$stream->test(\Twig_Token::BLOCK_END_TYPE)) {
                throw new \Twig_Error_Syntax('Unexpected token. Twig was looking for the "with", "from", or "into" keyword.', $stream->getCurrent()->getLine(), $stream->getFilename());
            }
        }

        // {% trans %}message{% endtrans %}
        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        $body = '';
        while (!$stream->test(\Twig_Token::BLOCK_START_TYPE)) {
            file_put_contents(
                __DIR__ . '/tmp.php',
                "<?php" . PHP_EOL . "require_once 'helper.php';" . PHP_EOL .
                "return " .
                preg_replace_callback(
                    '#^([^\(]*)\((.*)\)(.*)$#s',
                    function($a) {
                        return
                            strtolower($a[1]) .
                            sprintf('("%s")', addcslashes($a[2], "\0\t\"\$\\")) .
                            $a[3];
                    },
                    $stream->next()
                ) .
                ';');
            $body .= include __DIR__ . '/tmp.php';
        }

        $stream->next(); // BLOCK_START_TYPE

        $stream->next();

        $stream->test("endhighlight");
        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        /*$body = $this->parser->subparse(array($this, 'decideTransFork'), true);

        if (!$body instanceof \Twig_Node && !$body instanceof \Twig_Node_Text && !$body instanceof \Twig_Node_Expression) {
            throw new \Twig_Error_Syntax(
                'A message inside a trans tag must be a simple text.',
                $body->getLine(),
                $stream->getFilename()
            );
        }*/

        //$stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new HighlightNode($body, $domain, null, $vars, $locale, $lineno, $this->getTag());
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
