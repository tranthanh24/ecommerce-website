@php($chatbot = app(\App\Services\Chatbot\ChatbotSettings::class)->public())
@php($chatbotVisible = ($chatbot['enabled'] ?? false) || app()->environment('local'))

@if ($chatbotVisible)
  <div id="chatbot-widget" data-endpoint="{{ route('chatbot.reply') }}"
    data-enabled="{{ $chatbot['enabled'] ?? false ? '1' : '0' }}" data-provider="{{ $chatbot['provider'] ?? 'gemini' }}">
    <button id="chatbot-toggle" type="button" aria-label="Mở chatbot" aria-controls="chatbot-panel" aria-expanded="false">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path
          d="M12 3C6.477 3 2 6.806 2 11.5c0 2.185.99 4.177 2.64 5.705L4 21l3.93-1.31c1.22.35 2.6.56 4.07.56 5.523 0 10-3.806 10-8.5S17.523 3 12 3Z"
          stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
        <path d="M8 11.5h.01M12 11.5h.01M16 11.5h.01" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" />
      </svg>
    </button>

    <div id="chatbot-panel" class="d-none" role="dialog" aria-label="Chat hỗ trợ" aria-modal="false">
      <div class="d-flex align-items-center justify-content-between px-3 py-2" style="background:#111827;color:#fff;">
        <div>
          <div style="font-weight:600;line-height:1.2">Hỗ trợ mua sắm</div>
        </div>
        <div class="d-flex gap-2">
          <button id="chatbot-reset" type="button" class="btn btn-sm btn-outline-light" aria-label="Xóa hội thoại">
            Xóa
          </button>
          <button id="chatbot-close" type="button" class="btn btn-sm btn-light" aria-label="Đóng chatbot">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>

      <div id="chatbot-messages" aria-live="polite"></div>

      <form id="chatbot-form" class="p-3" style="border-top:1px solid rgba(0,0,0,.08);background:#fff;">
        <div class="input-group">
          <input id="chatbot-input" type="text" class="form-control" placeholder="Nhập câu hỏi..." autocomplete="off"
            maxlength="2000" />
          <button id="chatbot-send" class="btn btn-dark" type="submit">Gửi</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    (function() {
      const widget = document.getElementById('chatbot-widget');
      if (!widget) return;
      if (widget.dataset.bound === '1') return;
      widget.dataset.bound = '1';

      const endpoint = widget.dataset.endpoint;
      const enabled = widget.dataset.enabled === '1';
      const provider = widget.dataset.provider || '';
      const toggleBtn = document.getElementById('chatbot-toggle');
      const panel = document.getElementById('chatbot-panel');
      const closeBtn = document.getElementById('chatbot-close');
      const resetBtn = document.getElementById('chatbot-reset');
      const messagesEl = document.getElementById('chatbot-messages');
      const form = document.getElementById('chatbot-form');
      const input = document.getElementById('chatbot-input');

      if (!endpoint || !toggleBtn || !panel || !closeBtn || !resetBtn || !messagesEl || !form || !input) return;

      const csrfMeta = document.querySelector('meta[name="csrf-token"]');
      const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
      const storageKey = 'chatbot_history_v1';
      const conversationKey = 'chatbot_conversation_id_v1';
      let history = [];
      let conversationId = '';

      const scrollToBottom = () => {
        messagesEl.scrollTop = messagesEl.scrollHeight;
      };

      const escapeHtml = (str) => {
        return String(str).replace(/[&<>"']/g, (char) => {
          switch (char) {
            case '&':
              return '&amp;';
            case '<':
              return '&lt;';
            case '>':
              return '&gt;';
            case '"':
              return '&quot;';
            case "'":
              return '&#39;';
            default:
              return char;
          }
        });
      };

      const linkify = (text) => {
        const escaped = escapeHtml(text);
        const urlRegex = /(https?:\/\/[^\s<]+)/g;

        return escaped.replace(urlRegex, (rawUrl) => {
          let url = rawUrl;
          let suffix = '';

          while (url.length && /[)\].,;:!?]$/.test(url)) {
            suffix = url.slice(-1) + suffix;
            url = url.slice(0, -1);
          }

          if (!url) return rawUrl;

          return `<a href="${url}" target="_blank" rel="noopener noreferrer">${url}</a>${suffix}`;
        });
      };

      const setBubbleContent = (bubble, content) => {
        bubble.innerHTML = linkify(content);
      };

      const addMessage = (role, content) => {
        const wrap = document.createElement('div');
        wrap.className = 'chatbot-msg ' + role;

        const bubble = document.createElement('div');
        bubble.className = 'chatbot-bubble';
        setBubbleContent(bubble, content);

        wrap.appendChild(bubble);
        messagesEl.appendChild(wrap);
        scrollToBottom();
        return bubble;
      };

      const loadHistory = () => {
        try {
          const raw = localStorage.getItem(storageKey);
          if (!raw) return;
          const parsed = JSON.parse(raw);
          if (Array.isArray(parsed)) history = parsed;
        } catch (_) {
          history = [];
        }
      };

      const loadConversationId = () => {
        try {
          const raw = localStorage.getItem(conversationKey);
          if (typeof raw === 'string' && raw.length > 0) conversationId = raw;
        } catch (_) {}
      };

      const saveHistory = () => {
        try {
          localStorage.setItem(storageKey, JSON.stringify(history.slice(-30)));
        } catch (_) {}
      };

      const saveConversationId = () => {
        try {
          if (conversationId) localStorage.setItem(conversationKey, conversationId);
        } catch (_) {}
      };

      const render = () => {
        messagesEl.innerHTML = '';

        if (history.length === 0) {
          addMessage(
            'assistant',
            enabled ?
            'Chào bạn! Bạn muốn tìm sản phẩm gì hoặc cần hỗ trợ đơn hàng/phí vận chuyển?' :
            'Chào bạn! Mình đang ở chế độ gợi ý (chưa bật AI). Bạn hỏi mình vẫn sẽ tìm trong dữ liệu hệ thống nhé.'
          );
          return;
        }

        history.forEach((message) => {
          if (!message || !message.role || !message.content) return;
          addMessage(message.role, message.content);
        });
      };

      const setOpen = (open) => {
        panel.classList.toggle('d-none', !open);
        toggleBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open) {
          input.focus();
          scrollToBottom();
        }
      };

      loadHistory();
      loadConversationId();
      render();

      toggleBtn.addEventListener('click', () => setOpen(panel.classList.contains('d-none')));
      closeBtn.addEventListener('click', () => setOpen(false));
      resetBtn.addEventListener('click', () => {
        history = [];
        conversationId = '';
        try {
          localStorage.removeItem(storageKey);
          localStorage.removeItem(conversationKey);
        } catch (_) {}
        render();
      });

      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !panel.classList.contains('d-none')) setOpen(false);
      });

      form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const text = (input.value || '').trim();
        if (!text) return;

        input.value = '';

        history.push({
          role: 'user',
          content: text
        });
        addMessage('user', text);
        saveHistory();

        const pendingBubble = addMessage('assistant', 'Đang trả lời...');

        try {
          const res = await fetch(endpoint, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json',
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.assign({
              message: text,
              history: history.slice(-12),
            }, conversationId ? {
              conversation_id: conversationId
            } : {})),
          });

          if (!res.ok) {
            setBubbleContent(pendingBubble, res.status === 419 ?
              'Phiên làm việc hết hạn (CSRF). Bạn refresh trang rồi thử lại nhé.' :
              `Không gửi được (${res.status}).`);
            return;
          }

          const data = await res.json();
          if (data && data.conversation_id) {
            conversationId = data.conversation_id;
            saveConversationId();
          }

          const reply = (data && data.reply) ? data.reply :
            'Mình chưa có câu trả lời phù hợp. Bạn nói rõ hơn giúp mình nhé.';
          setBubbleContent(pendingBubble, reply);

          history.push({
            role: 'assistant',
            content: reply
          });
          saveHistory();
        } catch (_) {
          setBubbleContent(pendingBubble, provider === 'gemini' ?
            'Lỗi gọi Gemini. Bạn thử lại giúp mình nhé.' :
            'Có lỗi mạng khi gửi tin nhắn. Bạn thử lại giúp mình nhé.');
        }
      });
    })();
  </script>
@endif
