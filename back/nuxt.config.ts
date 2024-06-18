// https://nuxt.com/docs/api/configuration/nuxt-config
import vuetify, { transformAssetUrls } from 'vite-plugin-vuetify'
export default defineNuxtConfig({
    devtools: {
        enabled: true
    },

    build: {
        transpile: ['vuetify'],
    },

    runtimeConfig: {
        public: {
            apiBase: process.env.API_URL_V,
            apiUrl: process.env.API_URL,
            site: process.env.SITE_URL,
            currency: process.env.CURRENCY,
            GOOGLE_MAPS_API_KEY: process.env.GOOGLE_MAPS_API_KEY,
            static: process.env.STATIC,
            pusher_key: process.env.PUSHER_APP_KEY,
            pusher_cluster: process.env.PUSHER_APP_CLUSTER,
            debug: process.env.DEBUG,
        }
    },

    dateFns: {
        locales: ['en'],
    },

    compilerOptions: {
        "types": [
            "@nuxt/types",
            "@nuxtjs/date-fns"
        ]
    },

    nitro: {
        baseURL: process.env.DOMAIN,
        routeRules: {
            '/api/**': {
                proxy: {to: process.env.API_URL_V + '/**'},
            },
            '/broadcasting/**': {
                proxy: {to: process.env.API_BROADCASTING + '/**'},
            }
        },
    },

    routeRules: {
        '/api/': {
            proxy: process.env.API_URL_V,
        },
        '/broadcasting/': {
            proxy: process.env.API_BROADCASTING,
        },
    },

    modules: [
        (_options, nuxt) => {
            nuxt.hooks.hook('vite:extendConfig', (config) => {
                // @ts-expect-error
                config.plugins.push(vuetify({
                    autoImport: true,
                }))
            })
        },
        '@pinia/nuxt',
        '@nuxtjs/i18n',
        '@nuxtjs/google-fonts',
        "nuxt-auth-sanctum"
    ],

    googleFonts: {
        families: {
            Nunito: [400, 700],
            Rubik: [300, 700],
        },
    },

    sanctum: {
        baseUrl: process.env.SITE_URL,
        redirectIfAuthenticated: true,
        endpoints: {
            csrf: '/api/sanctum/csrf-cookie',
            login: '/api/admin/auth/login',
            logout: '/api/admin/auth/logout',
            user: '/api/admin/auth/user',
        },
        globalMiddleware: {
            allow404WithoutAuth: true,
        },
        redirect: {
            keepRequestedRoute: false,
            onLogin: '/',
            onLogout: '/',
            onAuthOnly: '/auth/sign-in',
            onGuestOnly: '/',
        }
    },

    i18n: {
        baseUrl: process.env.SITE_URL,
        strategy: 'prefix',
        langDir: './assets/translations',
        vueI18n: './config/i18n.ts',
        defaultLocale: 'en',
        bundle: {
            fullInstall: false,
        },
        detectBrowserLanguage: {
            useCookie: true,
            cookieKey: 'i18n_redirected',
            redirectOn: 'root',
        },
        lazy: true,
        locales: [
            {
                code: 'en',
                iso: 'en-EN',
                file: 'en-EN.ts'
            }
        ],
    },

    pinia: {
        autoImports: [
            'defineStore',
            ['defineStore', 'definePiniaStore'],
        ],
    },

    vite: {
        vue: {
            template: {
                transformAssetUrls,
            },
        },
    },

    css: [
        '@/assets/css/main.css',
        '@/assets/css/variables.scss',
    ],
})
