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

    let nodes = [];
    let edges = [];
    let foundationPads = [];
    let gridLines = [];
    let particles = [];
    let baseRadius = 120;
    let groundY = 0;
    let archMaxHeight = 150;

    function buildStructure() {
      nodes = [];
      edges = [];
      foundationPads = [];
      gridLines = [];
      particles = [];

      // Échelle dynamique proportionnelle pour FEE BTP
      if (width <= 480) {
        baseRadius = Math.min(width, height) * 0.12; // Compact sur mobile
      } else if (width <= 768) {
        baseRadius = Math.min(width, height) * 0.15;
      } else if (width <= 991) {
        baseRadius = Math.min(width, height) * 0.18;
      } else {
        baseRadius = Math.min(width, height) * 0.22;
      }

    // --- 1. SOCLE EN GRADINS ÉTAGÉS & MURS DE SOUTÈNEMENT (3 Terrasses) ---
    const terraceCount = 3;
    const pointsPerTerrace = 12; // Dodécagone majestueux
    const terraceNodesOffset = [];

    for (let t = 0; t < terraceCount; t++) {
      const tProgress = t / (terraceCount - 1);
      const tRadius = baseRadius * (1.35 - tProgress * 0.35); // Base large qui rétrécit vers le dôme
      const tY = (terraceCount * 0.25 - t * 0.28) * baseRadius; // Étagé vers le haut
      const startIdx = nodes.length;
      terraceNodesOffset.push(startIdx);

      for (let i = 0; i < pointsPerTerrace; i++) {
        const theta = (i / pointsPerTerrace) * Math.PI * 2;
        nodes.push({
          x0: Math.cos(theta) * tRadius,
          y0: tY,
          z0: Math.sin(theta) * tRadius,
          tier: t,
          type: 'terrace'
        });
      }

      // Anneau de terrasse
      for (let i = 0; i < pointsPerTerrace; i++) {
        const cur = startIdx + i;
        const next = startIdx + ((i + 1) % pointsPerTerrace);
        edges.push([cur, next, 'terrace-ring']);
      }

      // Contreforts de soutènement verticaux reliant les terrasses
      if (t > 0) {
        const prevStart = terraceNodesOffset[t - 1];
        for (let i = 0; i < pointsPerTerrace; i++) {
          edges.push([prevStart + i, startIdx + i, 'buttress']);
          // Diagonale anti-cisaillement
          if (i % 2 === 0) {
            edges.push([prevStart + i, startIdx + ((i + 1) % pointsPerTerrace), 'terrace-brace']);
          }
        }
      }
    }

    // --- 2. DÔME GÉODÉSIQUE BIOCLIMATIQUE (Cœur du Complexe) ---
    const domeRings = 4;
    const pointsPerDomeRing = 12;
    const domeCenterY = (terraceCount * 0.25 - (terraceCount - 1) * 0.28) * baseRadius;
    const domeNodesOffset = [];

    for (let r = 0; r < domeRings; r++) {
      const phi = (r / domeRings) * (Math.PI * 0.42); // Demi-sphère jusqu'au zénith
      const rRadius = baseRadius * 0.95 * Math.cos(phi);
      const rY = domeCenterY - Math.sin(phi) * baseRadius * 0.75;
      const startIdx = nodes.length;
      domeNodesOffset.push(startIdx);

      for (let i = 0; i < pointsPerDomeRing; i++) {
        const theta = (i / pointsPerDomeRing) * Math.PI * 2;
        nodes.push({
          x0: Math.cos(theta) * rRadius,
          y0: rY,
          z0: Math.sin(theta) * rRadius,
          ring: r,
          type: 'dome'
        });
      }

      // Anneaux horizontaux du dôme
      for (let i = 0; i < pointsPerDomeRing; i++) {
        const cur = startIdx + i;
        const next = startIdx + ((i + 1) % pointsPerDomeRing);
        edges.push([cur, next, 'dome-ring']);

        // Méridiens du dôme reliant les anneaux
        if (r > 0) {
          const prevStart = domeNodesOffset[r - 1];
          edges.push([prevStart + i, cur, 'dome-meridian']);
          // Maillage géodésique triangulé
          edges.push([prevStart + i, startIdx + ((i + 1) % pointsPerDomeRing), 'dome-tri']);
        }
      }
    }

    // Apex central du dôme
    const domeApexIdx = nodes.length;
    nodes.push({
      x0: 0,
      y0: domeCenterY - baseRadius * 0.80,
      z0: 0,
      type: 'dome-apex',
      isApex: true
    });
    const lastDomeStart = domeNodesOffset[domeRings - 1];
    for (let i = 0; i < pointsPerDomeRing; i++) {
      edges.push([lastDomeStart + i, domeApexIdx, 'dome-apex-rib']);
    }

    // --- 3. MONUMENTALES ARCHES PARABOLIQUES CROISÉES (Haute Voûte BTP) ---
    // Deux arches paraboliques géantes s'élançant au-dessus du dôme
    const archPoints = 13; // Précision de la courbe parabolique
    archMaxHeight = baseRadius * 1.45;
    const archSpan = baseRadius * 1.55;

    // Arche 1 (Axe X-Z principal)
    const arch1Indices = [];
    for (let a = 0; a < archPoints; a++) {
      const t = (a / (archPoints - 1)) * 2 - 1; // de -1 à +1
      const x = t * archSpan;
      const z = 0;
      // Parabole y = - (1 - t^2) * h + yBase
      const y = (terraceCount * 0.25 * baseRadius) - (1 - t * t) * archMaxHeight;
      const idx = nodes.length;
      arch1Indices.push(idx);
      nodes.push({ x0: x, y0: y, z0: z, type: 'arch1', isArch: true });
    }
    for (let a = 0; a < archPoints - 1; a++) {
      edges.push([arch1Indices[a], arch1Indices[a + 1], 'monumental-arch']);
    }

    // Arche 2 (Croisée à 90°, axe Z-X transversal)
    const arch2Indices = [];
    for (let a = 0; a < archPoints; a++) {
      const t = (a / (archPoints - 1)) * 2 - 1;
      const x = 0;
      const z = t * archSpan;
      const y = (terraceCount * 0.25 * baseRadius) - (1 - t * t) * archMaxHeight;
      const idx = nodes.length;
      arch2Indices.push(idx);
      nodes.push({ x0: x, y0: y, z0: z, type: 'arch2', isArch: true });
    }
    for (let a = 0; a < archPoints - 1; a++) {
      edges.push([arch2Indices[a], arch2Indices[a + 1], 'monumental-arch']);
    }

    // Câbles de haubanage reliant le sommet des arches paraboliques aux terrasses
    const archApex1 = arch1Indices[Math.floor(archPoints / 2)];
    const archApex2 = arch2Indices[Math.floor(archPoints / 2)];
    const outerTerraceStart = terraceNodesOffset[0];
    for (let i = 0; i < pointsPerTerrace; i += 3) {
      edges.push([archApex1, outerTerraceStart + i, 'cable-stay']);
      edges.push([archApex2, outerTerraceStart + ((i + 1) % pointsPerTerrace), 'cable-stay']);
    }

    // --- 4. MASSIFS DE FONDATION GÉOTECHNIQUES & RADIER (4 Piliers d'ancrage des Arches) ---
    groundY = terraceCount * 0.25 * baseRadius;
    foundationPads.push(
      { x: archSpan, y: groundY, z: 0, size: baseRadius * 0.20 },
      { x: -archSpan, y: groundY, z: 0, size: baseRadius * 0.20 },
      { x: 0, y: groundY, z: archSpan, size: baseRadius * 0.20 },
      { x: 0, y: groundY, z: -archSpan, size: baseRadius * 0.20 }
    );

    // Grille de fondation géotechnique (Sol latéritique / Radier général)
    const gridSize = baseRadius * 1.85;
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
    for (let p = 0; p < 40; p++) {
      particles.push({
        x: (Math.random() - 0.5) * baseRadius * 3.4,
        y: (Math.random() - 0.5) * baseRadius * 2.8,
        z: (Math.random() - 0.5) * baseRadius * 3.4,
        size: Math.random() * 2.5 + 1.2,
        speed: Math.random() * 0.007 + 0.003,
        alpha: Math.random() * 0.6 + 0.4
      });
    }
  }

  function resize() {
    const parent = canvas.parentElement;
    width = canvas.width = parent.clientWidth || window.innerWidth;
    height = canvas.height = parent.clientHeight || window.innerHeight;
    buildStructure();
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

  let autoRotation = 0;
  let scanLaserY = 0;

  function render() {
    ctx.clearRect(0, 0, width, height);

    // Adaptation responsive progressive : rétrécit nettement sur mobile sans dominer le texte
    let mobileAlpha = 0.95;
    let lineMultiplier = 1.0;
    if (width <= 480) {
      mobileAlpha = 0.30;
      lineMultiplier = 0.55;
    } else if (width <= 640) {
      mobileAlpha = 0.40;
      lineMultiplier = 0.65;
    } else if (width <= 768) {
      mobileAlpha = 0.50;
      lineMultiplier = 0.75;
    } else if (width <= 991) {
      mobileAlpha = 0.68;
      lineMultiplier = 0.85;
    }

    // Rotation continue 360° fluide & cinématique
    autoRotation += 0.0065;

    angleX = autoRotation + (targetX * 2);
    angleY += (targetY - angleY) * 0.05;

    const cosX = Math.cos(angleX);
    const sinX = Math.sin(angleX);
    const cosY = Math.cos(angleY + 0.24); // Vue plongeante héroïque mettant en valeur les arches
    const sinY = Math.sin(angleY + 0.24);

    // Centrage équilibré selon la taille d'écran
    const cx = width > 992 ? width * 0.74 : width * 0.50;
    const cy = width > 992 ? height * 0.52 : height * 0.48;
    const fov = 750;

    // Opacité adaptée sur mobile pour laisser respirer les textes et photos
    const prevAlpha = ctx.globalAlpha;
    ctx.globalAlpha = mobileAlpha;

    // Fonction de projection 3D mise à l'échelle responsive
    function projectPoint(x, y, z) {
      let x1 = x * cosX - z * sinX;
      let z1 = z * cosX + x * sinX;
      let y1 = y * cosY - z1 * sinY;
      let z2 = z1 * cosY + y * sinY + 750;
        const scale = fov / Math.max(100, z2);
        return {
          px: cx + x1 * scale,
          py: cy + y1 * scale,
          scale: scale,
          z: z2
        };
      }

      // --- A. GRILLE GÉOTECHNIQUE AU SOL (100% AMBRE & TERRACOTTA FEE BTP - ZÉRO BLEU) ---
      ctx.lineWidth = 1.5 * lineMultiplier;
      ctx.strokeStyle = 'rgba(234, 88, 12, 0.30)'; // Grille au sol ambre latérite FEE BTP
      for (let g = 0; g < gridLines.length; g++) {
        const pt1 = projectPoint(gridLines[g][0].x, gridLines[g][0].y, gridLines[g][0].z);
        const pt2 = projectPoint(gridLines[g][1].x, gridLines[g][1].y, gridLines[g][1].z);
        ctx.beginPath();
        ctx.moveTo(pt1.px, pt1.py);
        ctx.lineTo(pt2.px, pt2.py);
        ctx.stroke();
      }

      // Semelles de fondation béton d'ancrage des arches
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
        ctx.fillStyle = 'rgba(255, 106, 0, 0.22)';
        ctx.fill();
        ctx.strokeStyle = '#ff7a00';
        ctx.lineWidth = 2.4 * lineMultiplier;
        ctx.stroke();
      }

      // Projection de tous les nœuds
      const projected = [];
      for (let i = 0; i < nodes.length; i++) {
        const n = nodes[i];
        projected.push(projectPoint(n.x0, n.y0, n.z0));
      }

      // --- B. TRACÉ DES ARRÊTES STRUCTURALES (100% COULEURS OFFICIELLES FEE BTP : ORANGE, AMBRE, BLANC) ---
      for (let e = 0; e < edges.length; e++) {
        const p1 = projected[edges[e][0]];
        const p2 = projected[edges[e][1]];
        const type = edges[e][2];

        ctx.beginPath();
        ctx.moveTo(p1.px, p1.py);
        ctx.lineTo(p2.px, p2.py);

        if (type === 'monumental-arch') {
          // Grandes Arches Paraboliques maîtresses : Orange Flamboyant Ultra-Épais
          ctx.strokeStyle = '#ff6a00';
          ctx.lineWidth = 3.6 * lineMultiplier;
        } else if (type === 'cable-stay') {
          // Câbles de haubanage suspendus : Blanc pur haute précision
          ctx.strokeStyle = 'rgba(255, 255, 255, 0.75)';
          ctx.lineWidth = 1.3 * lineMultiplier;
        } else if (type === 'dome-apex-rib' || type === 'dome-ring') {
          // Dôme géodésique : Ambre doré rayonnant
          ctx.strokeStyle = '#f59e0b';
          ctx.lineWidth = 2.0 * lineMultiplier;
        } else if (type === 'dome-meridian' || type === 'dome-tri') {
          // Maillage triangulé du dôme : Ambre doux
          ctx.strokeStyle = 'rgba(245, 158, 11, 0.50)';
          ctx.lineWidth = 1.2 * lineMultiplier;
        } else if (type === 'terrace-ring') {
          // Gradins de soutènement : Orange BTP profond
          ctx.strokeStyle = '#ea580c';
          ctx.lineWidth = 2.2 * lineMultiplier;
        } else if (type === 'buttress' || type === 'terrace-brace') {
          // Contreforts de soutènement : Ambre chaud
          ctx.strokeStyle = 'rgba(217, 119, 6, 0.60)';
          ctx.lineWidth = 1.6 * lineMultiplier;
        } else {
          ctx.strokeStyle = '#ff7a00';
          ctx.lineWidth = 1.8 * lineMultiplier;
        }
        ctx.stroke();
      }

      // --- C. SCAN LASER DE CHANTIER (Orange Laser Topographique) ---
      scanLaserY += 0.015;
      const scanT = Math.sin(scanLaserY) * 0.5 + 0.5;
      const laserYPos = groundY - scanT * archMaxHeight;
      const laserRadius = baseRadius * (1.6 - scanT * 0.8);
      
      ctx.beginPath();
      for (let i = 0; i <= 24; i++) {
        const theta = (i / 24) * Math.PI * 2;
        const pt = projectPoint(Math.cos(theta) * laserRadius, laserYPos, Math.sin(theta) * laserRadius);
        if (i === 0) ctx.moveTo(pt.px, pt.py);
        else ctx.lineTo(pt.px, pt.py);
      }
      ctx.closePath();
      ctx.strokeStyle = '#ffffff';
      ctx.lineWidth = 2.4;
      ctx.shadowColor = '#ff6a00';
      ctx.shadowBlur = 18;
      ctx.stroke();
      ctx.shadowBlur = 0;

      // --- D. NŒUDS LUMINEUX D'ASSEMBLAGE (ÉTOILES DE STRUCTURE) ---
      for (let i = 0; i < projected.length; i++) {
        const p = projected[i];
        const isArch = nodes[i].isArch;
        const isApex = nodes[i].isApex;
        const radiusPt = isApex ? 5.5 : (isArch ? 3.4 : Math.max(1.6, p.scale * 2.8));

        ctx.beginPath();
        ctx.arc(p.px, p.py, radiusPt, 0, Math.PI * 2);
        ctx.fillStyle = isApex ? '#ffffff' : (isArch ? '#ffffff' : '#f59e0b');
        ctx.shadowColor = isApex ? '#ffffff' : '#ff7a00';
        ctx.shadowBlur = isApex ? 24 : 12;
        ctx.fill();
        ctx.shadowBlur = 0;
      }

      // --- E. PARTICULES DE POUSSIÈRE LUMINEUSE DORÉE ---
      for (let i = 0; i < particles.length; i++) {
        const pt = particles[i];
        pt.y -= pt.speed * 60;
        if (pt.y < -baseRadius * 1.8) pt.y = baseRadius * 1.8;

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
