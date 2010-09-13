<?php
function not_found() {
    header('HTTP/1.0 404 Not Found');
    exit;
}

$year = date('Y');
$week = date('W');

$request = $_SERVER['REQUEST_URI'];
if (preg_match('#^/(\\d+)/(\\d+)$#', $request, $match)) {
    // Fridays that haven't happened yet will return 404.
    if ($match[1] > $year || ($match[1] == $year && $match[2] > ($day >= 5 ? $week : $week - 1))) {
        not_found();
    }

    // Change values according to requested year and week.
    $year = $match[1];
    $week = $match[2];
    $day = 5;
} else if ($request != '/') {
    // Return 404 for invalid paths.
    not_found();
} else {
    $day = date('w');
}

// Return 404 for invalid values and non-Fridays.
if ($year < 0 || $week < 1 || $week > 53 || $day != 5) not_found();

// Generate a path for current year/week and make sure the browser is on that path.
$path = sprintf('/%d/%d', $year, $week);
if ($request != $path) {
    header('Location: http://fred.ag' . $path, true, 302);
    exit;
}

// Get a timestamp for the Friday of the specified date.
// Count from '0104' because January 4th is always in week 1 (according to ISO 8601).
// FIXME: Does not work correctly for week 53: http://fred.ag/2009/53
$time = strtotime($year . '0104 +' . ($week - 1) . ' weeks');
$timeDay = date('w', $time);
$time = strtotime(($timeDay <= 5 ? '+' : '') . (5 - $timeDay) . ' days', $time);

$months = array(
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
$friday = 'Fredagen den ' . strtr(date('j:\\e F, Y (\\v\\e\\c\\k\\a W)', $time), $months);

include('settings.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>fredag</title>
    <meta property="og:title" content="fredag">
    <meta property="og:type" content="activity">
    <meta property="og:url" content="http://fred.ag<?php echo $path; ?>">
    <meta property="og:site_name" content="fred.ag">
    <meta property="og:description" content="<?php echo $friday; ?>">
    <meta property="fb:app_id" content="<?php echo FACEBOOK_APP_ID ?>">
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
            color: #0a0;
            font-family: serif;
            font-size: 140px;
            line-height: 1;
            margin-top: -60px;
            position: absolute;
            text-align: center;
            text-shadow: 0 0 70px #0a0;
            top: 50%;
            width: 100%;
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
    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', '<?php echo ANALYTICS_ID; ?>']);
        _gaq.push(['_trackPageview']);
        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script>
</head>
<body>
<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function () {
        FB.init({appId: '<?php echo FACEBOOK_APP_ID ?>', status: true, cookie: true, xfbml: true});
    };

    (function() {
        var e = document.createElement('script');
        e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
        e.async = true;
        document.getElementById('fb-root').appendChild(e);
    }());
</script>
    <h1>fredag!</h1>
    <div id="like">
        <p>It's party time!!! Alla gillar fredagar, eller?</p>
        <p><fb:like colorscheme="dark"></fb:like></p>
    </div>
    <p id="open-source">Är du en nörd? Bidra! <a href="http://github.com/blixt/fred.ag">fred.ag @ GitHub</a></p>
</body>
</html>
