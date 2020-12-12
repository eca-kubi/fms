$(document).ready(function() {
    // Configure/customize these variables.
    let showChar = 250;  // How many characters are shown by default
    let ellipsestext = "...";
    let moretext = "Show more >";
    let lesstext = "Show less";


    $('.more').each(function() {
        let content = $(this).html();

        if(content.length > showChar) {

            let c = content.substr(0, showChar);
            let h = content.substr(showChar, content.length - showChar);

            let html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

            $(this).html(html);
        }

    });

    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
});
