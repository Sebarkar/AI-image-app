import {defineStore} from 'pinia'

export const useGuestStore = defineStore('guest', () => {
    const listener = useListenerStore();
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

    const guest_id = useCookie('guest_id', {
            default: () => null,
            expires: expires
        }
    )

    const guest_token = useCookie('guest_token', {
            default: () => null,
            expires: expires
        }
    )

    return {
        currencies,
        languages,
        currency,
        language,
        guest_id,
        guest_token,
        appLoaded
    };
})
