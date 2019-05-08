jQuery("#business-directory").owlCarousel({
    loop:true,
    margin: 5,
    nav: false,
    autoplay: true,
    autoplayHoverPause: true,
    checkVisible: false,
    responsive:{
        0:{
            items:1
        },
        768:{
            items:3
        },
        1087:{
            items:5
        }
    }
})