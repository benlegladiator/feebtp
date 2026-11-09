/**
 * FREE BTP SARL - JavaScript Panneau d'Administration
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Confirmation suppression
  const deleteButtons = document.querySelectorAll('.confirm-delete');
  deleteButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
      if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.')) {
        e.preventDefault();
      }
    });
  });

  // 2. Prévisualisation d'image avant upload
  const imageInputs = document.querySelectorAll('.image-preview-input');
  imageInputs.forEach(input => {
    input.addEventListener('change', function () {
      const previewId = this.getAttribute('data-preview-target');
      const previewEl = document.getElementById(previewId);
      if (previewEl && this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
          previewEl.src = e.target.result;
          previewEl.style.display = 'block';
        };
        reader.readAsDataURL(this.files[0]);
      }
    });
  });

  // 3. Modales d'administration
  window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.style.display = 'flex';
    }
  };

  window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.style.display = 'none';
    }
  };
});
