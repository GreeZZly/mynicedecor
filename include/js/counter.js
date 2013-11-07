function startCounter(interval, value, increment){
    window.setTimeout(function() {
        interval = Math.floor((Math.random()*10)+1);
        value = value + increment;
        increment = Math.floor((Math.random()*10)+1);
        prepForAnim(value, 5,500) //можно положить interval*500 чтобы было smooth
        startCounter(interval*500, value, increment);
    }, interval);
}
function prepForAnim(value, length, animTime){
    if (value>999999) value = 999999;
    var nextNum = (''+value).split('');
    var nextArr = [];
    length = length || 5;
    for (var i=length; i>=0; i--){
        nextArr[i] = nextNum.pop() || '0';
    }
    for (var i = 0; i <= length; i++) {
        if ($('#counter_el_'+i+' .visible').text() != nextArr[i]){
            performAnimation('#counter_el_'+i, nextArr[i], animTime);
        } 
    };
}
function performAnimation(element, value, animTime){
    var invisible = $(element+' .invisible');
    var visible = $(element+' .visible');
    animTime = animTime || 500;
    invisible.text(value).show();
    invisible.animate({
        top: 0},
        animTime, function() {
            invisible.attr('class', 'visible')
    });
    visible.animate({
        top: 70},
        animTime, function() {
            visible.attr('class', 'invisible').hide().css('top', '-70px')
    });
}
startCounter(10, 400, 6);