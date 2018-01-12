function initSpoilers(context)
{
    var context = context || 'body';
    $('div.spoiler-head', $(context))
            .click(function(){
                    $(this).toggleClass('unfolded');
                    $(this).next('div.spoiler-body').slideToggle();
    });
}

$(document).ready(function(){
    initSpoilers('body');
});