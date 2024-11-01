jQuery(document).ready(function () {
    
    // SiteWide admin settings start;
    jQuery('input[type=radio][name=banner_device_toggle]').change(function () {
        if (this.value == '0') {
            jQuery('.desktop_content_block').show('');
            jQuery('.mobile_content_block').hide();
        }
        else if (this.value == '1') {
            jQuery('.mobile_content_block').show('');
            jQuery('.desktop_content_block').hide();
        }
    });

    if (jQuery('#display_rules').val() == 'everywhere') {
        jQuery('#sitewide_page_ids').prop("disabled", true);
    }

    jQuery('#display_rules').change(function () {
        var val = jQuery('#display_rules').val();
        if (val != 'everywhere' && val != 'all_pages' && val != 'all_posts' && val != 'exclude_home') {
            jQuery('#sitewide_page_ids').prop("disabled", false);
        } else {
            jQuery('#sitewide_page_ids').prop("disabled", true);
        }
    });
    // SiteWide admin settings end;




});



WebFontConfig = {
    google: {families: ['Roboto:300,400:latin', 'Roboto+Condensed:400,300:latin']}
};
(function () {
    var wf = document.createElement('script');
    wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
            '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
    wf.type = 'text/javascript';
    wf.async = 'true';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(wf, s);
})();
