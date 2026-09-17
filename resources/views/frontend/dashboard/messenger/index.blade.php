@extends('frontend.dashboard.layouts.master')

@section('title', 'Tin nhắn')

@section('content')
  <section id="wsus__dashboard">
    <div class="container-fluid">
      @include('frontend.dashboard.layouts.sidebar')

      <div class="row">
        <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
          <div class="dashboard_content mt-2 mt-md-0">
            <h3><i class="far fa-sms"></i> Tin nhắn</h3>
            <div class="msg-wrapper">
              <div class="msg-sidebar">
                <div class="msg-sidebar-header">Danh sách</div>

                <div class="msg-user-list">
                  @foreach ($chats as $chat)
                    <div class="msg-user" data-id="{{ $chat->id }}" data-name="{{ $chat->name }}">
                      <img src="{{ asset($chat->image ?: 'frontend/images/avatar.jpg') }}">
                      <span>{{ $chat->name }}</span>
                    </div>
                  @endforeach
                </div>
              </div>

              <div class="msg-chat is-hidden" id="msgChat">
                <div class="msg-chat-header" id="chatHeader"></div>
                <div class="msg-chat-body" id="chatBody"></div>
                <form class="msg-chat-footer" id="msgForm">
                  @csrf
                  <input type="hidden" name="receiver_id" id="receiver_id">
                  <input type="text" id="msgInput" name="message" placeholder="Nhập tin nhắn...">
                  <button type="submit"><i class="fas fa-paper-plane"></i></button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const chatBody = document.getElementById("chatBody");
      const chatHeader = document.getElementById("chatHeader");
      const msgForm = document.getElementById("msgForm");
      const msgInput = document.getElementById("msgInput");
      const receiverInput = document.getElementById("receiver_id");
      const msgChat = document.getElementById("msgChat");
      const userGetMessageUrl = "{{ url('/user/messenger/get-message') }}";
      const currentUserId = "{{ auth()->id() }}";
      let activeChatId = null;
      const users = document.querySelectorAll(".msg-user");

      const normalizeId = (value) => {
        if (value === undefined || value === null) return null;
        return value.toString();
      };

      const setActiveChat = (id) => {
        activeChatId = normalizeId(id);
      };

      const getAvatar = (id) => {
        return document.querySelector(`.msg-user[data-id="${id}"] img`);
      };

      const highlightUser = (id) => {
        const avatar = getAvatar(id);
        if (avatar) {
          avatar.classList.add("msg-notification");
        }
      };

      const clearNotification = (element) => {
        const avatar = element.querySelector("img");
        if (avatar) {
          avatar.classList.remove("msg-notification");
        }
      };

      const addMsg = (text, isMe = false, time = null) => {
        const div = document.createElement("div");
        div.classList.add("msg-item", isMe ? "msg-right" : "msg-left");

        div.innerHTML = `
            ${text}
            <span class="msg-time">${new Date(time || Date.now()).toLocaleString()}</span>
        `;

        chatBody.appendChild(div);
      };

      const renderMessage = (msg) => {
        const senderId = normalizeId(msg.sender_id);
        const receiverId = normalizeId(msg.receiver_id);
        const isMe = senderId === currentUserId;
        const otherId = isMe ? receiverId : senderId;

        if (!otherId) return;

        if (activeChatId && activeChatId === otherId) {
          addMsg(msg.message, isMe, msg.created_at);
          return;
        }

        if (!isMe) {
          highlightUser(otherId);
        }
      };

      window.renderMessage = renderMessage;

      const pendingMessages = window.__chatPendingMessages || [];
      if (pendingMessages.length) {
        pendingMessages.forEach(renderMessage);
        window.__chatPendingMessages = [];
      }

      users.forEach(user => {
        user.addEventListener("click", async () => {
          users.forEach(u => u.classList.remove("active"));
          clearNotification(user);
          user.classList.add("active");

          if (msgChat) {
            msgChat.classList.remove("is-hidden");
          }

          const id = user.dataset.id;
          const name = user.dataset.name;

          setActiveChat(id);
          receiverInput.value = id;
          chatHeader.textContent = "Chat với " + name;

          try {
            const res = await fetch(`${userGetMessageUrl}/${id}`, {
              headers: {
                "Accept": "application/json"
              }
            });

            const data = await res.json();

            chatBody.innerHTML = "";

            data.messages.forEach(m => {
              addMsg(
                m.message,
                m.sender_id == {{ auth()->id() }},
                m.created_at
              );
            });

            chatBody.scrollTop = chatBody.scrollHeight;

          } catch (error) {
            console.error("Load message error:", error);
          }
        });
      });

      const sendButton = msgForm.querySelector('button[type="submit"]');

      msgForm.addEventListener("submit", async e => {
        e.preventDefault();

        const text = msgInput.value.trim();
        if (!text) return;

        sendButton.disabled = true;

        try {
          const formData = new FormData(msgForm);
          const res = await fetch("{{ route('user.send-message') }}", {
            method: "POST",
            body: formData
          });

          const data = await res.json();

          if (data.status === "success") {
            addMsg(text, true);
            msgInput.value = "";
            chatBody.scrollTop = chatBody.scrollHeight;
          }
        } catch (error) {
          console.error("Send message error:", error);
        } finally {
          sendButton.disabled = false;
        }
      });
    });
  </script>
@endpush
