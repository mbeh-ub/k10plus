#!/usr/bin/env php

<?php

$srcTiteldatenCsv='./konkordanz.titeldaten';
$srcLokaldatenCsv='./konkordanz.lokaldaten';
$srcNormdatenCsv='./konkordanz.normdaten';

$types = [
    'titeldaten',
    'normdaten',
    'lokaldaten',
    'alle'
];

$alle = <<<EOT
'EOS'
CREATE TABLE titeldaten (swbId STRING, kId STRING);
CREATE TABLE normdaten (swbId STRING, kId STRING);
CREATE TABLE lokaldaten (swbId STRING, kId STRING, year STRING);
.mode csv
.import {$srcTiteldatenCsv} titeldaten
.import {$srcNormdatenCsv} normdaten
.import {$srcLokaldatenCsv} lokaldaten

CREATE INDEX titeldaten_idx on titeldaten(swbId, kId);
CREATE INDEX normdaten_idx on normdaten(swbId, kId);
CREATE INDEX lokaldaten_idx on lokaldaten(swbId, kId, year);
EOS
EOT;

$titeldaten = <<<EOT
'EOS'
CREATE TABLE titeldaten (swbId STRING, kId STRING);
.mode csv
.import {$srcTiteldatenCsv} titeldaten
CREATE INDEX titeldaten_idx on titeldaten(swbId, kId);
EOS
EOT;

$normdaten = <<<EOT
'EOS'
CREATE TABLE normdaten (swbId STRING, kId STRING);
.mode csv
.import {$srcNormdatenCsv} normdaten
CREATE INDEX normdaten_idx on normdaten(swbId, kId);
EOS
EOT;

$lokaldaten = <<<EOT
'EOS'
CREATE TABLE lokaldaten (swbId STRING, kId STRING, year STRING);
.mode csv
.import {$srcLokaldatenCsv} lokaldaten
CREATE INDEX lokaldaten_idx on lokaldaten(swbId, kId, year);
EOS
EOT;

foreach ($types as $type) {
    $sqliteFile = "./konkordanz." . $type . ".sqlite";
    echo "creating sqlite lookupdb " . $sqliteFile . " \n";
    @unlink($sqliteFile);
    $db = new SQLite3($sqliteFile);
    $cmd = "sqlite3 $sqliteFile << " . ${$type};
    exec($cmd);
    $db->close();
}


