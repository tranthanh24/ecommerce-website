<div class="tab-pane fade chatbot-settings" id="list-chatbot" role="tabpanel" aria-labelledby="list-chatbot-list">
  <div class="card border">
    <div class="card-body">
      <form action="{{ route('admin.chatbot-settings.update') }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Trạng thái</label>
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="chatbot_enabled" name="enabled" value="1"
                  {{ @$chatbotSetting?->enabled ? 'checked' : '' }}>
                <label class="custom-control-label" for="chatbot_enabled">Bật chatbot</label>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label>Log vào DB</label>
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="chatbot_log_to_db" name="log_to_db"
                  value="1" {{ @$chatbotSetting?->log_to_db ? 'checked' : '' }}>
                <label class="custom-control-label" for="chatbot_log_to_db">Lưu hội thoại</label>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label>Provider</label>
              <select name="provider" class="form-control">
                <option value="gemini" {{ (@$chatbotSetting?->provider ?? 'gemini') === 'gemini' ? 'selected' : '' }}>
                  Gemini
                </option>
                <option value="openai" {{ (@$chatbotSetting?->provider ?? '') === 'openai' ? 'selected' : '' }}>
                  OpenAI
                </option>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Max history (messages)</label>
              <input type="number" class="form-control" name="max_history_messages" min="1" max="50"
                value="{{ old('max_history_messages', @$chatbotSetting?->max_history_messages ?? 12) }}">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Max products (>= 10)</label>
              <input type="number" class="form-control" name="max_product_results" min="10" max="50"
                value="{{ old('max_product_results', @$chatbotSetting?->max_product_results ?? 10) }}">
            </div>
          </div>
        </div>

        <hr>
        <h6>Gemini</h6>

        <div class="form-group">
          <label>Gemini API Key</label>
          <input type="password" class="form-control" name="gemini_api_key" placeholder="Nhập key mới (tùy chọn)">
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Base URL</label>
              <input type="text" class="form-control" name="gemini_base_url"
                value="{{ old('gemini_base_url', @$chatbotSetting?->gemini_base_url ?? 'https://generativelanguage.googleapis.com/v1beta') }}">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Model</label>
              <input type="text" class="form-control" name="gemini_model"
                value="{{ old('gemini_model', @$chatbotSetting?->gemini_model ?? 'gemini-1.5-flash') }}">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label>Temperature</label>
              <input type="number" step="0.1" min="0" max="2" class="form-control"
                name="gemini_temperature"
                value="{{ old('gemini_temperature', @$chatbotSetting?->gemini_temperature ?? 0.3) }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Timeout (s)</label>
              <input type="number" min="5" max="120" class="form-control" name="gemini_timeout"
                value="{{ old('gemini_timeout', @$chatbotSetting?->gemini_timeout ?? 30) }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Max output tokens</label>
              <input type="number" min="50" max="4000" class="form-control" name="gemini_max_output_tokens"
                value="{{ old('gemini_max_output_tokens', @$chatbotSetting?->gemini_max_output_tokens ?? 500) }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Top K</label>
              <input type="number" min="0" max="100" class="form-control" name="gemini_top_k"
                value="{{ old('gemini_top_k', @$chatbotSetting?->gemini_top_k ?? 40) }}">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label>Top P</label>
              <input type="number" step="0.01" min="0" max="1" class="form-control"
                name="gemini_top_p" value="{{ old('gemini_top_p', @$chatbotSetting?->gemini_top_p ?? 0.95) }}">
            </div>
          </div>
        </div>

        <hr>
        <h6>OpenAI</h6>

        <div class="form-group">
          <label>OpenAI API Key</label>
          <input type="password" class="form-control" name="openai_api_key" placeholder="Nhập key mới (tùy chọn)">
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Base URL</label>
              <input type="text" class="form-control" name="openai_base_url"
                value="{{ old('openai_base_url', @$chatbotSetting?->openai_base_url ?? 'https://api.openai.com/v1') }}">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Model</label>
              <input type="text" class="form-control" name="openai_model"
                value="{{ old('openai_model', @$chatbotSetting?->openai_model ?? 'gpt-4o-mini') }}">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Temperature</label>
              <input type="number" step="0.1" min="0" max="2" class="form-control"
                name="openai_temperature"
                value="{{ old('openai_temperature', @$chatbotSetting?->openai_temperature ?? 0.3) }}">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Timeout (s)</label>
              <input type="number" min="5" max="120" class="form-control" name="openai_timeout"
                value="{{ old('openai_timeout', @$chatbotSetting?->openai_timeout ?? 30) }}">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Max tokens</label>
              <input type="number" min="50" max="4000" class="form-control" name="openai_max_tokens"
                value="{{ old('openai_max_tokens', @$chatbotSetting?->openai_max_tokens ?? 500) }}">
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
      </form>
    </div>
  </div>
</div>
