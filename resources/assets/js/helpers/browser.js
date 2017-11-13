export const sleep = (milliseconds) => {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds){
            break;
        }
    }
}


export const flashToTitle = (message) => {
    let originalTitle = document.title;
    let seperator = ".";
    for(let i = 0; i < 10; i++) {

        sleep(250)
        if (i % 2) {
            document.title = message
        } else {
            // seperator += "."
            document.title = seperator
        }
    }

    document.title = originalTitle
}
