// noinspection JSUnusedGlobalSymbols

export function round_number(num, scale = 2) {
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

export function format_money(value, scale = 2) {
    return '€ ' + format_number(value, scale);
}

export function format_number(value, scale = 2) {
    if (!value) {
        value = 0;
    }

    if(scale !== null){
        value = (round_number(+value, scale)).toFixed(scale);
    }

    return `${value}`.replace('.', ',').replace(/\d(?=(\d{3})+,)/g, '$&.');
}

export function money_color_class(value, inverted = false) {
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
