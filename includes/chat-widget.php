<?php
// Widget Boîte de Dialogue & WhatsApp Direct
?>
<div class="chat-widget-wrapper">
  <!-- Boîte de Dialogue Interactive (Popup) -->
  <div class="chat-box" id="chatBox">
    <div class="chat-box-header">
      <div class="chat-header-info">
        <div class="chat-avatar">
          <i class="fas fa-headset"></i>
        </div>
        <div class="chat-header-text">
          <h5>Conseiller FREE BTP</h5>
          <span class="chat-status">En ligne • Dschang</span>
        </div>
      </div>
      <button class="chat-close-btn" id="chatCloseBtn" aria-label="Fermer la boîte de dialogue">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <!-- Historique des échanges -->
    <div class="chat-messages" id="chatMessages">
      <div class="chat-msg bot">
        Bonjour et bienvenue chez <strong>FREE BTP SARL</strong> ! 🏗️<br>
        Je suis à votre disposition pour vous orienter sur nos chantiers, nos devis ou nos réalisations à Dschang et partout au Cameroun.<br>
        Comment puis-je vous aider aujourd'hui ?
        <div class="chat-suggestions">
          <button type="button" class="chat-chip" onclick="document.getElementById('chatInput').value='J\'aimerais avoir un devis'; document.getElementById('chatForm').dispatchEvent(new Event('submit'));">📊 Demander un devis</button>
          <button type="button" class="chat-chip" onclick="document.getElementById('chatInput').value='Pouvez-vous construire un pont ?'; document.getElementById('chatForm').dispatchEvent(new Event('submit'));">🌉 Ouvrages d'art & Ponts</button>
          <button type="button" class="chat-chip" onclick="document.getElementById('chatInput').value='Où êtes-vous situés ?'; document.getElementById('chatForm').dispatchEvent(new Event('submit'));">📍 Siège & Localisation</button>
          <button type="button" class="chat-chip" onclick="window.open('https://wa.me/237691006431?text=Bonjour%20FREE%20BTP,%20je%20souhaite%20des%20informations.', '_blank');">💬 WhatsApp (+237 691 00 64 31)</button>
        </div>
        <span class="chat-time"><?php echo date('H:i'); ?></span>
      </div>
    </div>

    <!-- Formulaire d'envoi -->
    <form class="chat-input-area" id="chatForm">
      <input type="text" class="chat-input" id="chatInput" placeholder="Posez votre question ici..." required autocomplete="off">
      <button type="submit" class="chat-send-btn" aria-label="Envoyer">
        <i class="fas fa-paper-plane"></i>
      </button>
    </form>
    
    <!-- Raccourci vers WhatsApp direct -->
    <div style="background: #f8fafc; padding: 8px 14px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 0.78rem;">
      <a href="https://wa.me/<?php echo htmlspecialchars($siteSettings['whatsapp_number'] ?? '237691006431'); ?>?text=Bonjour,%20je%20souhaite%20échanger%20directement%20avec%20un%20ingénieur%20FREE%20BTP." target="_blank" style="color: #16a34a; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
        <i class="fab fa-whatsapp" style="font-size: 1rem;"></i> Basculer sur WhatsApp officiel
      </a>
    </div>
  </div>

  <!-- Bouton WhatsApp Flottant -->
  <a href="https://wa.me/<?php echo htmlspecialchars($siteSettings['whatsapp_number'] ?? '237691006431'); ?>?text=Bonjour%20FREE%20BTP,%20je%20vous%20contacte%20depuis%20votre%20site%20internet." 
     target="_blank" 
     class="floating-btn btn-whatsapp-float" 
     title="Discuter directement sur WhatsApp" 
     aria-label="WhatsApp">
    <i class="fab fa-whatsapp"></i>
  </a>

  <!-- Bouton d'ouverture de la boîte de dialogue -->
  <button class="floating-btn btn-chat-toggle" id="chatToggleBtn" title="Ouvrir la boîte de dialogue en direct" aria-label="Boîte de dialogue">
    <i class="fas fa-comments"></i>
    <span class="chat-badge">1</span>
  </button>
</div>
