<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once 'email-settings.php';

$posts_to_send = aben_get_posts_for_email()['posts_to_email'];
$posts_count = empty($posts_to_send) ? 0 : count($posts_to_send);

$aben_settings = aben_get_options();
$number_of_posts = $aben_settings['number_of_posts'];
$show_view_all = $aben_settings['show_view_all'] === 1 ? true : false;
$show_unsubscribe = $aben_settings['show_unsubscribe'] === 1 ? true : false;
$show_number_view_all = $aben_settings['show_number_view_all'] === 1 ? true : false;
$show_view_post = $aben_settings['show_view_post'] === 1 ? true : false;
$show_view_all_based_on_post = ($number_of_posts < $posts_count) ? true : false;

echo '<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
</head>

<body>
	<div style="font-family:Open Sans,sans-serif;margin:0;padding:0;background: {{BODY_BG}};color: #1f2430;">
		<div style="width:100%;max-width:500px;margin: auto;">
			<div style="padding:20px;">
				<p style="font-size:16px"><strong>{{HEADER_TEXT}}</strong> <img width="16px" data-emoji="👋" class="an1" alt="👋" aria-label="👋" draggable="false" src="https://fonts.gstatic.com/s/e/notoemoji/15.1/1f44b/72.png" loading="lazy"></p>
				<p style="font-size:16px;">{{HEADER_SUBTEXT}}
				</p>
			</div>
			<div style="padding:10px">';?><?php
foreach ($posts_to_send as $post) {

    if ($number_of_posts <= 0) {
        break;
    }
    $title = $post['title'];
    $link = $post['link'];
    $excerpt = $post['excerpt'];
    // $author = $post['author'];
    // $country = $post['country'];

    echo '<div style="display:flex;margin-bottom:20px;padding:20px;background:{{HEADER_BG}};">
					<div style="width:70%">
						<p style="font-size:16px;margin:0;color: #008dcd;">' . $title . '</p>';

    if (!empty($excerpt)) {
        echo '<p style="font-size:14px;color:#333333;margin:5px 0 0">' . $excerpt . '</p>';
    }
    echo '</div>
					<div style="width:30%;align-content: center;text-align: center;">';
    if ($show_view_post) {
        echo '<a href="' . $link . '"
							style="display:inline-block;padding:5px 20px;color:#fff;text-decoration:none;background-color:#0ead5d;border-radius:25px;height:fit-content">{{VIEW_POST_TEXT}}</a>';
    }
    echo '</div>
				</div>';

    $number_of_posts--;
}

?>
			<?php
echo '<div style="display:flex;padding-bottom:10px;">
					<div style="width:100%;text-align:center;">';

if ($show_view_all && $show_view_all_based_on_post) {
    echo '<a href="{{ALL_POSTS_PAGE_LINK}}"
							style="display:inline-block;padding:10px 20px;background-color:#165d31;color:#ffffff;text-decoration:none;border-radius:25px">{{VIEW_ALL_POSTS_TEXT}}';

    if ($show_number_view_all) {
        echo ' ({{POSTS_NUMBER}})';
    }
    echo '</a>';
}
echo '</div>
				</div>
			</div>
			<div style="color:#808080;text-align:center;padding:20px;">
				<a href="' . home_url() . '"><img src="{{SITE_LOGO}}" alt="Site Logo" style="max-width:180px;margin-top: 10px;"></a>
				<p>{{FOOTER_TEXT}}</p>'; ?>

				<?php
if ($show_unsubscribe) {
    echo '<p><a href="{{UNSUBSCRIBE_LINK}}" style="color:#808080;text-decoration:none">Unsubscribe</a></p>';
}?>

<?php echo '
			</div>
		</div>
</body>

</html>';
