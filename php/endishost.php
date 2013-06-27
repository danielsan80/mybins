<?php
copy('/etc/hosts', __DIR__.'/../backup/hosts/hosts_'.date('Y-m-d_H-i-s'));

$hosts = file_get_contents('/etc/hosts');

$mode = 'enable';
if (isset($argv[1])) {
    switch($argv[1]) {
        case'enable':
            $mode = 'enable';
            break;
        case'disable':
            $mode = 'disable';
            break;
        defalut:
            echo 'The second arg must be enable or disable'."\n";
            die();
    }
}

if (!isset($argv[2])) {
    echo 'Specify the block name, please'."\n";
    die();
}

$name= $argv[2];

$pattern = '/(#[ ]?block '.$name.')(?P<content>[.\s\w#]*)(#[ ]?endblock)/';


preg_match($pattern, $hosts, $matches);
if (!isset($matches['content'])) {
    echo 'The block "'.$name.'" doesn\'t exist'."\n";
    die();
}

    $content = preg_replace('/\n#/', "\n", $matches['content']);
if ($mode=='disable') {
    $content = preg_replace('/\n/', "\n#", $content);
    $content = preg_replace('/#$/', "", $content);
}

$hosts = preg_replace($pattern, '${1}'.$content.'${3}', $hosts);

file_put_contents('/etc/hosts', $hosts);
echo 'done'."\n";