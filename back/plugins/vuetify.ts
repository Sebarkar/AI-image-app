// import this after install `@mdi/font` package
import '@mdi/font/css/materialdesignicons.css'
import DateFnsAdapter from '@date-io/date-fns'
import {mdi} from 'vuetify/iconsets/mdi'
import {en} from 'vuetify/locale'
import colors from 'vuetify/util/colors';
import '@/styles/main.scss'
import {createVuetify} from 'vuetify'
import enUS from 'date-fns/locale/en-US'
import { md3 } from 'vuetify/blueprints'

const ICONS = {
    up: 'mdi-chevron-double-up',
    down: 'mdi-chevron-double-down',
    complete: 'mdi-check',
    cancel: 'mdi-cancel',
    close: 'mdi-close',
    delete: 'mdi-delete', // delete (e.g. v-chip close)
    clear: 'mdi-minus',
    cleaning: 'mdi-broom',
    animal: 'mdi-dog',
    clean_sofa: 'mdi-sofa-outline',
    repair: 'mdi-tools',
    shop: 'mdi-cart',
    internet: 'mdi-microsoft-internet-explorer',
    subrent: 'mdi-cash',
    cash: 'mdi-cash',
    dotsVertical: 'mdi-dots-vertical',
    success: 'mdi-check',
    settings: 'mdi-cog',
    robot: 'mdi-robot',
    workers: 'mdi-human-scooter',
    shield: 'mdi-shield-home',
    info: 'mdi-information-outline',
    transfer: 'mdi-taxi',
    task: 'mdi-clipboard-list-outline',
    simcard: 'mdi-sim',
    creditCard: 'mdi-credit-card-clock-outline',
    checkInInfo: 'mdi-car-info',
    review: 'mdi-comment',
    users: 'mdi-human-queue',
    window: 'mdi-window-closed-variant',
    other_service: 'mdi-bell-alert',
    bell: 'mdi-bell-alert',
    warning: 'mdi-alert',
    error: 'mdi-alert-circle',
    play: 'mdi-play-circle',
    pause: 'mdi-pause-circle',
    alert: 'mdi-alert-circle',
    booking: 'mdi-receipt',
    checkboxOn: 'mdi-check-box-outline',
    checkboxOff: 'mdi-checkbox-blank-outline',
    checkboxIndeterminate: 'mdi-checkbox-intermediate',
    delimiter: '...', // for carousel
    sort: 'mdi-sort-descending',
    expand: 'mdi-menu-down',
    menu: 'mdi-menu',
    print: 'mdi-printer',
    nights: 'mdi-moon-waning-crescent',
    source: 'mdi-source-branch',
    percent: 'mdi-percent-outline',
    hide: 'mdi-eye-off-outline',
    show: 'mdi-eye-outline',
    time: 'mdi-clock-time-eight-outline',
    calendar: 'mdi-calendar',
    subgroup: 'mdi-sort-bool-descending',
    language: 'mdi-earth',
    dropdown: 'mdi-menu-down-outline',
    link: 'mdi-link-variant',
    communal: 'mdi-water',
    radioOn: '...',
    radioOff: '...',
    edit: 'mdi-square-edit-outline',
    ratingEmpty: '',
    user: 'mdi-human-male',
    child: 'mdi-human-child',
    search: 'mdi-account-search',
    password: 'mdi-lock',
    pie: 'mdi-chart-pie',
    bars: 'mdi-chart-bar',
    marker: 'mdi-map-marker',
    ratingFull: 'mdi-star',
    ratingHalf: 'mdi-star-half',
    download: 'mdi-download',
    loading: 'mdi-loading',
    first: 'mdi-page-first',
    reload: 'mdi-reload',
    devicesLoad: 'mdi-puzzle-plus',
    last: 'mdi-page-last',
    prev: 'mdi-menu-left-outline',
    next: 'mdi-menu-right-outline',
    login: 'mdi-login-variant',
    logout: 'mdi-logout-variant',
    unfold: '...',
    file: 'mdi-file',
    photo: 'mdi-file-image',
    compare: 'mdi-compare-horizontal',
    sms: 'mdi-cellphone-message',
    message: 'mdi-cellphone-message',
    phone: 'mdi-card-account-phone',
    left: 'mdi-arrow-left-drop-circle-outline',
    building: 'mdi-office-building',
    mail: 'mdi-email',
    userCategory: 'mdi-account-group',
    add: 'mdi-plus',
    bulkAdd: 'mdi-plus-box-multiple-outline',
    rules: 'mdi-plus-box',
};

const lightTheme = {
    dark: false,
    colors: {
        primary: '#00bfff',
        'primary-lighten-3': '#52cbfc',
        'primary-darken-1': '#0090c2',
        'darkprimary': '#05789f',
        'darkprimary2': '#085873',
        'lightprimary': '#19bef8',
        'lightsecondary': '#1f4a70',
        secondary: '#0b253c',
        'secondary-lighten-3': '#4f5f94',
        'secondary-lighten-5': '#6c7593',
        'secondary-darken-1': '#0a1e2f',
        'darksecondary': '#061a2a',
        'darksecondary2': '#07111a',
        menu: colors.purple.darken1,
        btnToolbar: colors.purple.darken1,
        btnToolbarControl: colors.purple.lighten1,
        btnToolbarControlText: 'fff',
        submenu: colors.purple.darken4,
        accent: colors.shades.black,
        error: colors.red.accent3,
        active: colors.green.darken2,
        success: colors.green.darken1,
        textNotImportant: colors.grey.base,
        disabled: colors.grey.lighten1,
        tableBgColorSuccess: colors.green.lighten4,
        tableBgColorDanger: colors.red.lighten4,
    },
    variables: {
        'border-color': '#000000',
        'border-opacity': 0.12,
        'high-emphasis-opacity': 0.87,
        'medium-emphasis-opacity': 0.60,
        'disabled-opacity': 0.38,
        'idle-opacity': 0.04,
        'hover-opacity': 0.04,
        'focus-opacity': 0.12,
        'selected-opacity': 0.08,
        'activated-opacity': 0.12,
        'pressed-opacity': 0.12,
        'dragged-opacity': 0.08,
        'theme-kbd': '#212529',
        'theme-on-kbd': '#FFFFFF',
        'theme-code': '#F5F5F5',
        'theme-on-code': '#000000',
    }
}

export default defineNuxtPlugin((app) => {
    const vuetify = createVuetify({
        blueprint: md3,
        defaults: {
            VCardActions: {
                VBtn: {variant: 'flat', rounded: 'lg'},
            },
            VCard: {
                elevation: 0,
                rounded: 'lg',
                VBtn: {style: 'background: #fff;'},
            },
            VApplication: {style: 'background: #333030;'},
            VAppBar: {
                VDialog: {
                    VBtn: {variant: 'flat', rounded: 'lg'},
                }
            },
            VBtn: {variant: 'flat', rounded: 'lg'},
            VNavigationDrawer: {
                VList: {color: 'primary', baseColor: 'secondary'},
            },
        },
        date: {
            adapter: DateFnsAdapter,
            locale: {
                en: enUS,
            },
        },
        locale: {
            locale: 'en',
            fallback: 'en',
            messages: {en},
        },
        theme: {
            defaultTheme: 'lightTheme',
            themes: {
                lightTheme
            }
        },
        icons: {
            defaultSet: 'mdi',
            aliases: {
                ...ICONS,
            },
            sets: {
                mdi,
            },
        },
    })
    app.vueApp.use(vuetify)
})
