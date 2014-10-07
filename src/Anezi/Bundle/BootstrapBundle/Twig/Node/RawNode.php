<?php

namespace Anezi\Bundle\BootstrapBundle\Twig\Node;

use GeSHi;

class RawNode extends \Twig_Node
{
    public function __construct
    (
        $body,
        \Twig_NodeInterface $domain = null,
        \Twig_Node_Expression $count = null,
        \Twig_Node_Expression $vars = null,
        \Twig_Node_Expression $locale = null,
        $lineno = 0,
        $tag = null
    )
    {
        $this->body = $body;
        parent::__construct(
            array(
                'count' => $count,
                'body' => new \Twig_Node_Expression_Constant($body, array(), $lineno),
                'domain' => $domain,
                'vars' => $vars,
                'locale' => $locale
            ),
            array(),
            $lineno,
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

        $vars = $this->getNode('vars');
        $defaults = new \Twig_Node_Expression_Array(array(), -1);
        if ($vars instanceof \Twig_Node_Expression_Array) {
            $defaults = $this->getNode('vars');
            $vars = null;
        }
        //list($msg, $defaults) = $this->compileString($this->getNode('body'), $defaults, (bool) $vars);

        $compiler
            ->write(
                'echo \'<div class="zero-clipboard">' .
                '<span class="btn-clipboard btn-clipboard-hover">Copy</span></div>\';' .
                PHP_EOL)
            ->write('echo \'<div class="highlight"><pre>\';' . PHP_EOL );

        /*if( $msg instanceof \Twig_Node_Expression_Constant) {
            //$compiler->write('echo <<<TEXT' . PHP_EOL);
        }*/

        //$tmpCompiler = new \Twig_Compiler($compiler->getEnvironment());
        //$tmpCompiler->compile($msg);
        //$toExec = $tmpCompiler->getSource();
        //$this->env = $compiler->getEnvironment();
        //ob_start();
        //eval($toExec);
        //$out2 = ob_get_contents();
        //ob_end_clean();

        $geshi = new \GeSHi($this->body, 'html');
        //$geshi = new \GeSHi($this->text($msg->nodes), 'html');
        //$geshi->set_header_type(GESHI_HEADER_NONE);
        $geshi->enable_classes();

        $compiler->write('echo ');
        $compiler->string(
            preg_replace('#^<pre(.*)endhighlight</pre>$#s', '<code$1</code>', $geshi->parse_code()
            ));
        $compiler->write(';' . PHP_EOL);

        //if( $msg instanceof \Twig_Node_Expression_Constant) {
            //$compiler->write(PHP_EOL . 'TEXT;' . PHP_EOL);
        //}

        $compiler->write('echo \'</pre></div>\';' . PHP_EOL);

    }

    protected function compileString(\Twig_NodeInterface $body, \Twig_Node_Expression_Array $vars, $ignoreStrictCheck = false)
    {
        if ($body instanceof \Twig_Node_Expression_Constant) {
            $msg = $body->getAttribute('value');
        } elseif ($body instanceof \Twig_Node_Text) {
            $msg = $body->getAttribute('data');
        } else {
            return array($body, $vars);
        }

        preg_match_all('/(?<!%)%([^%]+)%/', $msg, $matches);

        foreach ($matches[1] as $var) {
            $key = new \Twig_Node_Expression_Constant('%'.$var.'%', $body->getLine());
            if (!$vars->hasElement($key)) {
                if ('count' === $var && null !== $this->getNode('count')) {
                    $vars->addElement($this->getNode('count'), $key);
                } else {
                    $varExpr = new \Twig_Node_Expression_Name($var, $body->getLine());
                    $varExpr->setAttribute('ignore_strict_check', $ignoreStrictCheck);
                    $vars->addElement($varExpr, $key);
                }
            }
        }

        return array(
            new \Twig_Node_Expression_Constant(
                str_replace('%%', '%', trim($msg)), $body->getLine()
            ),
            $vars
        );
    }

    private function text($nodes)
    {
        //$compiler = new \Twig_Compiler($this->g)
        $string = '';

        foreach($nodes as $node) {
            if($node instanceof \Twig_Node_Text) {
                $string .= $node->getAttribute('data');
            } elseif ($node instanceof \Twig_Node_Print) {
                $string .= $this->text($node);
            } elseif ($node instanceof \Twig_Node_Expression_Filter) {
                //$node->compile()
                $string .= $this->text($node->nodes);
            } elseif ($node instanceof \Twig_Node_Expression_Constant) {
                $string .= $node->getAttribute('value');
            } elseif ($node instanceof \Twig_Node) {
                $string .= $this->text($node->nodes);
            } elseif ($node instanceof \Twig_Node_Expression_GetAttr) {
                // TODO
            } else {
                $string .= get_class($node);
            }
        }

        return $string;
    }
}
