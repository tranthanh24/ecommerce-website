@extends('admin.layouts.master')

@section('title', 'Tin nhắn')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Tin nhắn</h1>
    </div>

    <div class="section-body">
      <div class="msg-wrapper">
        <div class="msg-sidebar">
          <div class="msg-sidebar-header">Danh sách</div>

          <div class="msg-user-list">
            @foreach ($chats as $chat)
              @php
                $unseen = \App\Models\Chat::where([
                    'sender_id' => $chat->id,
                    'receiver_id' => auth()->user()->id,
                    'seen' => false,
                ])->exists();
              @endphp

              <div class="msg-user" data-id="{{ $chat->id }}" data-name="{{ $chat->name }}">
                <img class="{{ $unseen ? 'msg-notification' : '' }}"
                  src="{{ asset($chat->image ?: 'frontend/images/avatar.jpg') }}" alt="{{ $chat->name }}">
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
  </section>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const chatBody = document.getElementById('chatBody');
      const chatHeader = document.getElementById('chatHeader');
      const msgForm = document.getElementById('msgForm');
      const msgInput = document.getElementById('msgInput');
      const receiverInput = document.getElementById('receiver_id');
      const msgChat = document.getElementById('msgChat');
      const adminGetMessageUrl = "{{ url('/admin/messenger/get-message') }}";
      const sendMessageUrl = "{{ route('admin.send-message') }}";
      const currentUserId = "{{ auth()->id() }}";

      if (!chatBody || !chatHeader || !msgForm || !msgInput || !receiverInput || !msgChat) {
        return;
      }

      const normalizeId = (value) => {
        if (value === undefined || value === null) return null;
        return value.toString();
      };

      let activeChatId = null;
      const normalizedCurrentUserId = normalizeId(currentUserId);
      const users = document.querySelectorAll('.msg-user');

      const setActiveChat = (id) => {
        activeChatId = normalizeId(id);
      };

      const getAvatar = (id) => {
        return document.querySelector(`.msg-user[data-id="${id}"] img`);
      };

      const highlightUser = (id) => {
        const avatar = getAvatar(id);
        if (avatar) {
          avatar.classList.add('msg-notification');
        }
      };

      const clearNotification = (element) => {
        const avatar = element.querySelector('img');
        if (avatar) {
          avatar.classList.remove('msg-notification');
        }
      };

      const addMsg = (text, isMe = false, time = null) => {
        const div = document.createElement('div');
        div.classList.add('msg-item', isMe ? 'msg-right' : 'msg-left');

        div.innerHTML = `
            ${text}
            <span class="msg-time">${new Date(time || Date.now()).toLocaleString()}</span>
        `;

        chatBody.appendChild(div);
        chatBody.scrollTop = chatBody.scrollHeight;
      };

      const renderMessage = (msg) => {
        const senderId = normalizeId(msg.sender_id);
        const receiverId = normalizeId(msg.receiver_id);
        const isMe = senderId === normalizedCurrentUserId;
        const otherId = isMe ? receiverId : senderId;

        if (!otherId) {
          return;
        }

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
        user.addEventListener('click', async () => {
          users.forEach(u => u.classList.remove('active'));
          clearNotification(user);
          user.classList.add('active');

          msgChat.classList.remove('is-hidden');

          const id = user.dataset.id;
          const name = user.dataset.name;

          setActiveChat(id);
          receiverInput.value = id;
          chatHeader.textContent = 'Chat với ' + name;

          try {
            const res = await fetch(`${adminGetMessageUrl}/${id}`, {
              headers: {
                'Accept': 'application/json'
              }
            });

            if (!res.ok) {
              throw new Error('Không thể tải tin nhắn');
            }

            const data = await res.json();
            const messages = data.messages || [];

            chatBody.innerHTML = '';

            messages.forEach(m => {
              addMsg(
                m.message,
                m.sender_id == normalizedCurrentUserId,
                m.created_at
              );
            });
          } catch (error) {
            console.error('Load message error:', error);
          }
        });
      });

      const sendButton = msgForm.querySelector('button[type="submit"]');
      if (!sendButton) {
        return;
      }

      msgForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const text = msgInput.value.trim();
        if (!text) {
          return;
        }

        sendButton.disabled = true;

        try {
          const formData = new FormData(msgForm);
          const res = await fetch(sendMessageUrl, {
            method: 'POST',
            headers: {
              'Accept': 'application/json'
            },
            body: formData
          });

          const data = await res.json();

          if (data.status === 'success') {
            addMsg(text, true);
            msgInput.value = '';
          }
        } catch (error) {
          console.error('Send message error:', error);
        } finally {
          sendButton.disabled = false;
        }
      });
    });
  </script>
@endpush
