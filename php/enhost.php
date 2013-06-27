<?php
copy('/etc/hosts', __DIR__.'/../backup/hosts/hosts_'.date('Y-m-d_H-i-s'));

$hosts = file_get_contents('/etc/hosts');

if (!isset($argv[1])) {
    echo 'Specify the block name, please'."\n";
    die();
}

$name= $argv[1];

$pattern = '/(#[ ]?block '.$name.')(?P<content>[.\s\w#]*)(#[ ]?endblock)/';

preg_match($pattern, $hosts, $matches);

$content = preg_replace('/\n#/', "\n", $matches['content']);
$hosts = preg_replace($pattern, '${1}'.$content.'${3}', $hosts);

file_put_contents('/etc/hosts', $hosts);
echo 'done'."\n";