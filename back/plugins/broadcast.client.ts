import Echo from '@ably/laravel-echo';
import * as Ably from 'ably';

export default defineNuxtPlugin(() => {
    const config = useRuntimeConfig()
    window.Ably = Ably;
    window.Echo = new Echo({
        broadcaster: 'ably',
        useTls: true,
    });

    window.Echo.connector.ably.connection.on(stateChange => {
        if (stateChange.current === 'connected') {
            console.log('connected to ably server');
        }
        if (stateChange.current === 'disconnected' && stateChange.reason?.code === 40142) { // key/token status expired
            console.log("LOGGER:: Connection token expired https://help.ably.io/error/40142");
        }
    });
})
