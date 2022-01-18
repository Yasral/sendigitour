jQuery(document).ready(function(e) {
    e(".popup-gallery").magnificPopup({
        delegate: "a",
        type: "image",
        gallery: {
            enabled: !0
        }
    }), e(".popup-modal").magnificPopup({
        type: "inline",
        preloader: !1,
        focus: "#username",
        modal: !0
    }), e(document).on("click", ".popup-modal-dismiss", function(p) {
        p.preventDefault(), e.magnificPopup.close()
    });

    if (e.fn.Lazy) {
        e('.btif-lazy-load').Lazy({
            // your configuration goes here
            scrollDirection: 'vertical',
            effect: 'fadeIn',
            visibleOnly: true,
            // called after an element was successfully handled
            afterLoad: function(element) {
                var imageAlt = element.data('alt');
                element.attr("alt", imageAlt);
                element.removeAttr( "data-alt" )
            },
            onError: function(element) {
                console.log('error loading ' + element.data('src'));
            },
        });
    }
});