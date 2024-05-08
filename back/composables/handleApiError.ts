export function handleApiError(response: { status: number; headers: { get: Function }, _data: { message: string | undefined } }) {
    const router = useRouter()
    const snackbar = useSnackbarStore();
    const {$i18n} = useNuxtApp()

    if (response.status == 401) {
        snackbar.add({color: 'red', title: $i18n.t('auth.text.Invalid credentials. Please try again.'), icon: 'error'})
    }
    if (response.status == 403) {
        toast.add({color: 'red', title: $i18n.t('errors.Unauthorized'), description: $i18n.t('errors.messages.You are unauthorized. Sign In first.'), icon: 'error'})
    }
    if (response.status == 404) {
        toast.add({color: 'red', title: $i18n.t('errors.wrong request url'), description: $i18n.t('errors.messages.we already know this problem'), icon: 'error'})
    }
    if (response.status == 500) {
        toast.add({color: 'red', title: $i18n.t('errors.server error'), description: response._data.message, icon: 'error'})
    }
    if (response.status == 301) {
        router.replace('/')

    }
    if (response.status == 429) {
        let retryAfter = null;
        let message = '';
        if (response.headers) {
            retryAfter = response.headers.get('Retry-After')
        }
        if (!retryAfter) {
            message = $i18n.t('errors.messages.please wait to request new one')
        } else {
            message = $i18n.t('errors.messages.retry after', {retryAfter: $i18n.t('plurals.seconds', parseInt(retryAfter))})
        }
        toast.add({color: 'red', title: $i18n.t('errors.too many request'), description: message, icon: 'error'})
    }
}
