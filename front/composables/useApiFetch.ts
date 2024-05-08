import type {UseFetchOptions} from 'nuxt/app'
import {useRequestHeaders} from "nuxt/app";

export function useApiFetch<T>(path: string, options: UseFetchOptions<T> = {}) {
    const settings = useRuntimeConfig();

    let headers: any = {
        accept: "application/json",
        referer: settings.public.site,
        origin: settings.public.site,
    }

    const token = useCookie('XSRF-TOKEN');

    if (token.value) {
        headers['X-XSRF-TOKEN'] = token.value as string;
    }

    if (!options.method) {
        options.method = 'POST'
    }

    if (options.method !== 'GET') {
        options.body = Object.assign({}, options.body, {

        })
    }

    const authToken = useCookie('access_token');

    if (authToken.value) {
        headers['Authorization'] = 'Bearer ' + authToken.value as string;
        headers['Content-Type'] = 'Bearer ' + authToken.value as string;
    }

    headers = {
        ...headers,
        ...useRequestHeaders(["cookie", "x-forwarded-for"]),
    }

    return $fetch('/api/' + path, {
        onRequest({request, options}) {
            options.headers = headers
        },
        onResponseError(context: FetchContext & { response: FetchResponse<R> }): Promise<void> | void {
            handleApiError(context.response)
        },
        credentials: 'include',
        headers: {
            ...headers,
            ...options?.headers
        },
        ...options,
    });
}
