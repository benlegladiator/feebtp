/**
 * FEE BTP SARL - Moteur 3D Interactif & Expérience Visuelle Next-Gen
 * - 1. Wireframe 3D Blueprint Canvas interactif (Hero)
 * - 2. Moteur de Tilt 3D Physique avec réflexion spéculaire (Cartes, Piliers, Projets)
 * - 3. Parallaxe de profondeur gyroscopique / au scroll
 */

(function() {
  'use strict';

  /* ==========================================================================
     1. HERO 3D BLUEPRINT CANVAS (Maillage géométrique architectural rotatif)
     ========================================================================== */
  function initHero3DCanvas() {
    const canvas = document.getElementById('feeHero3DCanvas');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    let width, height;
    let mouseX = 0, mouseY = 0;
    let targetX = 0, targetY = 0;
    let angleX = 0.2, angleY = 0.3;

    function resize() {
      const parent = canvas.parentElement;
      width = canvas.width = parent.clientWidth || window.innerWidth;
      height = canvas.height = parent.clientHeight || window.innerHeight;
    }
    window.addEventListener('resize', resize);
    resize();

    // Suivi de la souris pour inclinaison dynamique
    window.addEventListener('mousemove', (e) => {
      const rect = canvas.getBoundingClientRect();
      if (e.clientY < rect.bottom) {
        targetX = (e.clientX - width / 2) * 0.0005;
        targetY = (e.clientY - height / 2) * 0.0005;
      }
    });

    // Génération d'une structure 3D d'immeuble géométrique (FEE BTP Tower Mesh)
    const nodes = [];
    const edges = [];
    const floors = 7;
    const radius = Math.min(width, height) * 0.22;

    for (let f = 0; f < floors; f++) {
      const y = (f - floors / 2) * (radius * 0.38);
      const floorRadius = radius * (1 - f * 0.04);
      const pointsPerFloor = 6; // Prisme hexagonal BTP

      for (let i = 0; i < pointsPerFloor; i++) {
        const theta = (i / pointsPerFloor) * Math.PI * 2;
        nodes.push({
          x0: Math.cos(theta) * floorRadius,
          y0: y,
          z0: Math.sin(theta) * floorRadius,
          x: 0, y: 0, z: 0,
          floor: f
        });
      }
    }

    // Connecter les arrêtes (lignes horizontales, verticales et diagonales de contreventement)
    const ppf = 6;
    for (let f = 0; f < floors; f++) {
      for (let i = 0; i < ppf; i++) {
        const cur = f * ppf + i;
        const next = f * ppf + ((i + 1) % ppf);
        edges.push([cur, next, 'ring']); // Anneau horizontal

        if (f < floors - 1) {
          const above = (f + 1) * ppf + i;
          edges.push([cur, above, 'column']); // Poteau vertical
          if (i % 2 === 0) {
            const aboveDiag = (f + 1) * ppf + ((i + 1) % ppf);
            edges.push([cur, aboveDiag, 'brace']); // Diagonale acier
          }
        }
      }
    }

    // Particules de poussière de chantier / laser
    const particles = [];
    for (let p = 0; p < 35; p++) {
      particles.push({
        x: (Math.random() - 0.5) * radius * 3.5,
        y: (Math.random() - 0.5) * radius * 3.5,
        z: (Math.random() - 0.5) * radius * 3.5,
        size: Math.random() * 2 + 1,
        speed: Math.random() * 0.005 + 0.002
      });
    }

    function render() {
      ctx.clearRect(0, 0, width, height);

      // Interpolation douce
      angleX += (targetX - angleX) * 0.05 + 0.0018;
      angleY += (targetY - angleY) * 0.05;

      const cosX = Math.cos(angleX);
      const sinX = Math.sin(angleX);
      const cosY = Math.cos(angleY);
      const sinY = Math.sin(angleY);

      const cx = width * 0.72; // Placé sur le flanc droit pour laisser respirer le texte
      const cy = height * 0.52;
      const fov = 650;

      // Projection des nœuds
      const projected = [];
      for (let i = 0; i < nodes.length; i++) {
        const n = nodes[i];
        // Rotation Y puis X
        let x1 = n.x0 * cosX - n.z0 * sinX;
        let z1 = n.z0 * cosX + n.x0 * sinX;
        let y1 = n.y0 * cosY - z1 * sinY;
        let z2 = z1 * cosY + n.y0 * sinY + 600;

        const scale = fov / z2;
        projected.push({
          px: cx + x1 * scale,
          py: cy + y1 * scale,
          scale: scale,
          z: z2
        });
      }

      // Tracé des arrêtes architecturales
      for (let e = 0; e < edges.length; e++) {
        const p1 = projected[edges[e][0]];
        const p2 = projected[edges[e][1]];
        const type = edges[e][2];

        ctx.beginPath();
        ctx.moveTo(p1.px, p1.py);
        ctx.lineTo(p2.px, p2.py);

        if (type === 'brace') {
          ctx.strokeStyle = 'rgba(249, 115, 22, 0.45)'; // Poutre orange FEE BTP
          ctx.lineWidth = 1;
        } else if (type === 'column') {
          ctx.strokeStyle = 'rgba(255, 180, 0, 0.55)'; // Colonne porteuse or
          ctx.lineWidth = 1.4;
        } else {
          ctx.strokeStyle = 'rgba(255, 255, 255, 0.35)'; // Dalle structurale blanche
          ctx.lineWidth = 1;
        }
        ctx.stroke();
      }

      // Nœuds lumineux (Joints de soudure / coulages de béton armé)
      for (let i = 0; i < projected.length; i++) {
        const p = projected[i];
        ctx.beginPath();
        ctx.arc(p.px, p.py, Math.max(1.5, p.scale * 2.8), 0, Math.PI * 2);
        ctx.fillStyle = '#ffffff';
        ctx.shadowColor = '#f97316';
        ctx.shadowBlur = 10;
        ctx.fill();
        ctx.shadowBlur = 0;
      }

      // Particules flottantes ambiantes
      for (let i = 0; i < particles.length; i++) {
        const pt = particles[i];
        pt.y -= pt.speed * 80;
        if (pt.y < -radius * 2) pt.y = radius * 2;

        let x1 = pt.x * cosX - pt.z * sinX;
        let z1 = pt.z * cosX + pt.x * sinX;
        let z2 = z1 * cosY + pt.y * sinY + 600;
        const scale = fov / z2;

        ctx.beginPath();
        ctx.arc(cx + x1 * scale, cy + pt.y * scale, pt.size * scale, 0, Math.PI * 2);
        ctx.fillStyle = 'rgba(249, 115, 22, 0.6)';
        ctx.fill();
      }

      requestAnimationFrame(render);
    }

    render();
  }

  /* ==========================================================================
     2. MOTEUR TILT 3D PHYSIQUE AVEC GLOW & PERSPECTIVE
     ========================================================================== */
  function init3DTilt() {
    const tiltElements = document.querySelectorAll('[data-3d-tilt], .pillar-card, .card-wow-service, .project-card-3d');
    
    tiltElements.forEach(el => {
      // S'assurer qu'on a le conteneur de reflet spéculaire
      let glare = el.querySelector('.fee-3d-glare');
      if (!glare) {
        glare = document.createElement('div');
        glare.className = 'fee-3d-glare';
        el.style.position = 'relative';
        el.style.overflow = 'hidden';
        el.appendChild(glare);
      }

      el.addEventListener('mousemove', (e) => {
        const rect = el.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;

        const rotateX = ((y - centerY) / centerY) * -12; // Max 12 deg
        const rotateY = ((x - centerX) / centerX) * 12;

        el.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) translateY(-8px) scale3d(1.02, 1.02, 1.02)`;

        // Déplacer le reflet de lumière
        const glareX = (x / rect.width) * 100;
        const glareY = (y / rect.height) * 100;
        glare.style.background = `radial-gradient(circle at ${glareX}% ${glareY}%, rgba(255, 255, 255, 0.28) 0%, rgba(255, 255, 255, 0) 70%)`;
        glare.style.opacity = '1';
      });

      el.addEventListener('mouseleave', () => {
        el.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0) scale3d(1, 1, 1)';
        glare.style.opacity = '0';
      });
    });
  }

  /* ==========================================================================
     3. CONTRÔLEUR VIDÉO CINÉMATIQUE 4K (Ultra-Fluide, Lazy Loaded, Fallback)
     ========================================================================== */
  function initCinematicVideo() {
    const video = document.getElementById('feeHeroCinematicVideo');
    if (!video) return;

    // Détection connexion / basse consommation
    video.play().catch(() => {
      // Si la lecture automatique est bloquée par le navigateur, activer l'écouteur du premier clic
      const startVideo = () => {
        video.play();
        window.removeEventListener('click', startVideo);
        window.removeEventListener('scroll', startVideo);
      };
      window.addEventListener('click', startVideo, { once: true });
      window.addEventListener('scroll', startVideo, { once: true });
    });
  }

  // Initialisation dès que le DOM est prêt
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      initHero3DCanvas();
      init3DTilt();
      initCinematicVideo();
    });
  } else {
    initHero3DCanvas();
    init3DTilt();
    initCinematicVideo();
  }
})();
