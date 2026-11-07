import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: "reverb",
    // FIXME - Import the key and host
    key: "01M6TE8BB1TA5X3QWETAPA2PBH", // import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: "192.168.1.254", // import.meta.env.VITE_REVERB_HOST,
    wsPort: 80,
    wssPort: 443,
    forceTLS: true,
    enabledTransports: ["ws", "wss"],
});
