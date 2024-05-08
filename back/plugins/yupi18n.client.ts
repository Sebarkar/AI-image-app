import { setLocale, addMethod, string } from 'yup';

export default defineNuxtPlugin((nuxtApp) => {

    const { $i18n } = useNuxtApp()

    setLocale({
        // use constant translation keys for messages without values
        mixed: {
            default: 'field_invalid',
            required: (val) => $i18n.t('errors.required', { field: $i18n.t('input.' + val.path) }),
        },
        string: {
            min: ({ min }) => $i18n.t('errors.string_min_length', { number: min }),
            email: $i18n.t('errors.invalid email'),
        },
        number: {
            min: ({ min }) => {console.log(({ key: 'errors.field_too_short', values: { min } }))},
            max: ({ max }) => ({ key: 'errors.field_too_big', values: { max } }),
            lessThan: ({ less }) => ({ key: 'errors.field_too_big', values: { less } }),
            moreThan: ({ more }) => ({ key: 'errors.field_too_big', values: { more } }),
        },
    });

    addMethod(string, 'phone', function(isValid) {
        return isValid;
    });
})
