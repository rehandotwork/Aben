<?php // Send Email

if (!defined('ABSPATH')) {

    exit;

}

// add_action('save_post', 'aben_send_email');

function aben_send_email()
{

    error_log('aben_send_email function was called at ' . current_time('mysql'));

    // if (wp_is_post_autosave($post_id)) {
    //     return;
    // }

    // // Check if this is a revision
    // if (wp_is_post_revision($post_id)) {
    //     return;
    // }

    // // Check if the post is published
    // $post_status = get_post_status($post_id);
    // if ($post_status !== 'publish') {
    //     return;
    // }
    $aben_get_posts_result = aben_get_today_posts(); // Refer to email-settings.php

    if (!empty($aben_get_posts_result)) {

        $posts_published_today = $aben_get_posts_result['posts_published_today']; //Refer to email-settings.php

        $posts_to_send = $aben_get_posts_result['posts_to_email'];
        // var_dump($posts_to_send);

    }

    if (!empty($posts_published_today)) {

        // echo ($posts_published_today);

        $get_settings = aben_get_options();

        $post_archive_slug = $get_settings['archive_page_slug'];
        // echo $post_archive_slug;

        $email_subject = $get_settings['email_subject'];

        $email_body = $get_settings['email_body'];
        $email_body = '<!DOCTYPE html>
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">

<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"><!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]--><!--[if !mso]><!-->
	<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;600;700;800;900" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@100;200;300;400;500;600;700;800;900" rel="stylesheet" type="text/css"><!--<![endif]-->
	<style>
		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			padding: 0;
		}

		a[x-apple-data-detectors] {
			color: inherit !important;
			text-decoration: inherit !important;
		}

		#MessageViewBody a {
			color: inherit;
			text-decoration: none;
		}

		p {
			line-height: inherit
		}

		.desktop_hide,
		.desktop_hide table {
			mso-hide: all;
			display: none;
			max-height: 0px;
			overflow: hidden;
		}

		.image_block img+div {
			display: none;
		}

		@media (max-width:700px) {
			.desktop_hide table.icons-inner {
				display: inline-block !important;
			}

			.icons-inner {
				text-align: center;
			}

			.icons-inner td {
				margin: 0 auto;
			}

			.mobile_hide {
				display: none;
			}

			.row-content {
				width: 100% !important;
			}

			.stack .column {
				width: 100%;
				display: block;
			}

			.mobile_hide {
				min-height: 0;
				max-height: 0;
				max-width: 0;
				overflow: hidden;
				font-size: 0px;
			}

			.desktop_hide,
			.desktop_hide table {
				display: table !important;
				max-height: none !important;
			}
		}
	</style>
</head>

<body class="body" style="background-color: #ffffff; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
	<table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;">
		<tbody>
			<tr>
				<td>
					<table class="row row-1" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 680px; margin: 0 auto;" width="680">
										<tbody>
											<tr>
												<td class="column column-1" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="padding-bottom:15px;padding-top:25px;width:100%;padding-right:0px;padding-left:0px;">
																<div class="alignment" align="left" style="line-height:10px">
																	<div style="max-width: 238px;"><a href="' . home_url() . '" target="_blank" style="outline:none" tabindex="-1"><img src="https://stg.gulfworking.com/wp-content/uploads/2023/01/finalLogo-cropped-1.svg" style="display: block; height: auto; border: 0; width: 100%;" width="238" alt="Logo" title="Logo" height="auto"></a></div>
																</div>
															</td>
														</tr>
													</table>
												</td>
												<td class="column column-2" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="heading_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="padding-right:15px;padding-top:40px;text-align:center;width:100%;">
																<h1 style="margin: 0; color: #5a5a5a; direction: ltr; font-family: "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif; font-size: 19px; font-weight: 400; letter-spacing: normal; line-height: 120%; text-align: right; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 22.8px;"><span class="tinyMce-placeholder" style="word-break: break-word;">Daily Gulf Jobs<br></span></h1>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-2" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8f4dc;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8f4dc; background-position: center top; color: #000000; width: 680px; margin: 0 auto;" width="680">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="width:100%;">
																<div class="alignment" align="center" style="line-height:10px">
																	<div style="max-width: 640px;"><img src="https://stg.gulfworking.com/wp-content/uploads/2024/07/246-2462187_dubai-jobs-banner-hd-png-download-removebg-preview.png" style="display: block; height: auto; border: 0; width: 100%;" width="640" alt="Alternate text" title="Alternate text" height="auto"></div>
																</div>
															</td>
														</tr>
													</table>
													<table class="paragraph_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad" style="padding-bottom:5px;padding-left:20px;padding-right:10px;">
																<div style="color:#008dcd; text-align:center; font-family:Oswald, Arial, Helvetica Neue, Helvetica, sans-serif;font-size:50px;font-weight:400;line-height:120%;text-align:center;mso-line-height-alt:60px;">
																	<p style="margin: 0; word-break: break-word;"><span style="word-break: break-word;"><strong>Latest Gulf Jobs For You<br></strong></span></p>
																</div>
															</td>
														</tr>
													</table>
													<table class="paragraph_block block-3" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad" style="padding-bottom:30px;padding-left:15px;padding-right:15px;padding-top:15px;">
																<div style="color:#555555;text-align:center;font-family:"Roboto Slab",Arial,"Helvetica Neue",Helvetica,sans-serif;font-size:16px;line-height:150%;text-align:center;mso-line-height-alt:24px;">
																	<p style="margin: 0; word-break: break-word;"><span style="word-break: break-word;">Gulf Jobs for Indian Nationals <br>By Authorized Human Resource Consultancies<br></span></p>
																</div>
															</td>
														</tr>
													</table>
													<table class="image_block block-4" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="width:100%;">
																<div class="alignment" align="center" style="line-height:10px">
																	<div style="max-width: 680px;"><img src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/1741/top.png" style="display: block; height: auto; border: 0; width: 100%;" width="680" alt="Alternate text" title="Alternate text" height="auto"></div>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>';

        foreach ($posts_to_send as $post) { // Appending Fetched Posts to Email Body

            $title = $post['title'];
            $link = $post['link'];
            $author = $post['author'];
            // echo $title;

            // $email_body .= '<h4>' . $title . ' <a href = ' . $link . '>Apply Here</a></h4>';

            $email_body .= '<table class="row row-3" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8f4dc;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 680px; margin: 0 auto;" width="680">
										<tbody>
											<tr>
												<td class="column column-1" width="66.66666666666667%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad" style="padding-left:35px;padding-right:20px;padding-top:10px;">
																<div style="color:#3a4848;font-family: "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;font-size:20px;font-weight:400;line-height:150%;text-align:left;mso-line-height-alt:30px;">
																	<p style="margin: 0; word-break: break-word;"><span style="word-break: break-word; color: #313131;"><strong>' . $title . '</strong></span></p>
																</div>
															</td>
														</tr>
													</table>
													<table class="paragraph_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad" style="padding-left:35px;padding-right:20px;">
																<div style="color:#3a4848;font-family:Oswald, Arial, Helvetica Neue, Helvetica, sans-serif;font-size:15px;font-weight:400;line-height:150%;text-align:left;mso-line-height-alt:22.5px;">
																	<p style="margin: 0; word-break: break-word;"><span style="word-break: break-word;">By - ' . $author . '</span></p>
																</div>
															</td>
														</tr>
													</table>
													<table class="paragraph_block block-3" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad" style="padding-left:35px;padding-right:20px;">
																<div style="color:#3a4848;font-family:"Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;font-size:15px;font-weight:400;line-height:150%;text-align:left;mso-line-height-alt:22.5px;">
																	<p style="margin: 0; display:none;">Full Time | San Francisco, CA</p>
																</div>
															</td>
														</tr>
													</table>
													<table class="paragraph_block block-4" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad" style="padding-bottom:5px;padding-left:35px;padding-right:20px;">
																<div style="color:#3a4848;font-family:"Roboto Slab",Arial,"Helvetica Neue",Helvetica,sans-serif;font-size:14px;font-weight:400;line-height:150%;text-align:left;mso-line-height-alt:21px;">&nbsp;</div>
															</td>
														</tr>
													</table>
												</td>
												<td class="column column-2" width="33.333333333333336%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="button_block block-1" width="100%" border="0" cellpadding="35" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad">
																<div class="alignment" align="center"><!--[if mso]>
<v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" style="height:42px;width:119px;v-text-anchor:middle;" arcsize="10%" stroke="false" fillcolor="#de4d4c">
<w:anchorlock/>
<v:textbox inset="0px,0px,0px,0px">
<center dir="false" style="color:#ffffff;font-family:Arial, sans-serif;font-size:16px">
<![endif]-->
																	<div style="background-color:#de4d4c;border-bottom:0px solid transparent;border-left:0px solid transparent;border-radius:4px;border-right:0px solid transparent;border-top:0px solid transparent;color:#ffffff;display:inline-block;font-family:"Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;font-size:16px;font-weight:400;mso-border-alt:none;padding-bottom:5px;padding-top:5px;text-align:center;text-decoration:none;width:auto;word-break:keep-all;"><span style="word-break: break-word; padding-left: 20px; padding-right: 20px; font-size: 16px; display: inline-block; letter-spacing: normal;"><a href="' . $link . '"style="word-break: break-word; line-height: 32px; color:white; text-decoration:none;">Apply Now</a></span></div><!--[if mso]></center></v:textbox></v:roundrect><![endif]-->
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-4" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f3f7f2;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 680px; margin: 0 auto;" width="680">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="divider_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="padding-bottom:10px;">
																<div class="alignment" align="center">
																	<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
																		<tr>
																			<td class="divider_inner" style="font-size: 1px; line-height: 1px; border-top: 1px solid #DFDFDF;"><span style="word-break: break-word;">&#8202;</span></td>
																		</tr>
																	</table>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
                        </table>';

        }

        $email_body .= '<table class="row row-12" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 680px; margin: 0 auto;" width="680">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 15px; padding-top: 35px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="button_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="padding-bottom:20px;padding-left:10px;padding-right:10px;padding-top:10px;text-align:center;">
																<div class="alignment" align="center"><!--[if mso]>
<v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://www.example.com/" style="height:64px;width:328px;v-text-anchor:middle;" arcsize="55%" stroke="false" fillcolor="#de4d4c">
<w:anchorlock/>
<v:textbox inset="0px,0px,0px,0px">
<center dir="false" style="color:#ffffff;font-family:Arial, sans-serif;font-size:22px">
<![endif]--><a href="' . home_url('/' . $post_archive_slug) . '" target="_blank" style="background-color:#de4d4c;border-bottom:0px solid #2F7D81;border-left:0px solid #2F7D81;border-radius:35px;border-right:0px solid #2F7D81;border-top:0px solid #2F7D81;color:#ffffff;display:inline-block;font-family:"Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;font-size:22px;font-weight:400;mso-border-alt:none;padding-bottom:10px;padding-top:10px;text-align:center;text-decoration:none;width:auto;word-break:keep-all;"><span style="word-break: break-word; padding-left: 45px; padding-right: 45px; font-size: 22px; display: inline-block; letter-spacing: normal;"><span style="word-break: break-word;"><span style="word-break: break-word; line-height: 44px;" data-mce-style>FIND YOUR DREAM JOB</span></span></span></a><!--[if mso]></center></v:textbox></v:roundrect><![endif]--></div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-13" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 680px; margin: 0 auto;" width="680">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 30px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="divider_block block-1" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad">
																<div class="alignment" align="center">
																	<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
																		<tr>
																			<td class="divider_inner" style="font-size: 1px; line-height: 1px; border-top: 1px solid #E0E0E0;"><span style="word-break: break-word;">&#8202;</span></td>
																		</tr>
																	</table>
																</div>
															</td>
														</tr>
													</table>
													<table class="paragraph_block block-2" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="color:#a6a4a2; text-align:center; font-family:"Roboto Slab",Arial,"Helvetica Neue",Helvetica,sans-serif;font-size:12px;line-height:150%;text-align:center;mso-line-height-alt:18px;">
																	<p style="margin: 0; display:none; word-break: break-word;"><span style="word-break: break-word;">This message was sent to <a style="text-decoration: none; color: #a6a4a2;" title="email@example.com" href="mailto:email@example.com">email@example.com</a></span></p>
																	<p style="margin: 0; word-break: break-word;"><span style="word-break: break-word;">If you no longer wish to receive e-mails from us, <u><a style="text-decoration: none; color: #a6a4a2;" href="' . home_url() . '" target="_blank" rel="noopener">unsubscribe</a></u></span></p>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table><!-- End -->
</body>

</html>';

        // $email_body .= $posts_published_today > 10 ?
        // '<p>Check ' . $posts_published_today - 10 . ' more posts <a href = "' . home_url('/' . $post_archive_slug) . '">here.</a></p>' :
        // '<section><p>Check more posts <a href = "' . home_url('/' . $post_archive_slug) . '">here</a></p>';

        // echo $email_body;

        $headers = ['Content-Type:text/html'];

        $email_addresses = aben_get_users_email();

        // print_r($email_addresses);
        // error_log(var_export($email_addresses, true));

        if (!empty($email_addresses)) {

            foreach ($email_addresses as $index => $email_address) {

                // echo $email_address;
                if (wp_mail($email_address, $email_subject, $email_body, $headers)) {
                    error_log('Email sent to' . $email_address);
                    // unset($email_addresses[$index]);

                    // print_r($email_addresses);

                }
                // echo ('Mail Sent');

                // echo count($email_addresses);

            }
        } else {
            error_log('No user has opted for notification');
        }

    } else {
        echo '<script> console.log("No Posts Found for Today")</script>';
    }

}

// aben_send_email();
