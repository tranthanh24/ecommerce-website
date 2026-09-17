if (window.Echo && typeof USER !== "undefined" && USER?.id) {
  window.Echo.private(`chat.${USER.id}`).listen("MessageEvent", (e) => {
    if (typeof renderMessage === "function") {
      renderMessage(e);
    } else {
      window.__chatPendingMessages = window.__chatPendingMessages || [];
      window.__chatPendingMessages.push(e);
    }

    document.querySelectorAll(".seller-profile").forEach((btn) => {
      const inboxId = btn.dataset.id;

      if (inboxId == e.sender_id) {
        const imgDiv = btn.querySelector(".wsus_chat_list_img");
        if (imgDiv) imgDiv.classList.add("msg-notification");
      }
    });
  });
}
