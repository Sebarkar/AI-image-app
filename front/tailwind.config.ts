import type { Config } from 'tailwindcss'

import colors from 'tailwindcss/colors'

export default <Partial<Config>>{
    theme: {
        extend: {
            colors: {
                success: colors.green,
                error: colors.red,
            }
        }
    }
}
