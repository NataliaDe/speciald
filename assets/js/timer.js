// здесь нужная дата в формате гггг-мм-дд чч:мм:сс
//var deadline='2020-01-20 00:00:00';
function dateAdd(date, interval, units) {
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

var deadline = dateAdd(new Date(), 'minute', 60);
//alert(deadline);
$('.countdown').downCount({
    date: deadline,
},
        function () {
            /* действие после завершения таймера */
            alert("Время истекло!");
        });