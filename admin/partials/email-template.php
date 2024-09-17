<?php

if (!defined('ABSPATH')) {
    exit;
}
require_once 'email-settings.php';

$aben_settings = aben_get_options();
$show_view_all = $aben_settings['view_all_posts_text'] === 1 ? true : false;
$show_number_view_all = $aben_settings['view_all_number'] === 1 ? true : false;
$show_unsubscribe = $aben_settings['show_unsubscribe'] === 1 ? true : false;
$show_view_post = $aben_settings['show_view_post'] === 1 ? true : false;

$aben_get_posts_data = aben_get_today_posts();
$posts_to_send = $aben_get_posts_data['posts_to_email'];

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
    $title = $post['title'];
    $link = $post['link'];
    $excerpt = $post['excerpt'];
    $author = $post['author'];
    $country = $post['country'];

    echo '<div style="display:flex;margin-bottom:20px;padding:20px;background: white;">
					<div style="width:80%">
						<p style="font-size:16px;margin:0;color: #008dcd;">' . $title . '</p>
						<p style="font-size:14px;color:#333333;margin:5px 0 0">' . $excerpt . '</p>
					</div>
					<div style="width:20%;align-content: center;text-align: center;">
						<a href="' . $link . '"
							style="display:inline-block;padding:5px 20px;color:#fff;text-decoration:none;background-color:#0ead5d;border-radius:25px;height:fit-content">{{VIEW_POST_TEXT}}</a>
					</div>
				</div>';

}?>
			<?php
echo '<div style="display:flex;padding-bottom:10px;">
					<div style="width:100%;text-align:center;">
						<a href="' . home_url('{{ALL_POSTS_PAGE_LINK}}') . '"
							style="display:inline-block;padding:10px 20px;background-color:#165d31;color:#ffffff;text-decoration:none;border-radius:25px">{{VIEW_ALL_POSTS_TEXT}} ({{POSTS_NUMBER}})</a>
					</div>
				</div>
			</div>
			<div style="color:#808080;text-align:center;padding:20px;">
				<a href="#"><img src="{{SITE_LOGO}}" alt="Site Logo" style="max-width:180px;margin-top: 10px;"></a>
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
