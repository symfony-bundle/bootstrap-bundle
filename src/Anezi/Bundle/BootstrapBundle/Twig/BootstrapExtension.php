<?php
/**
 * This file is part of the Twitter Bootstrap Bundle for Symfony.
 *
 * @copyright   Copyright (C) 2014 Hassan Amouhzi. All rights reserved.
 * @license     The MIT License (MIT), see LICENSE.md
 */
 
namespace Anezi\Bundle\BootstrapBundle\Twig;

use Symfony\Component\Form\FormView;

class BootstrapExtension extends \Twig_Extension
{

    function getTokenParsers() {
        return array(
        );
    }

    public function getName()
    {
        return 'bootstrap_extension';
    }

    public function getFunctions()
    {
        $tags = array('input', 'horizontal_radio', 'form_group');
        $function = array();
        foreach($tags as $tag) {
            $functionName = preg_replace_callback('/_([a-z])/', function($var) {
                return strtoupper($var[1]);
            }, $tag);
            $function[] = new \Twig_SimpleFunction(
                $tag,
                array($this, $functionName . 'Function'),
                array('is_safe' => array('html'))
            );
        }
        return $function;
    }

    public function formGroupFunction(FormView $radio)
    {
        $html = '<div class="form-group">';
        $html .= '<label class="col-xs-3 control-label">';

        $label = preg_replace_callback('/([A-Z])/', function($var) {
            return ' ' . $var[1];
        }, $radio->vars['name']);

        $label = ucfirst($label);

        $html .= $label;

        if($radio->vars['required']) {
            $html .=  ' <span class="required" title="Required">*</span>';
        }

        $html .= '</label><div class="col-xs-9">';
        if(count($radio->children) > 0) {
            foreach ($radio->children as $child) {
                $html .= $this->horizontalRadioFunction($child);
            }
        } else {
            $html .= $this->controlFunction($radio);
        }
        $html .= '</div></div>';
        $radio->setRendered();
        return $html;
    }

    public function controlFunction(FormView $control)
    {
        return $this->inputFunction('text', $control);
    }

    public function horizontalRadioFunction(FormView $radio)
    {
        return $this->tagFunction(
            'div',
            array('class' => 'radio'),
            array(
                $this->horizontalRadioLabelFunction($radio)
            )
        );
    }

    public function horizontalRadioLabelFunction(FormView $radio)
    {
        return $this->tagFunction(
            'label',
            $radio->vars['label_attr'],
            array(
                $this->inputFunction('radio', $radio),
                $radio->vars['label']
            )
        );
    }

    public function inputFunction($type, FormView $input)
    {
        $value    = $input->vars['value'];
        $id       = $input->vars['id'];
        $name     = $input->vars['full_name'];
        $disabled = $input->vars['disabled'];
        $readOnly = $input->vars['read_only'];
        $required = $input->vars['required'];
        $checked  = isset($input->vars['checked']) ? $input->vars['checked'] : false;

        return '<input type="'  . $type  . '"'       .
        ($id       ? ' id="'    . $id    . '"' : '') .
        ($name     ? ' name="'  . $name  . '"' : '') .
        ($value    ? ' value="' . $value . '"' : '') .
        ($disabled ? ' disabled'               : '') .
        ($readOnly ? ' readonly'               : '') .
        ($required ? ' required'               : '') .
        ($checked  ? ' checked'                : '') .
        '>';
    }

    private function tagFunction($tagName, $attributes, $children = array())
    {
        $voidElements = array('area', 'base', 'br', 'col', 'command', 'embed', 'hr',
            'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr');

        $isVoid = in_array($tagName, $voidElements);

        $hasChildren = count($children) > 0;

        if($isVoid && $hasChildren) {
            throw new \Exception('Void elements can\'t have children');
        }

        $html = '<' . $tagName;
        foreach($attributes as $key=>$value) {
            $html .= ' ' . $key . '="' . $value . '"';
        }

        if($isVoid) {
            return $html . '>';
        }

        if(!$hasChildren) {
            return $html . '/>';
        }

        $html .= '>';

        foreach($children as $child) {
            $html .= is_array($child) ?
                $this->tagFunction($child['tagName'], $child['attributes']) : $child;
        }

        return $html . '</' . $tagName . '>';
    }
}
