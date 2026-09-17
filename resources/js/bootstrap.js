import axios from "axios";
import Pusher from "pusher-js";
import Echo from "laravel-echo";

window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

window.Pusher = Pusher;

if (typeof PUSHER !== "undefined" && PUSHER?.key) {
  const csrfToken =
    document.head.querySelector('meta[name="csrf-token"]')?.getAttribute("content");
  const authEndpoint = PUSHER.authEndpoint ?? "/broadcasting/auth";

  window.Echo = new Echo({
    broadcaster: "pusher",
    key: PUSHER.key,
    cluster: PUSHER.cluster ?? "ap1",
    wsHost: `ws-${PUSHER.cluster}.pusher.com` ?? "ws-ap1.pusher.com",
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],
    authEndpoint,
    auth: {
      headers: {
        "X-CSRF-TOKEN": csrfToken,
      },
      withCredentials: true,
    },
  });
}
