if (window.Echo && typeof USER !== "undefined" && USER?.id) {
  window.Echo.private(`chat.${USER.id}`).listen("MessageEvent", (e) => {
    if (typeof renderMessage === "function") {
      renderMessage(e);
    } else {
      window.__chatPendingMessages = window.__chatPendingMessages || [];
      window.__chatPendingMessages.push(e);
    }

    document.querySelectorAll(".user-profile").forEach((btn) => {
      const inboxId = btn.dataset.id;

      if (inboxId == e.sender_id) {
        const imgDiv = btn.querySelector(".user-image");
        if (imgDiv) imgDiv.classList.add("msg-notification");
      }
    });
  });
}
