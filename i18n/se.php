<?php
$translation = array(
    'title' => 'Fredag',
    'title_no' => 'Inte fredag :(',
    'today' => 'It\'s party time!!! Alla gillar fredagar, eller?',
    'past' => 'Denna fredag har kommit och gått. Var den bra?',
    'contribute' => 'Är du en nörd? Bidra! <a href="%s">%s</a>',
    'friday' => 'Fredag!',
    'description' => 'Fredagen den %s'
);

$_months = array(
    'January' => 'januari',
    'February' => 'februari',
    'March' => 'mars',
    'April' => 'april',
    'May' => 'maj',
    'June' => 'juni',
    'July' => 'juli',
    'August' => 'augusti',
    'September' => 'september',
    'October' => 'oktober',
    'November' => 'november',
    'December' => 'december'
);

/*
 * Returns the date of the specified timestamp as a string.
 */
function getDateString($time) {
    global $_months;
    return strtr(date('j:\\e F, Y (\\v\\e\\c\\k\\a W)', $time), $_months);
}
?>
