<?php
$translation = array(
    'title' => 'Friday',
    'title_no' => 'Not Friday :(',
    'today' => 'It\'s party time!!! Everyone likes Fridays, right?',
    'past' => 'This Friday has come and gone, was it good?',
    'contribute' => 'Feeling nerdy? Contribute! <a href="%s">%s</a>',
    'friday' => 'Friday!',
    'description' => 'Friday the %s'
);

/*
 * Returns the date of the specified timestamp as a string.
 */
function getDateString($time) {
    return date('jS \\o\\f F (\\w\\e\\e\\k W)', $time);
}
?>
