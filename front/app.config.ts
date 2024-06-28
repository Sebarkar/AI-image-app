export default defineAppConfig({
    nuxtIcon: {
        aliases: {
            success: 'i-material-symbols-light-check-circle-outline-rounded',
            error: 'i-material-symbols-light-error-outline-rounded',
            info: 'i-material-symbols-light-info-outline',
            add: 'i-material-symbols-add-circle-outline',
            close: 'i-material-symbols-close-rounded',
            remove: 'i-ic-outline-remove-circle-outline',
            signOut: 'i-material-symbols-light-power-settings-new-outline',
            signIn: 'i-material-symbols-light-power-settings-new-outline',
            signUp: 'i-material-symbols-light-assignment-ind-outline-sharp',
            loading: 'i-heroicons-arrow-path-20-solid',
            upload: 'i-mdi-cloud-upload-outline',
            await: 'i-pajamas-status-waiting',
        },
    },
    ui: {
        base: 'purple', //60% color
        gray: 'v1primary', //30% color
        icons: {
            dynamic: true,
        },
        input: {
            default: {
                size: 'xl',
                color: 'white',
                variant: 'outline',
                loadingIcon: 'loading',
            },
        },
        textarea: {
            default: {
                size: 'xl',
            }
        },
        selectMenu: {
            default: {
                size: 'xl',
            }
        },
        button: {
            default: {
                size: 'xl',
                loadingIcon: 'loading',
            },
        },
        container: {
            base: 'mx-auto',
            padding: '',
            constrained: 'max-w-full',
        }
    }
})
