<?php




function _dump_r($some, $ret = false)
{
    $str = "<pre>";
    $str .= var_export($some, true);
    $str .= "</pre>";

    if ($ret === true) {
        return $str;
    }
    echo $str;
}

function _dump_d($data, $exit = TRUE, $file = '')
{
    if ($data) {
        print '<pre>';
        var_dump($data);
        print '</pre>';
    }
    if ($exit) {
        die($file);
    }
}

function _print_r($some, $ret = false)
{
    $str = "<pre>";
    $str .= print_r($some, true);
    $str .= "</pre>";

    if ($ret === true) {
        return $str;
    }
    echo $str;
}

function _print_d($data, $exit = TRUE, $file = '')
{
    if ($data) {
        print '<pre>';
        print_r($data);
        print '</pre>';
    }
    if ($exit) {
        die($file);
    }
}

function _print_b($data, $title = '')
{
    \Nette\Diagnostics\Debugger::barDump($data, $title);
}

function preg_escape($str)
{
    $patterns = array(
        '/\//',
        '/\^/',
        '/\./',
        '/\$/',
        '/\|/',
        '/\(/',
        '/\)/',
        '/\[/',
        '/\]/',
        '/\*/',
        '/\+/',
        '/\?/',
        '/\{/',
        '/\}/',
        '/\,/'
    );

    $replace = array(
        '\/',
        '\^',
        '\.',
        '\$',
        '\|',
        '\(',
        '\)',
        '\[',
        '\]',
        '\*',
        '\+',
        '\?',
        '\{',
        '\}',
        '\,'
    );

    return preg_replace($patterns, $replace, $str);
}
