<?php
/*
Plugin Name: Sitewide Coloring
Description: Color your website with banners just in one click.
Version: 1.1
Author: Mobilunity
License: GPLv2 or later
*/

class SiteWideColoring {

    private $positions = array(
        'Above',
        'Below',
        'Left',
        'Right'
    );
    private $paragraphs = array(
        '1st paragraph',
        '2nd paragraph',
        '3rd paragraph',
        '4th paragraph',
        '5th paragraph',
        'last paragraph'
    );
    private $display = array(
        'everywhere' => 'Show on all pages and posts',
        'all_pages' => 'Show on all pages',
        'all_posts' => 'Show on all posts',
        'exclude_home' => 'Show everywhere except Home page',
        'if_page_exclude_id' => 'Show on pages, excluding the following ID(s):',
        'if_post_exclude_id' => 'Show on posts, excluding the following ID(s):',
        'if_post_and_page_exclude_id' => 'Show on pages and posts, excluding the following ID(s):',
        'on_special_id' => 'Show on following ID(s):',
        'on_pages_with_special_id' => 'Show on pages with following ID(s):',
        'on_posts_with_special_id' => 'Show on posts with following ID(s):',
        'on_pages_with_special_id_and_parentid' => 'Show on pages with following ID(s) and parent ID(s):',
    );
    public $box = array();
    public $mobileDetect;

    public function __construct() {
        require_once 'MobileDetect/Mobile_Detect.php';
        $this->mobileDetect = new Mobile_Detect;
        add_action('setup_theme', array($this, 'themeSetup'));
        $this->registrationHooks();
        $this->updateBox();

    }

    private function updateBox() {
        $sitewide_box = $this->getParam("sitewide_box");
        if (!$sitewide_box || empty($sitewide_box)) {
            return false;
        }
        $this->box = json_decode($this->getParam("sitewide_box"));
    }

    public function themeSetup() {
        add_action('admin_menu', [$this, 'addNavigationItems']);
    }

    public function addNavigationItems() {
        add_menu_page('SiteWide Banners', 'SiteWide Banners', 'manage_options', 'sitewide', [$this, 'renderSettingsPage']);
    }

    public function renderSettingsPage() {
        wp_enqueue_style('sitewide-style', plugins_url('assets/css/sitewide.css', __FILE__ ), array(), false);
        wp_enqueue_script('sitewide_script', plugins_url('assets/js/sitewide.js', __FILE__ ), array(), false);
        $options = [
            'sitewide_box' => $this->getParam('sitewide_box'),
            'sitewide_rules' => $this->getParam('sitewide_rules'),
            'sitewide_slugs' => $this->getParam('sitewide_slugs'),
            'sitewide_ids' => $this->getParam('sitewide_ids'),
        ];
        $errors = $this->save($options);
        ob_start();
        ob_implicit_flush(false);
        require(dirname(__FILE__) . "/views/index.php");
        echo ob_get_clean();
    }

    public function datafeedr_ads_filter($content) {
        preg_match_all('/\[ad-([^\]]+)\]/six', $content, $matches);

        if (!empty($matches)) {
            $codes = $matches[0];
            $ids = $matches[1];

            for ($i = 0; $i < count($codes); $i++) {
                $code = $codes[$i];
                $id = $ids[$i];
                $ad = '';
                if (function_exists('dfrads')) {
                    $ad = dfrads($id);
                }
                $content = str_replace($code, $ad, $content);
            }

            return $content;
        }

        return $content;
    }

    public function registrationHooks() {
        add_filter('the_content', array($this, 'addSiteWiding'), 10);
        add_filter('the_content', 'do_shortcode', 11);
        add_filter('the_content', array($this, 'datafeedr_ads_filter'));
        wp_enqueue_style('sitewide-style', plugins_url('assets/css/sitewide.css', __FILE__ ), array(), false);
    }

    public function addSiteWiding($content) {
        global $post;
        ob_start();
        ob_implicit_flush(false);
        $sitewide_box = $this->getParam("sitewide_box");
        $trim_sitewide_box = trim($sitewide_box);
        $boxes = (empty($trim_sitewide_box)) ? array() : json_decode($sitewide_box);
        $settings = $this->getParam("sitewide_display_rules");
        $is_mobile = $this->mobileDetect->isMobile();
        $slugs_settings = preg_split("/\s+/", $this->getParam("sitewide_slugs"), -1, PREG_SPLIT_NO_EMPTY);
        $array_filtered = preg_split("/\s+/", $this->getParam("sitewide_page_ids"), -1, PREG_SPLIT_NO_EMPTY);
        $postid = get_the_ID();
        $show = false;
        if ($this->doNotDisplay($slugs_settings) && !$is_mobile) {
            return $content;
        }
        if ($settings == 'everywhere') {
            if (is_page() || is_single()) {
                $show = true;
            }
        } else if ($settings == 'all_pages') {
            if (is_page()) {
                $show = true;
            }
        } else if ($settings == 'all_posts') {
            if (is_single()) {
                $show = true;
            }
        } else if ($settings == 'exclude_home') {
            if (!is_home() && !is_front_page()) {
                $show = true;
            }
        } else if ($settings == 'if_page_exclude_id') {
            if (is_page()) {
                if (sizeof($array_filtered) > 0 && !in_array($postid, $array_filtered)) {
                    $show = true;
                }
            }
        } else if ($settings == 'if_post_exclude_id') {
            if (is_single()) {
                if (sizeof($array_filtered) > 0 && !in_array($postid, $array_filtered)) {
                    $show = true;
                }
            }
        } else if ($settings == 'if_post_and_page_exclude_id') {
            if (is_single() || is_page()) {
                if (sizeof($array_filtered) > 0 && !in_array($postid, $array_filtered)) {
                    $show = true;
                }
            }
        } else if ($settings == 'on_special_id') {
            if (sizeof($array_filtered) > 0 && in_array($postid, $array_filtered)) {
                $show = true;
            }
        } else if ($settings == 'on_pages_with_special_id') {
            if (is_page()) {
                if (sizeof($array_filtered) > 0 && in_array($postid, $array_filtered)) {
                    $show = true;
                }
            }
        } else if ($settings == 'on_posts_with_special_id') {
            if (is_single()) {
                if (sizeof($array_filtered) > 0 && in_array($postid, $array_filtered)) {
                    $show = true;
                }
            }
        } else if ($settings == 'on_pages_with_special_id_and_parentid') {
            if (is_page()) {
                if (sizeof($array_filtered) > 0) {
                    if (in_array($postid, $array_filtered) || in_array($prntid, $array_filtered)) {
                        $show = true;
                    }
                }
            }
        }
        if ($is_mobile) {
            $show = true;
            if (is_page('quote')) {
                $show = false;
            }
        }

        if (is_page('privacy-policy') || is_page('cookie-policy')) {
            $show = false;
        }

        if ($show && $content) {
            $dom = new DOMDocument();
            $content = do_shortcode($content);
            libxml_use_internal_errors(true);
            $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'utf-8'));
            $elems = $dom->getElementsByTagName('body');
            $elements = $elems->item(0)->childNodes;
            $buffer = array();
            $buffer_size = 0;
            foreach ($elements as $value) {
                if ($value->nodeName == 'p' || $value->nodeName == 'ul' || $value->nodeName == 'ol') {
                    $buffer[] = $value;
                    $buffer_size++;
                }
            }

            if (empty($buffer)) {
                return $content;
            }
            $content = '';
            $paragraph = 1;
            $original_buffer = $buffer;
            foreach ($buffer as $key => $part) {
                if (trim($part->textContent) != '' || strstr(trim($part->textContent), '&nbsp')) {
                    $part_text = $part;
                    foreach ($boxes as $box) {
                        if ($box->paragraph == "last paragraph")
                            $box_paragraph = $buffer_size;
                        else
                            $box_paragraph = intval($box->paragraph);
                        if ($paragraph != $box_paragraph) {
                            $buffer[$key] = $part_text;
                            continue;
                        }
                        if ($is_mobile) {
                            if (empty($box->mobile_content)) {
                                continue;
                            }
                        } else {
                            if (empty($box->desktop_content)) {
                                continue;
                            }
                        }
                        $banner = ($is_mobile) ? '<div class="mobile_banner">' . $box->mobile_content . '</div>' : '<div class="desktop_banner">' . $box->desktop_content . '</div>';
                        switch ($box->position) {
                            case 'Below':

                                $banner_text = "<div class='center-sidewide-align'>" . $banner . "</div>";
                                $banner_obj = new DOMDocument();
                                $banner_obj->loadHTML(mb_convert_encoding($banner_text, 'HTML-ENTITIES', 'utf-8'));
                                $banner_obj_elems = $banner_obj->getElementsByTagName('body');
                                $banner_obj_elements = $banner_obj_elems->item(0)->firstChild;
                                if (($box->paragraph != "last paragraph")) {
                                    try {
                                        $banner_obj_element = $dom->importNode($banner_obj_elements, true);
                                        $elems->item(0)->insertBefore($banner_obj_element, $part_text->nextSibling);
                                    } catch (\Exception $e) {
                                        $banner_obj_element = $dom->importNode($banner_obj_elements, true);
                                        $elems->item(0)->appendChild($banner_obj_element);
                                    }
                                } else {
                                    $banner_obj_element = $dom->importNode($banner_obj_elements, true);
                                    $elems->item(0)->appendChild($banner_obj_element);
                                }
                                break;
                            case 'Left':
                                $banner_text = ($is_mobile) ? "<div class='center-sidewide-align'>$banner</div>" : "<div class='left-sidewide-align'>$banner</div>";
                                $banner_obj = new DOMDocument();
                                $banner_obj->loadHTML(mb_convert_encoding($banner_text, 'HTML-ENTITIES', 'utf-8'));
                                $banner_obj_elems = $banner_obj->getElementsByTagName('body');
                                $banner_obj_elements = $banner_obj_elems->item(0)->firstChild;
                                try {
                                    $banner_obj_element = $dom->importNode($banner_obj_elements, true);
                                    $elems->item(0)->insertBefore($banner_obj_element, $part_text);
                                } catch (\Exception $e) {
                                    $banner_obj_element = $dom->importNode($banner_obj_elements, true);
                                    $elems->item(0)->appendChild($banner_obj_element);
                                }
                                break;
                            case 'Right':
                                $banner_text = ($is_mobile) ? "<div class='center-sidewide-align'>$banner</div>" : "<div class='right-sidewide-align'>$banner</div>";

                                $banner_obj = new DOMDocument();
                                $banner_obj->loadHTML(mb_convert_encoding($banner_text, 'HTML-ENTITIES', 'utf-8'));
                                $banner_obj_elems = $banner_obj->getElementsByTagName('body');
                                $banner_obj_elements = $banner_obj_elems->item(0)->firstChild;
                                try {
                                    $banner_obj_element = $dom->importNode($banner_obj_elements, true);
                                    $elems->item(0)->insertBefore($banner_obj_element, $part_text);
                                } catch (\Exception $e) {
                                    $banner_obj_element = $dom->importNode($banner_obj_elements, true);
                                    $elems->item(0)->appendChild($banner_obj_element);
                                }
                                break;
                            default :
                                $banner_text = "<div class='center-sidewide-align'>" . $banner . "</div>";
                                $banner_obj = new DOMDocument();
                                $banner_obj->loadHTML(mb_convert_encoding($banner_text, 'HTML-ENTITIES', 'utf-8'));
                                $banner_obj_elems = $banner_obj->getElementsByTagName('body');
                                $banner_obj_elements = $banner_obj_elems->item(0)->firstChild;
                                if ($box->paragraph != "1st paragraph") {
                                    if (($box->paragraph == "last paragraph")) {
                                        $banner_obj_element = $dom->importNode($banner_obj_elements, true);
                                        $elems->item(0)->insertBefore($banner_obj_element, $elems->item(0)->lastChild);
                                    } else {
                                        $part_text = $original_buffer[$key - 1];
                                        try {
                                            $banner_obj_element = $dom->importNode($banner_obj_elements, true);
                                            $elems->item(0)->insertBefore($banner_obj_element, $part_text->nextSibling);
                                        } catch (\Exception $e) {
                                            $banner_obj_element = $dom->importNode($banner_obj_elements, true);
                                            $elems->item(0)->appendChild($banner_obj_element);
                                        }
                                    }
                                } else {
                                    $banner_obj_element = $dom->importNode($banner_obj_elements, true);
                                    $elems->item(0)->insertBefore($banner_obj_element, $elems->item(0)->firstChild);
                                }
                                break;
                        }
                        $buffer[$key] = $part_text;
                    }
                    $paragraph++;
                }
            }
            $dom_save = $dom->saveHTML();
            $pattern = array('/<!DOCTYPE\s.*dtd">/', '/<\/?html>/', '/<\/?body>/');
            $content = preg_replace($pattern, '', $dom_save);
        }

        libxml_use_internal_errors(false);
        return $content;
    }

    private function doNotDisplay($slugs_settings) {
        foreach ($slugs_settings as $value) {
            if (strstr(strtolower($this->sitemanager->request()->server('REQUEST_URI')), $value)) {
                return true;
            }
            continue;
        }
        return false;
    }

    public function initShortCodeSquareBanner($atts, $content = null) {
        extract(shortcode_atts(array(
            'type' => ''
                        ), $atts));
        $type = (!empty($type)) ? $type : 1;
        $dir = plugin_dir_path(__FILE__) . 'banners/square/' . $type;
        $files = glob($dir . '/*.*');
        $file = array_rand($files);
        $folder_url = plugin_dir_path(__FILE__) . 'banners/square/' . $type . '/';
        $banner = '<a rel="nofollow" href="/order/"><img src="' . $folder_url . basename($files[$file]) . '"/></a>';
        return $banner;
    }

    public function initShortCodeHorizontalBanner($atts, $content = null) {
        extract(shortcode_atts(array(
            'type' => ''
                        ), $atts));
        $type = (!empty($type)) ? $type : 1;
        $dir = plugin_dir_path(__FILE__) . 'banners/horizontal/' . $type;
        $files = glob($dir . '/*.*');
        $file = array_rand($files);
        $folder_url = plugin_dir_path(__FILE__) . 'banners/horizontal/' . $type . '/';
        $banner = '<a rel="nofollow" href="/order/"><img src="' . $folder_url . basename($files[$file]) . '"/></a>';
        return $banner;
    }

    private function stripslashes_deep($value) {
        $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);

        return $value;
    }

    private function save(&$options) {
        $errors = array();
        if ($attr = $_POST["sitewide"]) {
            if (get_magic_quotes_gpc()) {
                $attr = $this->stripslashes_deep($attr);
            }
            $options['save'] = true;
            $box = array();
            foreach ($attr['desktop_content'] as $key => $value) {
                if (empty($attr['desktop_content'][$key]) && empty($attr['mobile_content'][$key])) {
                    continue;
                }
                $box[] = array(
                    'desktop_content' => $attr['desktop_content'][$key],
                    'mobile_content' => $attr['mobile_content'][$key],
                    'position' => $attr['position'][$key],
                    'paragraph' => $attr['paragraph'][$key],
                );
            }
            $box_encode = json_encode($box);
            $options["sitewide_box"] = ($box_encode);
            $this->box = $options["sitewide_box"];
            $this->updateParam("sitewide_box", $box_encode);
            $options["sitewide_display_rules"] = $attr['display_rules'];
            $this->updateParam("sitewide_display_rules", $attr['display_rules']);
            $options["sitewide_slugs"] = $attr['slugs'];
            $this->updateParam("sitewide_slugs", $attr['slugs']);
            $options["sitewide_page_ids"] = $attr['page_ids'];
            $this->updateParam("sitewide_page_ids", $attr['page_ids']);
        }
        return false;
    }

    public function shortcodeBanner($atts, $content = null) {
        extract(shortcode_atts(array(
            'name' => '',
            'href' => '',
                        ), $atts));

        $filenames = array();
        $files = glob(plugin_dir_path(__FILE__) . 'assets/banners/' . "*", GLOB_BRACE);

        foreach ($files as $file) {
            $f = explode(".", basename($file));
            $filenames[] = $f[0];
        }

        if (isset($name)) {
            if (array_search($name, $filenames) !== false) {
                $el = array_search($name, $filenames);
                //                $form = file_get_contents($files[$el]);
                ob_start();
                require_once($files[$el]);
                $form = ob_get_clean();
                if (isset($href))
                    $form = preg_replace('/href="#"/', 'href="' . $href . '"', $form);

                return $form;
            }
        }
    }

    static function remove() {
        global $boot;
        remove_filter('the_content', array($boot->sitewide, 'addSiteWiding'), 10);
        remove_filter('the_content', 'do_shortcode', 11);
        remove_filter('the_content', array($boot->sitewide, 'datafeedr_ads_filter'));
    }

    private function updateParam($param, $value) {
        if ($this->getParam($param) === false) {
            add_option($param, $value);
        } else {
            update_option($param, $value);
        }
        return true;
    }

    private function getParam($param) {
        return get_option($param, false);
    }

}

$sitewide = new SiteWideColoring();