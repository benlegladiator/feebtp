/**
 * FREE BTP SARL - JavaScript Principal (Frontend)
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Menu Mobile
  const mobileBtn = document.querySelector('.mobile-menu-btn');
  const navLinks = document.querySelector('.nav-links');

  if (mobileBtn && navLinks) {
    mobileBtn.addEventListener('click', () => {
      navLinks.classList.toggle('active');
    });
  }

  // 2. Filtres Projets BTP
  const filterBtns = document.querySelectorAll('.filter-btn');
  const projectCards = document.querySelectorAll('.project-card');

  if (filterBtns.length > 0 && projectCards.length > 0) {
    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const filterValue = btn.getAttribute('data-filter');

        projectCards.forEach(card => {
          const cardCategory = card.getAttribute('data-category');
          if (filterValue === 'all' || cardCategory === filterValue) {
            card.style.display = 'block';
            card.style.animation = 'fadeIn 0.4s ease';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  }

  // 3. Widget Boîte de Dialogue Interactive (Live Chat & WhatsApp)
  const chatToggleBtn = document.getElementById('chatToggleBtn');
  const chatBox = document.getElementById('chatBox');
  const chatCloseBtn = document.getElementById('chatCloseBtn');
  const chatForm = document.getElementById('chatForm');
  const chatInput = document.getElementById('chatInput');
  const chatMessages = document.getElementById('chatMessages');

  if (chatToggleBtn && chatBox) {
    chatToggleBtn.addEventListener('click', () => {
      chatBox.classList.toggle('active');
      if (chatBox.classList.contains('active') && chatInput) {
        chatInput.focus();
      }
    });

    if (chatCloseBtn) {
      chatCloseBtn.addEventListener('click', () => {
        chatBox.classList.remove('active');
      });
    }

    if (chatForm && chatInput && chatMessages) {
      chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const text = chatInput.value.trim();
        if (!text) return;

        // Ajouter message utilisateur à l'écran
        appendMessage(text, 'user');
        chatInput.value = '';

        // Afficher l'indicateur de frappe
        showTypingIndicator();

        // Envoyer à l'API backend
        try {
          const response = await fetch('api/chat.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: text })
          });
          const data = await response.json();

          // Retirer l'indicateur de frappe
          removeTypingIndicator();

          // Réponse du bot ou de l'équipe FREE BTP
          const reply = data.reply || "Merci pour votre message ! Un ingénieur de FREE BTP SARL va l'examiner. Vous pouvez aussi nous joindre au (+237) 691 00 64 31.";
          const suggestions = data.suggestions || [];
          appendMessage(reply, 'bot', suggestions);
        } catch (error) {
          removeTypingIndicator();
          appendMessage("Message transmis à notre équipe ! Pour une réponse immédiate, écrivez-nous sur WhatsApp au (+237) 691 00 64 31.", 'bot', ["💬 WhatsApp (+237 691 00 64 31)", "📊 Demander un devis"]);
        }
      });
    }
  }

  function showTypingIndicator() {
    if (!chatMessages) return;
    removeTypingIndicator();
    const typingDiv = document.createElement('div');
    typingDiv.id = 'chatTypingIndicator';
    typingDiv.className = 'chat-typing';
    typingDiv.innerHTML = '<span></span><span></span><span></span>';
    chatMessages.appendChild(typingDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }

  function removeTypingIndicator() {
    const existing = document.getElementById('chatTypingIndicator');
    if (existing) existing.remove();
  }

  function appendMessage(text, type, suggestions = []) {
    if (!chatMessages) return;
    const msgDiv = document.createElement('div');
    msgDiv.className = `chat-msg ${type}`;
    
    const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    
    // Si bot : autoriser HTML sécurisé pour mise en valeur (bold, br, bullet points)
    const content = (type === 'bot') ? text : escapeHtml(text);
    msgDiv.innerHTML = `${content} <span class="chat-time">${time}</span>`;

    // Ajouter les boutons de suggestions rapides
    if (type === 'bot' && suggestions && suggestions.length > 0) {
      const suggBox = document.createElement('div');
      suggBox.className = 'chat-suggestions';
      
      suggestions.forEach(s => {
        const chip = document.createElement('button');
        chip.type = 'button';
        chip.className = 'chat-chip';
        chip.innerText = s;
        chip.addEventListener('click', () => {
          handleSuggestionClick(s);
        });
        suggBox.appendChild(chip);
      });
      msgDiv.appendChild(suggBox);
    }

    chatMessages.appendChild(msgDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }

  function handleSuggestionClick(suggestionText) {
    if (suggestionText.includes('WhatsApp')) {
      window.open('https://wa.me/237691006431?text=Bonjour%20FREE%20BTP,%20je%20vous%20contacte%20depuis%20votre%20site%20internet.', '_blank');
      return;
    }
    if (suggestionText.includes('formulaire de devis') || suggestionText.includes('Demander un devis')) {
      window.location.href = 'contact.php?type=devis';
      return;
    }
    if (suggestionText.includes('Google Maps') || suggestionText.includes('Adresse du siège')) {
      window.location.href = 'contact.php#siege-map';
      return;
    }
    
    // Nettoyer emojis éventuels au début pour envoyer comme question
    const cleanText = suggestionText.replace(/^[\p{Emoji}\s]+/u, '').trim();
    if (chatInput) {
      chatInput.value = cleanText;
      if (chatForm) {
        chatForm.dispatchEvent(new Event('submit'));
      }
    }
  }

  function escapeHtml(string) {
    const entityMap = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;'
    };
    return String(string).replace(/[&<>"']/g, s => entityMap[s]);
  }

  // 4. Switcher Interactif Vidéos de Chantiers
  const playlistItems = document.querySelectorAll('.video-playlist-item');
  if (playlistItems.length > 0) {
    playlistItems.forEach(item => {
      item.addEventListener('click', () => {
        playlistItems.forEach(el => el.classList.remove('active'));
        item.classList.add('active');

        const embedHtml = item.getAttribute('data-embed');
        const title = item.getAttribute('data-title');
        const desc = item.getAttribute('data-desc');
        const category = item.getAttribute('data-category');
        const location = item.getAttribute('data-location');
        const link = item.getAttribute('data-link');

        const screen = document.getElementById('mainVideoScreen');
        if (screen && embedHtml) {
          screen.innerHTML = embedHtml;
        }

        const titleEl = document.getElementById('mainVideoTitle');
        if (titleEl && title) titleEl.textContent = title;

        const descEl = document.getElementById('mainVideoDesc');
        if (descEl && desc) descEl.textContent = desc;

        const catEl = document.getElementById('mainVideoCategory');
        if (catEl && category) catEl.textContent = category;

        const locEl = document.getElementById('mainVideoLocation');
        if (locEl && location) {
          locEl.innerHTML = '<i class="fas fa-map-marker-alt" style="color: var(--accent);"></i> ' + location;
        }

        const linkEl = document.getElementById('mainVideoLink');
        if (linkEl && link) linkEl.setAttribute('href', link);
      });
    });
  }
});
