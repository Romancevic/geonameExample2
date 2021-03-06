<?php
//echo '<pre>';print_r($_POST);

$countryBorders = json_decode(file_get_contents('countryBorders.geo.json'), true);
$border = null;

foreach($countryBorders['features'] as $feature) {

	if($feature['properties']['ISO_A3'] == $_POST['iso3']) {
		$border = $feature; break;
	}
}

exit(json_encode($border));
/* OLD VERSION
if(isset($_POST['iso3'])) {

	$indexArr = 0; // openstreetmap дает несколько массивов с координатами по стране. Берем только $indexArr
	$iso3 = trim($_POST['iso3']);
	$countryName = $_POST['countryName'];
	$filePolygon = 'polygon/'.preg_replace('/[^0-9a-zа-яё-]/i', '', $countryName).'.txt';

	if(file_exists($filePolygon)) {
		exit(getFormat(file_get_contents($filePolygon), $indexArr, $countryName, $iso3));
	}

	$url = 'https://nominatim.openstreetmap.org/search.php?country='.urlencode($countryName).'&polygon_geojson=1&format=jsonv2';
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_REFERER, 'https://nominatim.openstreetmap.org/');
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36');

	$result = curl_exec($ch); //echo $result;

	curl_close($ch);

	$obj = json_decode($result);

	// тип полигона (как правило MultiPolygon,Polygon)
	if(isset($obj[$indexArr]->geojson->type)) {

		file_put_contents($filePolygon, $result);

		exit(getFormat($result, $indexArr, $countryName, $iso3));
	} else {
		exit('{"status":"error","message":"Type not found"}');
	}
}

function getFormat($data, $indexArr, $countryName, $iso3) {

	$data = json_decode($data);
	$obj =
	'{'.
		'"type":"Feature",'.
		'"properties":'.
		'{'.
			'"ADMIN":"'.$countryName.'",'.
			'"ISO_A3":"'.$iso3.'"'.
		'},'.
		'"geometry":'.
		'{'.
			'"type":"'.$data[$indexArr]->geojson->type.'",'.
			'"coordinates":'.json_encode($data[$indexArr]->geojson->coordinates).
		'}'.
	'}';

	return $obj;
}
OLD VERSION */