import type { Config } from 'tailwindcss'

import colors from 'tailwindcss/colors'

export default <Partial<Config>>{
    theme: {
        extend: {
            colors: {
                success: colors.green,
                error: colors.red,
                v1primary: colors.blue,
                v1secondary: colors.blue,
                v1base: '#BDBDBD',
            },
            fontSize: {
                '2xs': ['0.65rem', {
                    lineHeight: '0.6rem',
                    letterSpacing: '-0.01em',
                    fontWeight: '500',
                }],
            },
        }
    }
}
