export default defineAppConfig({
    nuxtIcon: {
        aliases: {
            success: 'i-material-symbols-light-check-circle-outline-rounded',
            error: 'i-material-symbols-light-error-outline-rounded',
            info: 'i-material-symbols-light-info-outline',
            signOut: 'i-material-symbols-light-power-settings-new-outline',
            signIn: 'i-material-symbols-light-power-settings-new-outline',
            signUp: 'i-material-symbols-light-assignment-ind-outline-sharp',
        },
    },
    ui: {
        primary: 'purple',
        gray: 'cool',
        icons: {
            dynamic: true,
        },
    }
})
