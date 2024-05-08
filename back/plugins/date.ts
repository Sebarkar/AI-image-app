import {format, parse, parseISO, getDay, isSaturday, isSunday} from 'date-fns'
import { uk, ru } from 'date-fns/locale'

const datefns: any = {
    parse,
    parseISO,
    getDay,
    isSaturday,
    isSunday
}

const locales = {uk, ru};
export default defineNuxtPlugin(() => {
    const { $i18n } = useNuxtApp()
    return {
        provide: {
            dateFns: datefns,
            format: (date, formate = 'y-MM-dd') => format(date, formate, {'locale': locales[$i18n.locale._value]})
        }
    }
})