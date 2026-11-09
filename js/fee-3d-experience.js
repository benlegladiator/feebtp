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

    // ========================================================================
    // STRUCTURE ARCHITECTURALE D'AVANT-GARDE : TOUR TORSADÉE TWIST & DIAGRID FEE BTP
    // ========================================================================
    const nodes = [];
    const edges = [];
    const floors = 16; // Gratte-ciel haute ingénierie de 16 niveaux
    const baseRadius = Math.min(width, height) * 0.216; // Rayon proportionnel
    const pointsPerFloor = 8; // Octogone à torsion continue

    for (let f = 0; f < floors; f++) {
      const floorProgress = f / (floors - 1);
      // Effet de torsion progressive hélicoïdale (Turning Torso / Cayan Tower)
      const twistAngle = Math.pow(floorProgress, 1.1) * (Math.PI * 0.45);
      
      // Rayon étagé avec porte-à-faux en porte-à-faux (Sky Terraces tous les 4 étages)
      let floorRadius = baseRadius * (1 - Math.pow(floorProgress, 1.3) * 0.42);
      if (f > 0 && f < floors - 1 && f % 4 === 0) {
        floorRadius *= 1.16; // Balcon / terrasse panoramique en porte-à-faux
      }
      const y = (f - floors * 0.42) * (baseRadius * 0.21);

      // Anneau extérieur octogonal avec torsion hélicoïdale
      for (let i = 0; i < pointsPerFloor; i++) {
        const theta = (i / pointsPerFloor) * Math.PI * 2 + twistAngle;
        nodes.push({
          x0: Math.cos(theta) * floorRadius,
          y0: -y,
          z0: Math.sin(theta) * floorRadius,
          floor: f,
          isCantilever: (f % 4 === 0),
          isCore: false
        });
      }

      // Noyau central porteur cylindrique (Cage d'ascenseurs et voiles béton armé)
      const coreRadius = floorRadius * 0.38;
      for (let i = 0; i < 4; i++) {
        const theta = (i / 4) * Math.PI * 2 + Math.PI / 4 + (twistAngle * 0.5);
        nodes.push({
          x0: Math.cos(theta) * coreRadius,
          y0: -y,
          z0: Math.sin(theta) * coreRadius,
          floor: f,
          isCore: true
        });
      }
    }

    // Double Flèche sommitale / Antennes architecturales de prestige
    const topFloorY = (floors - 1 - floors * 0.42) * (baseRadius * 0.21);
    const apexIndex = nodes.length;
    nodes.push({
      x0: 0,
      y0: -(topFloorY + baseRadius * 0.72),
      z0: 0,
      floor: floors,
      isCore: true,
      isApex: true
    });

    // Lier les arrêtes du bâtiment avec treillis Diagrid en losanges
    const nodeStride = pointsPerFloor + 4; // 8 extérieurs + 4 intérieurs = 12 par étage
    for (let f = 0; f < floors; f++) {
      const baseIdx = f * nodeStride;

      // 1. Périmètre extérieur de la dalle
      for (let i = 0; i < pointsPerFloor; i++) {
        const cur = baseIdx + i;
        const next = baseIdx + ((i + 1) % pointsPerFloor);
        edges.push([cur, next, 'ring']);

        // Colonnes torsadées et treillis Diagrid structural
        if (f < floors - 1) {
          const above = baseIdx + nodeStride + i;
          edges.push([cur, above, 'column']);

          // Diagonales croisées (Diagrid BTP haute résistance sismique)
          const aboveNext = baseIdx + nodeStride + ((i + 1) % pointsPerFloor);
          edges.push([cur, aboveNext, 'brace']);
          const abovePrev = baseIdx + nodeStride + ((i - 1 + pointsPerFloor) % pointsPerFloor);
          edges.push([cur, abovePrev, 'brace-rev']);
        }
      }

      // 2. Noyau intérieur central
      const coreOffset = baseIdx + pointsPerFloor;
      for (let i = 0; i < 4; i++) {
        const curCore = coreOffset + i;
        const nextCore = coreOffset + ((i + 1) % 4);
        edges.push([curCore, nextCore, 'core-ring']);

        if (f < floors - 1) {
          const aboveCore = coreOffset + nodeStride + i;
          edges.push([curCore, aboveCore, 'core-column']);
        }

        // Poutres radiales de solidarisation dalle / noyau
        edges.push([curCore, baseIdx + (i * 2), 'radial']);
      }
    }

    // Relier les derniers étages à l'apex sommital
    const lastFloorBase = (floors - 1) * nodeStride;
    for (let i = 0; i < pointsPerFloor; i++) {
      edges.push([lastFloorBase + i, apexIndex, 'spire']);
    }

    // 3. Fondations Géotechniques & Radier Nervuré FEE BTP
    const foundationPads = [];
    const groundY = (floors * 0.35) * (baseRadius * 0.21);

    for (let i = 0; i < pointsPerFloor; i++) {
      const theta = (i / pointsPerFloor) * Math.PI * 2;
      const padX = Math.cos(theta) * baseRadius;
      const padZ = Math.sin(theta) * baseRadius;
      const padSize = baseRadius * 0.15;
      foundationPads.push({ x: padX, y: groundY, z: padZ, size: padSize });
    }

    // Grille de fondation géotechnique
    const gridLines = [];
    const gridSize = baseRadius * 1.65;
    const gridSteps = 7;
    for (let g = -gridSteps; g <= gridSteps; g++) {
      const pos = (g / gridSteps) * gridSize;
      gridLines.push([
        { x: -gridSize, y: groundY, z: pos },
        { x: gridSize, y: groundY, z: pos }
      ]);
      gridLines.push([
        { x: pos, y: groundY, z: -gridSize },
        { x: pos, y: groundY, z: gridSize }
      ]);
    }

    // Particules volumétriques dorées et d'énergie de construction
    const particles = [];
    for (let p = 0; p < 50; p++) {
      particles.push({
        x: (Math.random() - 0.5) * baseRadius * 3.2,
        y: (Math.random() - 0.5) * baseRadius * 3.2,
        z: (Math.random() - 0.5) * baseRadius * 3.2,
        size: Math.random() * 2.5 + 1.2,
        speed: Math.random() * 0.007 + 0.003,
        alpha: Math.random() * 0.6 + 0.4
      });
    }

    let autoRotation = 0; // Rotation continue à 360°
    let scanLaserY = 0;

    function render() {
      ctx.clearRect(0, 0, width, height);

      // Adaptation responsive progressive : rétrécit nettement sur mobile sans dominer le texte
      let responsiveScale = 1.0;
      let mobileAlpha = 0.95;
      if (width <= 480) {
        responsiveScale = 0.28; // Réduction drastique pour mobile portrait
        mobileAlpha = 0.22;     // Effet filigrane discret derrière la typographie
      } else if (width <= 640) {
        responsiveScale = 0.34;
        mobileAlpha = 0.26;
      } else if (width <= 768) {
        responsiveScale = 0.42;
        mobileAlpha = 0.35;
      } else if (width <= 991) {
        responsiveScale = 0.58;
        mobileAlpha = 0.50;
      }

      // Rotation continue 360° fluide & cinématique
      autoRotation += 0.0075;

      angleX = autoRotation + (targetX * 2);
      angleY += (targetY - angleY) * 0.05;

      const cosX = Math.cos(angleX);
      const sinX = Math.sin(angleX);
      const cosY = Math.cos(angleY + 0.18);
      const sinY = Math.sin(angleY + 0.18);

      // Centrage équilibré selon la taille d'écran
      const cx = width > 992 ? width * 0.74 : width * 0.50;
      const cy = width > 992 ? height * 0.50 : (width <= 640 ? height * 0.46 : height * 0.48);
      const fov = 750;

      // Opacité adaptée sur mobile pour laisser respirer les textes et photos
      const prevAlpha = ctx.globalAlpha;
      ctx.globalAlpha = mobileAlpha;

      // Fonction de projection 3D mise à l'échelle responsive
      function projectPoint(x, y, z) {
        const sx = x * responsiveScale;
        const sy = y * responsiveScale;
        const sz = z * responsiveScale;
        let x1 = sx * cosX - sz * sinX;
        let z1 = sz * cosX + sx * sinX;
        let y1 = sy * cosY - z1 * sinY;
        let z2 = z1 * cosY + sy * sinY + 750;
        const scale = fov / Math.max(100, z2);
        return {
          px: cx + x1 * scale,
          py: cy + y1 * scale,
          scale: scale,
          z: z2
        };
      }

      // --- A. RADIER GÉNÉRAL & GRILLE AU SOL (FONDATIONS FEE BTP) ---
      ctx.lineWidth = 1.6;
      ctx.strokeStyle = 'rgba(255, 122, 0, 0.35)'; // Grille au sol ambre orangée FEE BTP
      for (let g = 0; g < gridLines.length; g++) {
        const pt1 = projectPoint(gridLines[g][0].x, gridLines[g][0].y, gridLines[g][0].z);
        const pt2 = projectPoint(gridLines[g][1].x, gridLines[g][1].y, gridLines[g][1].z);
        ctx.beginPath();
        ctx.moveTo(pt1.px, pt1.py);
        ctx.lineTo(pt2.px, pt2.py);
        ctx.stroke();
      }

      // Semelles de fondation béton au sol
      for (let s = 0; s < foundationPads.length; s++) {
        const pad = foundationPads[s];
        const half = pad.size;
        const pCorners = [
          projectPoint(pad.x - half, pad.y, pad.z - half),
          projectPoint(pad.x + half, pad.y, pad.z - half),
          projectPoint(pad.x + half, pad.y, pad.z + half),
          projectPoint(pad.x - half, pad.y, pad.z + half)
        ];
        ctx.beginPath();
        ctx.moveTo(pCorners[0].px, pCorners[0].py);
        for (let c = 1; c < pCorners.length; c++) {
          ctx.lineTo(pCorners[c].px, pCorners[c].py);
        }
        ctx.closePath();
        ctx.fillStyle = 'rgba(255, 122, 0, 0.20)';
        ctx.fill();
        ctx.strokeStyle = 'rgba(245, 158, 11, 0.85)';
        ctx.lineWidth = 2.4;
        ctx.stroke();
      }

      // Projection des nœuds de la tour
      const projected = [];
      for (let i = 0; i < nodes.length; i++) {
        const n = nodes[i];
        projected.push(projectPoint(n.x0, n.y0, n.z0));
      }

      // --- B. TRACÉ DES ARRÊTES ARCHITECTURALES HAUTE DÉFINITION FEE BTP ---
      for (let e = 0; e < edges.length; e++) {
        const p1 = projected[edges[e][0]];
        const p2 = projected[edges[e][1]];
        const type = edges[e][2];

        ctx.beginPath();
        ctx.moveTo(p1.px, p1.py);
        ctx.lineTo(p2.px, p2.py);

        if (type === 'spire') {
          ctx.strokeStyle = '#ffffff'; // Flèche blanche éclatante
          ctx.lineWidth = 3.2;
        } else if (type === 'column') {
          ctx.strokeStyle = '#ff7a00'; // Colonnes porteuses Orange BTP signature
          ctx.lineWidth = 2.8;
        } else if (type === 'brace' || type === 'brace-rev') {
          ctx.strokeStyle = 'rgba(245, 158, 11, 0.65)'; // Treillis Diagrid Ambre
          ctx.lineWidth = 1.6;
        } else if (type === 'core-column' || type === 'core-ring') {
          ctx.strokeStyle = '#ffffff'; // Cœur béton armé blanc haute luminosité
          ctx.lineWidth = 2.2;
        } else if (type === 'radial') {
          ctx.strokeStyle = 'rgba(255, 200, 150, 0.40)';
          ctx.lineWidth = 1.4;
        } else {
          ctx.strokeStyle = '#e65100'; // Dalles d'étages orange soutenu FEE BTP
          ctx.lineWidth = 2.0;
        }
        ctx.stroke();
      }

      // --- C. SCAN LASER DE CHANTIER (Orange Laser de mesure Topographique) ---
      scanLaserY += 0.015;
      const currentLaserFloor = (Math.sin(scanLaserY) * 0.5 + 0.5) * (floors - 1);
      const laserFloorInt = Math.floor(currentLaserFloor);
      if (laserFloorInt < floors - 1) {
        const baseScan = laserFloorInt * nodeStride;
        ctx.beginPath();
        for (let i = 0; i < pointsPerFloor; i++) {
          const pt = projected[baseScan + i];
          if (i === 0) ctx.moveTo(pt.px, pt.py);
          else ctx.lineTo(pt.px, pt.py);
        }
        ctx.closePath();
        ctx.strokeStyle = '#ffffff';
        ctx.lineWidth = 2.5;
        ctx.shadowColor = '#ff7a00';
        ctx.shadowBlur = 18;
        ctx.stroke();
        ctx.shadowBlur = 0;
      }

      // --- D. NŒUDS LUMINEUX D'ASSEMBLAGE (ÉTOILES DE STRUCTURE) ---
      for (let i = 0; i < projected.length; i++) {
        const p = projected[i];
        const isApex = nodes[i].isApex;
        const radiusPt = isApex ? 5.0 : Math.max(1.8, p.scale * 3.0);

        ctx.beginPath();
        ctx.arc(p.px, p.py, radiusPt, 0, Math.PI * 2);
        ctx.fillStyle = isApex ? '#ffffff' : (nodes[i].isCore ? '#ffffff' : '#f59e0b');
        ctx.shadowColor = isApex ? '#ffffff' : '#ff7a00';
        ctx.shadowBlur = isApex ? 22 : 10;
        ctx.fill();
        ctx.shadowBlur = 0;
      }

      // --- E. PARTICULES DE POUSSIÈRE LUMINEUSE DORÉE ---
      for (let i = 0; i < particles.length; i++) {
        const pt = particles[i];
        pt.y -= pt.speed * 70;
        if (pt.y < -baseRadius * 2) pt.y = baseRadius * 2;

        const proj = projectPoint(pt.x, pt.y, pt.z);
        ctx.beginPath();
        ctx.arc(proj.px, proj.py, Math.max(0.6, pt.size * proj.scale), 0, Math.PI * 2);
        ctx.fillStyle = `rgba(245, 158, 11, ${pt.alpha})`;
        ctx.fill();
      }

      ctx.globalAlpha = prevAlpha;
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
