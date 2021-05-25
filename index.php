<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package referendum
 */

get_header();
?>

<h2>Всеукраинский Референдум “Отменить ли COVID-19 и SARS-CoV-2 ограничения?”</h2>

<?php
$fb = new \Facebook\Facebook([
	'app_id' => '493097174605329',           //Replace {your-app-id} with your app ID
	'app_secret' => 'd9afeea2403ccbfa1df1f53cfaacd18a',   //Replace {your-app-secret} with your app secret
	'graph_api_version' => 'v10.0',
]);
try {
	// Returns a `Facebook\FacebookResponse` object
	$response = $fb->get(
		'/180808927046063',
		'{access-token}'
	);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
	echo 'Graph returned an error: ' . $e->getMessage();
	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
	exit;
}
$graphNode = $response->getGraphNode();
/* handle the result */
?>
<?php
get_footer();
