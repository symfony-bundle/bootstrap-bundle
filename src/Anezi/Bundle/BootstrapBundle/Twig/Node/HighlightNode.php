<?php

namespace Anezi\Bundle\BootstrapBundle\Twig\Node;

class HighlightNode extends \Twig_Node
{
    public function __construct
    (
        \Twig_Node $body,
        $lineNo = 0,
        $tag,
        $language
    )
    {
        $this->language = $language;
        parent::__construct(
            array(
                'body' => $body
            ),
            array(),
            $lineNo,
            $tag
        );
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler $compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        $compiler
            ->write(
                'echo \'<div class="zero-clipboard">' .
                '<span class="btn-clipboard btn-clipboard-hover">Copy</span></div>\';' .
                PHP_EOL);

        $compiler->write('$result = \PHPygments\PHPygments::render(""');
        /*$compiler
            ->write('. ')
            ->string(print_r($this, 1))
            ->raw("\n")
        ;*/
        /** @var \Twig_Node $node */
        foreach($this->nodes as $node) {
            $this->writeNode($compiler, $node);
        }

        $compiler->raw(", '{$this->language}');\n");
        $compiler->raw('echo preg_replace(\'#^<div class="highlighted-source default (.*)"><pre>(.*)</pre></div>$#s\', \'<div class="highlight"><pre><code class="$1">$2</code></pre></div>\', $result["code"]);');

    }

    private function writeNodeText(\Twig_Compiler $compiler, \Twig_Node_Text $node)
    {
        $compiler
            ->write('. ')
            ->string($node->getAttribute('data'))
            ->raw("\n")
        ;
    }

    private function writeNodePrint(\Twig_Compiler $compiler, \Twig_Node_Print $node)
    {
        $compiler->write('. ');
        $compiler->addIndentation();
        $node->getNode('expr')->compile($compiler);
        $compiler->raw("\n");
    }

    private function writeNode(\Twig_Compiler $compiler, \Twig_Node $node)
    {
        if      ($node instanceof \Twig_Node_Text) {
            $this->writeNodeText($compiler, $node);
        } elseif($node instanceof \Twig_Node_Print) {
            $this->writeNodePrint($compiler, $node);
        } elseif($node instanceof \Twig_Node_Expression_Filter) {
            $compiler->write('. ');
            $node->compile($compiler);
            $compiler->raw("\n");
        } elseif($node instanceof \Twig_Node && !is_subclass_of($node, '\Twig_Node')) {
            foreach ($node->nodes as $iNode) {
                $this->writeNode($compiler, $iNode);
            }
        } else {
            $compiler->write('. ');
            $compiler->string(get_class($node));
            $compiler->raw("\n");
        }
    }
}
