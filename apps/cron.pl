require "/home/alphawhi/zjx-apps/metar/METAR.pm";
use LWP::UserAgent;
use DBI;

$dbh = DBI->connect("DBI:mysql:(database name)","(username)","(password)");

$m = new Geo::METAR;
$ua = new LWP::UserAgent;

@P31 = ("KPNS","KNPA","KJKA","KNSE");
@VPS = ("KVPS","KHRT","KDTS","KCEW");
@PAM = ("KPAM","KECP","KAAF");
@TLH = ("KTLH");
@VLD = ("KVAD","KVLD");
@JAX = ("KJAX","KNIP","KVQQ","KSGJ","KGNV","KOCF","KCRG");
@DAB = ("KDAB","KOMN","KEVB","KXFL");
@F11 = ("KSFB","KORL","KMCO","KISM","KMLB","KLEE","KCOF");
@SAV = ("KSAV", "KSVN");
@NBC = ("KNBC");
@CHS = ("KCHS");
@MYR = ("KMYR","KCRE");
@CAE = ("KCAE","KSSC","KOGB");
@FLO = ("KFLO");
@OZR = ("KOZR","KTOI");
@stations = (@P31,@VPS,@PAM,@TLH,@VLD,@JAX,@DAB,@F11,@SAV,@NBC,@CHS,@MYR,@CAE,@FLO,@OZR);
#@stations = ("KCAE","KCHS","KDAB","KFLO","KJAX","KMCO","KMYR","KPNS","KSAV","KTLH","KVPS");

foreach $station (@stations)
{
my $req = new HTTP::Request GET =>
  "http://weather.noaa.gov/cgi-bin/mgetmetar.pl?cccc=$station";

my $response = $ua->request($req);

if (!$response->is_success) {

    print $response->error_as_HTML;
    my $err_msg = $response->error_as_HTML;
    warn "$err_msg\n\n";
    die "$!";
}

    # Yep, get the data and find the METAR.

    my $data;
    $data = $response->as_string;
    $data =~ s/\n//go;                          # remove newlines
    $data =~ m/([A-Z]+\s\d+Z.*?)</go;       # find the METAR string
    my $metar = $1;
    my $m = new Geo::METAR;
    $m->metar ($metar);

$alt = $m->ALT;
if ($m->wind eq "00000KT") { $wind = "Calm"; }
else { $wind = $m->WIND_DIR_DEG . '@' . $m->WIND_KTS;
	if ($m->WIND_GUST_KTS) { $wind .= "G" . $m->WIND_GUST_KTS; } }
if ($m->VISIBILITY =~ /^(\d+) (\d+)\/(\d+)$/) { $vis = $1 + ($2 / $3); }
else { $vis = $m->VISIBILITY; }

$layer = 99999;
foreach $piece (@{$m->sky})
{
	if ($piece =~ /^BKN(\d\d\d)/ || $piece =~ /^OVC(\d\d\d)/) {
		$layer = int($1 * 100);
		last;
	}
}

	if ($vis < 3 || $layer < 1000) {
		$fr = "IFR";
	} elsif ($vis < 5 || $layer < 3000) {
		$fr = "MVFR";
	} else {
		$fr = "VFR";
	}
	$dbh->do("INSERT INTO `weather` VALUES('$station','$fr','$wind','$alt','$metar') ON DUPLICATE KEY UPDATE `rules`='$fr', `wind`='$wind', `baro`='$alt', `metar`='$metar'");
}
exit;
