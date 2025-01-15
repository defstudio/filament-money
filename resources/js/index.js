// noinspection JSUnusedGlobalSymbols



window.round_number = function(num, scale = 2) {
    if(!("" + num).includes("e")) {
        // noinspection JSCheckFunctionSignatures
        return +(Math.round(num + "e+" + scale)  + "e-" + scale);
    } else {
        var arr = ("" + num).split("e");
        var sig = ""
        if(+arr[1] + scale > 0) {
            sig = "+";
        }
        // noinspection JSCheckFunctionSignatures
        return +(Math.round(+arr[0] + "e" + sig + (+arr[1] + scale)) + "e-" + scale);
    }
}

window.format_money = function(value, scale = 2) {
    return '€ ' + format_number(value, scale);
}

window.format_number = function(value, scale = 2) {
    if (!value) {
        value = 0;
    }

    if(scale !== null){
        value = (round_number(+value, scale)).toFixed(scale);
    }

    return `${value}`.replace('.', ',').replace(/\d(?=(\d{3})+,)/g, '$&.');
}

window.money_color_class = function(value, inverted = false) {
    if (!value) {
        value = 0
    }

    if (inverted) {
        value = -value
    }

    if (+value < 0) {
        return '!text-red-600'
    }

    if (+value > 0) {
        return '!text-green-600'
    }

    return null
}
