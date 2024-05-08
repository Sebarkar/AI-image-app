import {defineStore} from 'pinia'

export const useCoreStore = defineStore('core', () => {
    const {locale} = useI18n();

    const currencies = ref([])
    const languages = ref([])
    const expires = new Date();
    const appLoaded = ref(false);

    expires.setTime(expires.getTime() + (365 * 24 * 60 * 60 * 1000));

    const currency = useCookie('currency', {
            default: () => ('EUR'),
            expires: expires,
        }
    )

    const language = useCookie('language', {
            default: () => (locale.value),
            expires: expires
        }
    )

    return {
        currencies,
        languages,
        currency,
        language,
        appLoaded,
        fetch
    };
})
