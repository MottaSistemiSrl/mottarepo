<?php

chdir("..");
if (!file_exists(".git/config")) die("e' necessario lanciare questo script dalla cartella shell del progetto\n");

$check = file_get_contents(".git/config");
if (preg_match("/gitflow/", $check)) die("git flow era gia' stato configurato in passato\n");

$fp = fopen(".git/config", "a");
fwrite($fp, '[gitflow "branch"]
        master = prod
        develop = master
[gitflow "prefix"]
        feature = feature/
        release = release/
        hotfix = hotfix/
        support = support/
        versiontag = v
');
die("git flow configurato correttamente\n");