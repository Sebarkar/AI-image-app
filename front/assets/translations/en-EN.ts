export default defineI18nLocale(locale => {
    return {
        'errors': {
            'string_min_length': 'Minimum {number} characters',
            'invalid email': 'Invalid email format',
            'too many request': 'Too many request',
            'messages': {
                'please wait to request new one': 'Please wait to request new one',
                'retry after': 'Retry after {retryAfter}',
                'we already know this problem': 'We already know this problem'
            }
        },
        plurals: {
            seconds: 'second | 1 second | {count} seconds | {count} seconds'
        }
    }
})
