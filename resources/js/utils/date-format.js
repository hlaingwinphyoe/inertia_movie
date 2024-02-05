import moment from "moment";

export function dateFormat(dtime) {
    return moment(dtime).fromNow();
}
