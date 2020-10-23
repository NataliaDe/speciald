// здесь нужная дата в формате гггг-мм-дд чч:мм:сс
//var deadline='2020-01-20 00:00:00';
function dateAddTimeMsg(date, interval, units) {
    if (!(date instanceof Date))
        return undefined;
    var ret = new Date(date); //don't change original date
    var checkRollover = function () {
        if (ret.getDate() != date.getDate())
            ret.setDate(0);
    };
    switch (String(interval).toLowerCase()) {
        case 'year'   :
            ret.setFullYear(ret.getFullYear() + units);
            checkRollover();
            break;
        case 'quarter':
            ret.setMonth(ret.getMonth() + 3 * units);
            checkRollover();
            break;
        case 'month'  :
            ret.setMonth(ret.getMonth() + units);
            checkRollover();
            break;
        case 'week'   :
            ret.setDate(ret.getDate() + 7 * units);
            break;
        case 'day'    :
            ret.setDate(ret.getDate() + units);
            break;
        case 'hour'   :
            ret.setTime(ret.getTime() + units * 3600000);
            break;
        case 'minute' :
            ret.setTime(ret.getTime() + units * 60000);
            break;
        case 'second' :
            ret.setTime(ret.getTime() + units * 1000);
            break;
        default       :
            ret = undefined;
            break;
    }
    return ret;
}


function start_timer_time_msg(start, during_timer){

var end = dateAddTimeMsg(new Date(start), 'minute', during_timer);
//alert(deadline);
$('.timer_time_msg').downCount({
    date: end,
},
        function () {
            /* действие после завершения таймера */
            //alert("Время истекло!");
            $('.timer_time_msg').find('#end_timer_time_msg').removeClass('black_end_timer_time_msg');
            playAudio();
//            $('.timer_time_msg').find('#end_timer_time_msg').removeClass('red_end_timer_time_msg');
        });
}
