<?php

function var_start_type($a, $b = null)
{
    if($b != null) {
        throw new Exception("Why?");
    }
    return '{'.$a.'{ ';
}

function name_type($a, $b = null)
{
    if($b != null) {
        throw new Exception("Why?");
    }
    return $a;
}

function text_type($a, $b = null)
{
    if($b != null) {
        throw new Exception("Why?");
    }
    return $a;
}

function punctuation_type($a, $b = null)
{
    if($b != null) {
        throw new Exception("Why?");
    }
    return $a;
}

function var_end_type($a, $b = null)
{
    if($b != null) {
        throw new Exception("Why?");
    }
    return ' }'.$a.'}';
}

function string_type($a, $b = null)
{
    if($b != null) {
        throw new Exception("Why?");
    }
    return $a;
}
