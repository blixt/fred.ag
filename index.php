<?php
if (!is_file('settings.php')) {
  echo 'You need to copy settings.php.template to settings.php and set it up.';
  exit(1);
}

include('settings.php');

// Get the ISO 8601 week year, number and day. The year may differ from the
// Gregorian calendar year since ISO 8601 years only consist of whole weeks,
// which may be a bit confusing.
$year = date('o');
$week = date('W');
$day = date('N');

$today = "$year-W$week-$day";

$request = $_SERVER['REQUEST_PATH'];

if (preg_match('#^/(\\d{4})/(\\d{2})(\\.png)?$#', $request, $match)) {
    // Invalid values and Fridays that haven't happened yet will return 404.
    if (($match[1] < 1970 || $match[1] > $year) ||
        ($match[2] < 1 || $match[2] > 53) ||
        ($match[1] == $year && $match[2] > ($day >= 5 ? $week : $week - 1)))
    {
        header('HTTP/1.0 404 Not Found');
        exit;
    }

    // Change values according to requested year and week.
    $year = $match[1];
    $week = $match[2];
    $day = 5;

    // Whether an image should be generated.
    $image = !!$match[3];
} else if ($request != '/') {
    header('HTTP/1.0 404 Not Found');
    exit;
} else {
    $image = false;
}

// Get a timestamp for the Friday of the specified date.
$time = strtotime("$year-W$week-5");

// Generate a path for current year/week and make sure the browser is on that
// path.
$path = ($day == 5) ? date('/o/W', $time) . ($image ? '.png' : '') : '/';
if ($request != $path) {
    header('Location: http://' . DOMAIN . $path, true, 302);
    exit;
}

if ($image) {
    $ih = imagecreatetruecolor(300, 300);
    $textColor = imagecolorallocate($ih, 255, 255, 255);
    imagettftext($ih, 160, 0, 30, 184, $textColor, './droidserif.ttf', $week);
    imagettftext($ih, 80, 0, 31, 269, $textColor, './droidserif.ttf', $year);

    header('Content-Type: image/png');
    imagepng($ih);
    imagedestroy($ih);
    exit;
}

$shownDay = "$year-W$week-$day";

$language = isset($_GET[LANGUAGE_KEY]) ? $_GET[LANGUAGE_KEY] : LANGUAGE_DEFAULT;
if (!array_key_exists($language, $languages)) {
  $language = LANGUAGE_DEFAULT;
}

// This file must define $translation.
include $languages[$language];

/*
 * Attempt to replace a key and a 'variadic' argument with a string
 * replacement. Or return just the attempted key, indicating a non-existing
 * translation.
 * Usage: <?=_l('hello_world', array('Hej', 'Världen'))?>
 */ 
function _l($key, $vars=array()) {
    global $translation;

    if (!isset($translation[$key])) {
        return "$language:$key";
    }

    return vsprintf($translation[$key], $vars);
}

header('Content-type: text/html; charset=' . ENCODING);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="<?= ENCODING ?>">
    <? if ($day == 5): ?>
        <title><?= _l('title') ?>></title>
        <meta property="og:title" content="<?= _l('title') ?>">
        <meta property="og:type" content="activity">
        <meta property="og:url" content="http://<?= DOMAIN . $path ?>">
        <meta property="og:description" content="<?= _('description', array(getDate($time))) ?>">
        <meta property="og:image" content="http://<?= DOMAIN . $path ?>.png">
        <meta property="fb:app_id" content="<?= FACEBOOK_APP_ID ?>">
    <? else: ?>
        <title><?= _l('title_no') ?></title>
    <? endif; ?>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }

        a {
            color: #fff;
        }

        body {
            color: #fff;
            font: 15px/1.3 sans-serif;
        }

        h1 {
            font-family: serif;
            font-size: 140px;
            line-height: 1;
            margin-top: -60px;
            position: absolute;
            text-align: center;
            top: 50%;
            width: 100%;
        }

        h1.no {
            color: #c00;
            text-shadow: 0 0 70px #c00;
        }

        h1.past {
            color: #030;
        }

        h1.today {
            color: #0a0;
            text-shadow: 0 0 70px #0a0;
        }

        html {
            background: #151515;
        }

        p {
            margin-bottom: .3em;
        }

        #like {
            background: #000;
            float: right;
            padding: 20px;
        }

        #open-source {
            background: #000;
            bottom: 0;
            font-size: 11px;
            left: 0;
            margin: 0;
            padding: 5px;
            position: absolute;
        }
    </style>
    <script>
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', '<?= ANALYTICS_ID; ?>']);
        _gaq.push(['_trackPageview']);
        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script>
</head>
<body>
    <? if ($day == 5): ?>
        <div id="fb-root"></div>
        <script>
            window.fbAsyncInit = function () {
                FB.init({appId: '<?= FACEBOOK_APP_ID ?>', status: true, cookie: true, xfbml: true});
            };
            
            (function () {
                var e = document.createElement('script');
                e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            })();
        </script>
        <? if ($shownDay == $today): ?>
            <h1 class="today"><?=_l('friday')?></h1>
            <div id="like">
                <p><?= _l('today') ?></p>
                <p><fb:like colorscheme="dark"></fb:like></p>
            </div>
        <? else: ?>
            <h1 class="past"><?=_l('friday')?></h1>
            <div id="like">
                <p><?= _l('past') ?></p>
                <p><fb:like colorscheme="dark"></fb:like></p>
            </div>
        <? endif; ?>
    <? else: ?>
        <h1 class="no">:(</h1>
    <? endif; ?>

    <p id="open-source"><?= _l('contribute', array('http://github.com/blixt/fred.ag', 'fred.ag @ GitHub')) ?></p>
</body>
</html>
