<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://rehan.work
 * @since      1.0.0
 *
 * @package    Aben
 * @subpackage Aben/admin/partials
 */

if (!defined('ABSPATH')) {
    exit;
}

class Aben_Email
{

    private $email_subject;
    private $archive_page_slug;
    private $number_of_posts;
    private $unsubscribe_link;
    private $body_bg;
    private $header_text;
    private $header_bg;
    private $header_subtext;
    private $footer_text;
    private $site_logo;
    private $show_view_all;
    private $view_all_posts_text;
    private $show_number_view_all;
    private $show_view_post;
    private $view_post_text;
    private $show_unsubscribe;
    private $posts_to_send;

    public function __construct(
        $email_subject,
        $archive_page_slug,
        $number_of_posts,
        $unsubscribe_link,
        $body_bg,
        $header_text,
        $header_bg,
        $header_subtext,
        $footer_text,
        $site_logo,
        $show_view_all,
        $view_all_posts_text,
        $show_number_view_all,
        $show_view_post,
        $view_post_text,
        $show_unsubscribe,
        $posts_to_send
    ) {
        $this->email_subject = $email_subject;
        $this->archive_page_slug = $archive_page_slug;
        $this->number_of_posts = $number_of_posts;
        $this->unsubscribe_link = $unsubscribe_link;
        $this->body_bg = $body_bg;
        $this->header_text = $header_text;
        $this->header_bg = $header_bg;
        $this->header_subtext = $header_subtext;
        $this->footer_text = $footer_text;
        $this->site_logo = $site_logo;
        $this->show_view_all = $show_view_all;
        $this->view_all_posts_text = $view_all_posts_text;
        $this->show_number_view_all = $show_number_view_all;
        $this->show_view_post = $show_view_post;
        $this->view_post_text = $view_post_text;
        $this->show_unsubscribe = $show_unsubscribe;
        $this->posts_to_send = $posts_to_send;
    }

    public function aben_email_template()
    {
        echo '<!DOCTYPE html><html><head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title></title></head>
        <body>
        <div id="aben-email-template" style="font-family:Open Sans,sans-serif;margin:0;padding:0;background: ' . $this->body_bg . ';color: #1f2430;">
        <div style="width:100%;max-width:500px;margin: auto;">
        <div style="padding:20px;">
        <p id ="header-text"style="font-size:16px"><strong>' . $this->header_text . '</strong>
        <img width="16px" data-emoji="👋" class="an1" alt="👋" aria-label="👋" draggable="false" src="https://fonts.gstatic.com/s/e/notoemoji/15.1/1f44b/72.png" loading="lazy"></p>
        <p id="header-subtext" style="font-size:16px;">' . $this->header_subtext . '</p></div>
        <div id="posts-wrapper" style="padding:10px">';

        foreach ($this->posts_to_send as $post) {
            $title = $post['title'];
            $link = $post['link'];
            $excerpt = $post['excerpt'];
            echo '<div class="post-tile" style="display:flex;margin-bottom:20px;padding:20px;background: white;">
            <div style="width:70%">
            <p style="font-size:16px;margin:0;color: #008dcd;"><a href="' . $link . '" style="text-decoration:none;">' . $title . '</a></p>';
            if (!empty($excerpt)) {
                echo '<p style="font-size:14px;color:#333333;margin:5px 0 0">' . $excerpt . '</p>';
            }
            echo '</div><div style="width:30%;align-content: center;text-align: center;">';
            if ($this->show_view_post) {
                echo '<a class="view-post" href="' . $link . '"style="display:inline-block;padding:5px 20px;color:#fff;text-decoration:none;background-color:#0ead5d;border-radius:25px;height:fit-content">' . $this->view_post_text . '</a>';
            }
            echo '</div></div>';
        }

        echo '<div style="display:flex;padding-bottom:10px;">
        <div style="width:100%;text-align:center;">';
        if ($this->show_view_all) {
            echo '<a id="view-all-post" href="' . $this->archive_page_slug . '"style="display:inline-block;padding:15px 0px;background-color:#165d31;color:#ffffff;text-decoration:none;width: 100%;font-size:16px;">' . $this->view_all_posts_text;
            if ($this->show_number_view_all) {
                echo ' <span id="post-number">(' . $this->number_of_posts . ')';
            }
            echo '</span></a>';
        }
        echo '</div></div></div>
        <div style="color:#808080;text-align:center;padding:20px;">
        <a href="' . home_url() . '"><img src="' . $this->site_logo . '" alt="Site Logo" style="max-width:180px;margin-top: 10px;"></a>
        <p id="footer-text">' . $this->footer_text . '</p>';
        if ($this->show_unsubscribe) {
            echo '<p id="unsubscribe"><a href="' . $this->unsubscribe_link . '" style="color:#808080;text-decoration:none">Unsubscribe</a></p>';
        }
        echo '</div></div></body></html>';
    }
}
