jQuery(document).ready(function($) {
    $(".btss-tab-trigger").on('click', function() {
        $(".btss-tab-trigger").removeClass("nav-tab-active"), $(this).addClass("nav-tab-active");
        var s = $(this).data("configuration");
        $(".blossomthemes-instagram-feed-settings").hide(), $("#blossomthemes-instagram-feed-settings-" + s).show();
    });

     // Fetched new instagram posts.
     $("#btif-fetch-new-posts").on('click', function(event){
        $.ajax({
            url: blossomthemesInstagramFeed.ajaxurl,
            type: 'post',
            dataType: 'json',
            data: { action: 'btif_fetch_new_posts'},
            beforeSend: function(xhr) {
                jQuery('#btif-fetch-new-posts-loader').css('display', 'inline');
                jQuery('#btif-fetch-new-posts-failure').css('display', 'none');
                jQuery('#btif-fetch-new-posts-success').css('display', 'none');
            }
        }).done(function(data){
            jQuery('#btif-fetch-new-posts-loader').css('display', 'none');
            if ( data.success) {
                jQuery('#btif-fetch-new-posts-success').css('display', 'inline');
            } else {
                jQuery('#btif-fetch-new-posts-failure').css('display', 'inline');
            }
        }).fail(function(data){
            jQuery('#btif-fetch-new-posts-failure').css('display', 'inline');
            jQuery('#btif-fetch-new-posts-loader').css('display', 'none');
        });
    });
});