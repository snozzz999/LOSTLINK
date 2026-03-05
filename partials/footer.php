<footer>
  <div class="container">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
      <div>
        © <?php echo date('Y'); ?> <strong>VU LostLink</strong> — All rights reserved
      </div>
    </div>
  </div>
</footer>

<?php if (!empty($_SESSION["user_id"]) && strtolower(trim($_SESSION["role"] ?? "")) === "user"): ?>

  <!-- Chatbot Widget -->
  <button class="chat-fab" id="botFab" aria-label="Open help chat">?</button>

  <div class="chat-panel" id="botPanel" style="display:none;">
    <div class="chat-header">
      <div>VU LostLink Help Bot</div>
      <button class="chat-close" id="botClose" type="button">Close</button>
    </div>

    <div class="chat-body" id="botBody"></div>

    <div class="chat-input" style="flex-direction:column;">
      <div class="text-muted small mb-2">Quick questions:</div>

      <div style="display:flex; flex-wrap:wrap; gap:8px;">
        <button class="btn btn-sm btn-outline-primary" data-q="how_upload" type="button">Upload item</button>
        <button class="btn btn-sm btn-outline-primary" data-q="how_verification" type="button">Verification</button>
        <button class="btn btn-sm btn-outline-primary" data-q="why_otp" type="button">OTP login</button>
        <button class="btn btn-sm btn-outline-primary" data-q="approved_lost" type="button">Approved (Lost)</button>
        <button class="btn btn-sm btn-outline-primary" data-q="approved_found" type="button">Approved (Found)</button>
        <button class="btn btn-sm btn-outline-primary" data-q="rejected" type="button">Rejected</button>
        <button class="btn btn-sm btn-outline-primary" data-q="browse_privacy" type="button">Browse privacy</button>
        <button class="btn btn-sm btn-outline-danger" data-q="other" type="button">Other</button>
      </div>

      <div class="mt-2 text-muted small">
        If your issue isn’t listed, choose <b>Other</b> — the bot will direct you to email support.
      </div>
    </div>
  </div>

  <script>
  (function(){
    const fab = document.getElementById("botFab");
    const panel = document.getElementById("botPanel");
    const closeBtn = document.getElementById("botClose");
    const body = document.getElementById("botBody");

    function addMsg(text, who){
      const div = document.createElement("div");
      div.className = "chat-msg " + (who === "user" ? "user" : "bot");
      div.textContent = text;
      body.appendChild(div);
      body.scrollTop = body.scrollHeight;
    }

    function openBot(){
      panel.style.display = "block";
      if (body.childElementCount === 0){
        addMsg("Hi! I’m the VU LostLink Help Bot 👋 Tap a question below.", "bot");
      }
    }
    function closeBot(){ panel.style.display = "none"; }

    fab.addEventListener("click", openBot);
    closeBtn.addEventListener("click", closeBot);

    panel.addEventListener("click", async (e) => {
      const btn = e.target.closest("button[data-q]");
      if (!btn) return;

      const key = btn.getAttribute("data-q");
      const label = btn.textContent.trim();

      addMsg(label, "user");

      try {
        const fd = new FormData();
        fd.append("q", key);

        const res = await fetch("bot_api.php", {
          method: "POST",
          body: fd,
          headers: { "Accept": "application/json" }
        });

        const data = await res.json();
        addMsg(data.reply || "Sorry, something went wrong.", "bot");
      } catch (err){
        addMsg("Sorry, the bot is unavailable right now.", "bot");
      }
    });
  })();
  </script>

<?php endif; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>