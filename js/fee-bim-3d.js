/**
 * FEE BTP SARL - Studio 3D BIM Architectural & Structural Viewer Haute Définition
 * Version Architecte Prestige :
 * - Villa Contemporaine R+1 avec Baies Coulissantes & Porte Pivotante
 * - Piscine à Débordement (Infinity Pool) avec Eau Turquoise Translucide & Transats
 * - Aménagement Intérieur Contemporain (Salon d'Architecte, Cuisine Îlot, Suite R+1)
 * - Escalier Hélicoïdal / Suspendu en Teck Noble & Garde-corps Vitré
 * - Éclairage Architectural Bi-Ambiance : Mode Plein Soleil & Mode Nuit VIP avec Leds Dorées
 * - Plan Filaire Orthogonal (Blueprint Revit / AutoCAD) & Mode Béton Brut Armé
 */

(function() {
  'use strict';

  let scene, camera, renderer, controls;
  let modelGroup, foundationGroup, rdcGroup, r1Group, roofGroup;
  let isExploded = false;
  let areDoorsOpen = false;
  let isNightMode = false;
  let currentMode = 'prestige'; // 'prestige', 'blueprint', 'concrete'
  let autoRotate = true;
  let animationFrameId;

  // Lumières & Ambiance
  let ambientLight, sunLight, fillLight, interiorLight, poolLight;
  let ledMaterials = [];

  // Objets interactifs mobiles (ouvertures)
  let doorPivot, slidingGlassPanel, balconyDoor, poolWaterMesh;

  const targetPositions = {
    foundation: { normal: 0, exploded: -3.8 },
    rdc: { normal: 0, exploded: 0 },
    r1: { normal: 0, exploded: 3.8 },
    roof: { normal: 0, exploded: 7.6 }
  };

  function initBIM() {
    const container = document.getElementById('feeBimCanvasWrap');
    const canvas = document.getElementById('feeBim3DCanvas');
    if (!container || !canvas) return;

    if (typeof THREE === 'undefined') {
      console.warn('Three.js non chargé.');
      return;
    }

    const width = container.clientWidth;
    const height = container.clientHeight;

    // 1. Scène & Atmosphère architecturale lumineuse (Ardoise & Studio BTP)
    scene = new THREE.Scene();
    scene.background = new THREE.Color(0x0f172a); // Ardoise / Anthracite BTP signature FEE BTP
    scene.fog = new THREE.FogExp2(0x0f172a, 0.016);

    // 2. Caméra (Dézoom proportionné sur mobile pour que l'ensemble avec terrasse et piscine tienne dans le cadre)
    camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
    if (width <= 768) {
      camera.position.set(30, 24, 36);
    } else {
      camera.position.set(19, 14, 23);
    }

    // 3. Renderer WebGL avec ombres douces et antialiasing
    renderer = new THREE.WebGLRenderer({
      canvas: canvas,
      antialias: true,
      alpha: true,
      powerPreference: 'high-performance'
    });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;

    // 4. Contrôles OrbitControls
    if (typeof THREE.OrbitControls !== 'undefined') {
      controls = new THREE.OrbitControls(camera, renderer.domElement);
      controls.enableDamping = true;
      controls.dampingFactor = 0.05;
      controls.maxPolarAngle = Math.PI / 2 + 0.04;
      controls.minDistance = 7;
      controls.maxDistance = 50;
      controls.target.set(0, 3.2, 0);
    }

    // 5. Éclairage Studio & Lumière du jour
    setupLights();

    // 6. Construction de la maquette détaillée haut de gamme
    buildArchitecturalModel();

    // 7. Grille de sol et dallage extérieur
    buildGridFloor();

    // 8. Événements & Écouteurs de la barre d'outils
    window.addEventListener('resize', onWindowResize);
    setupInteractions();

    // 9. Boucle d'animation fluide 60fps
    animate();
  }

  function setupLights() {
    ambientLight = new THREE.AmbientLight(0xffffff, 0.7);
    scene.add(ambientLight);

    // Soleil principal BTP chaud
    sunLight = new THREE.DirectionalLight(0xfff7ed, 1.4);
    sunLight.position.set(22, 28, 16);
    sunLight.castShadow = true;
    sunLight.shadow.mapSize.width = 1024;
    sunLight.shadow.mapSize.height = 1024;
    sunLight.shadow.camera.near = 0.5;
    sunLight.shadow.camera.far = 70;
    const d = 18;
    sunLight.shadow.camera.left = -d;
    sunLight.shadow.camera.right = d;
    sunLight.shadow.camera.top = d;
    sunLight.shadow.camera.bottom = -d;
    scene.add(sunLight);

    // Remplissage d'adoucissement naturel
    fillLight = new THREE.DirectionalLight(0xe2e8f0, 0.45);
    fillLight.position.set(-18, 12, -15);
    scene.add(fillLight);

    // Éclairage d'ambiance intérieur (lumières du salon & lustre)
    interiorLight = new THREE.PointLight(0xfbbf24, 1.4, 25);
    interiorLight.position.set(0, 3.5, 0);
    scene.add(interiorLight);

    // Projecteur subaquatique turquoise pour la piscine à débordement
    poolLight = new THREE.PointLight(0x06b6d4, 1.2, 14);
    poolLight.position.set(-3.4, 0.8, 6.2);
    scene.add(poolLight);
  }

  function buildGridFloor() {
    // Grille de sol lumineuse : Axe Jaune Doré et Carroyage Bleu Azur
    const grid = new THREE.GridHelper(34, 34, 0xf59e0b, 0x1d4ed8);
    grid.position.y = -0.05;
    scene.add(grid);
  }

  function createMaterial(type, colorHex, opacity = 1, roughness = 0.4, metalness = 0.15) {
    if (type === 'glass') {
      return new THREE.MeshPhysicalMaterial({
        color: 0x38bdf8,
        transmission: 0.85,
        opacity: 0.55,
        transparent: true,
        roughness: 0.08,
        metalness: 0.1,
        reflectivity: 0.95,
        clearcoat: 1.0,
        clearcoatRoughness: 0.1
      });
    }

    if (type === 'water') {
      return new THREE.MeshPhysicalMaterial({
        color: 0x06b6d4,
        transmission: 0.88,
        opacity: 0.82,
        transparent: true,
        roughness: 0.04,
        metalness: 0.15,
        reflectivity: 0.98,
        clearcoat: 1.0
      });
    }

    if (type === 'led') {
      const mat = new THREE.MeshStandardMaterial({
        color: colorHex,
        emissive: colorHex,
        emissiveIntensity: 0.4,
        roughness: 0.2,
        metalness: 0.2
      });
      ledMaterials.push(mat);
      return mat;
    }

    return new THREE.MeshStandardMaterial({
      color: colorHex,
      roughness: roughness,
      metalness: metalness,
      transparent: opacity < 1,
      opacity: opacity
    });
  }

  function addMeshWithEdges(group, geo, mat, isOpening = false) {
    const clonedMat = mat.clone();
    const mesh = new THREE.Mesh(geo, clonedMat);
    mesh.castShadow = true;
    mesh.receiveShadow = true;
    mesh.userData.origColor = clonedMat.color ? clonedMat.color.getHex() : 0xffffff;
    mesh.userData.origOpacity = clonedMat.opacity;
    mesh.userData.origTransparent = clonedMat.transparent;
    mesh.userData.origRoughness = clonedMat.roughness;
    mesh.userData.origMetalness = clonedMat.metalness;
    mesh.userData.isGlass = (clonedMat.transmission > 0 || clonedMat.transparent);
    mesh.userData.isOpening = isOpening;

    // Arêtes filaires CAO : Jaune Or pour les ouvertures & Bleu Azur pour la structure
    const edgesGeo = new THREE.EdgesGeometry(geo, 15);
    const edgesMat = new THREE.LineBasicMaterial({
      color: isOpening ? 0xfbbf24 : 0x38bdf8,
      linewidth: isOpening ? 2 : 1,
      transparent: true,
      opacity: 0.95
    });
    const edgeLines = new THREE.LineSegments(edgesGeo, edgesMat);
    edgeLines.visible = false;
    mesh.add(edgeLines);
    mesh.userData.edgeLines = edgeLines;

    group.add(mesh);
    return mesh;
  }

  function buildArchitecturalModel() {
    modelGroup = new THREE.Group();
    scene.add(modelGroup);

    foundationGroup = new THREE.Group();
    rdcGroup = new THREE.Group();
    r1Group = new THREE.Group();
    roofGroup = new THREE.Group();

    modelGroup.add(foundationGroup);
    modelGroup.add(rdcGroup);
    modelGroup.add(r1Group);
    modelGroup.add(roofGroup);

    // =========================================================================
    // MATÉRIAUX NOBLES HAUT DE GAMME : MARRON TECK, OR, BLEU & MARBRE
    // =========================================================================
    const matConcrete = createMaterial('solid', 0x94a3b8, 1, 0.85, 0.05); // Béton armé clair
    const matRebar = createMaterial('solid', 0xf59e0b, 1, 0.35, 0.7); // Acier HA Jaune Doré
    const matWhiteWall = createMaterial('solid', 0xfffef7, 1, 0.45, 0.05); // Murs Blanc Solaire Lumineux
    const matMarble = createMaterial('solid', 0xf1f5f9, 1, 0.25, 0.2); // Marbre blanc margelles & cuisine
    const matBrownPillar = createMaterial('solid', 0x78350f, 1, 0.5, 0.15); // Piliers Marron Teck / Pierre de taille
    const matBrownWood = createMaterial('solid', 0x92400e, 1, 0.4, 0.1); // Bois teck noble chaleureux
    const matGoldYellow = createMaterial('solid', 0xf59e0b, 1, 0.25, 0.85); // Jaune Or métallique brossé
    const matAzureFrame = createMaterial('solid', 0x1e293b, 1, 0.28, 0.65); // Menuiseries Noir Sablé / Anthracite
    const matNavyBlue = createMaterial('solid', 0xea580c, 1, 0.35, 0.4); // Acrotères & bandeaux Orange BTP signature FEE BTP
    const matPergola = createMaterial('solid', 0xa16207, 1, 0.35, 0.15); // Pergola Marron Chêne Doré
    const matGlass = createMaterial('glass', 0x38bdf8); // Vitrage Bleu Ciel Azur
    const matWater = createMaterial('water', 0x06b6d4); // Eau de piscine turquoise
    const matFabric = createMaterial('solid', 0xe2e8f0, 1, 0.8, 0.05); // Canapé tissu velours contemporain
    const matGreenery = createMaterial('solid', 0x15803d, 1, 0.7, 0.05); // Feuillages paysagers contemporains
    const matLedGlow = createMaterial('led', 0xfffbeb); // Bandeaux LED chauds

    // =========================================================================
    // NIVEAU 0 : FONDATIONS GÉOTECHNIQUES & SEMELLES BÉTON ARMÉ
    // =========================================================================
    const slabGeo = new THREE.BoxGeometry(10.2, 0.6, 8.2);
    const foundationSlab = addMeshWithEdges(foundationGroup, slabGeo, matConcrete);
    foundationSlab.position.set(0, 0.3, 0);

    const footingPositions = [
      [-4.2, -0.6, -3.2], [0, -0.6, -3.2], [4.2, -0.6, -3.2],
      [-4.2, -0.6, 3.2],  [0, -0.6, 3.2],  [4.2, -0.6, 3.2]
    ];
    footingPositions.forEach(pos => {
      const footingGeo = new THREE.BoxGeometry(1.6, 0.8, 1.6);
      const footing = addMeshWithEdges(foundationGroup, footingGeo, matConcrete);
      footing.position.set(pos[0], pos[1], pos[2]);

      const rebarGeo = new THREE.CylinderGeometry(0.04, 0.04, 1.2, 8);
      for (let r = 0; r < 4; r++) {
        const rebar = addMeshWithEdges(foundationGroup, rebarGeo, matRebar);
        const rx = pos[0] + (r % 2 === 0 ? 0.35 : -0.35);
        const rz = pos[2] + (r > 1 ? 0.35 : -0.35);
        rebar.position.set(rx, pos[1] + 0.4, rz);
      }
    });

    // =========================================================================
    // 0.1 NOUVEAUTÉ PRESTIGE : PISCINE À DÉBORDEMENT & TERRASSE SUNSET
    // =========================================================================
    // Terrasse extérieure avancée en grès cérame clair
    const deckGeo = new THREE.BoxGeometry(6.4, 0.25, 4.6);
    const terraceDeck = addMeshWithEdges(foundationGroup, deckGeo, matConcrete);
    terraceDeck.position.set(-3.2, 0.2, 5.8);

    // Margelle en marbre blanc de la piscine
    const poolBorderGeo = new THREE.BoxGeometry(5.4, 0.42, 3.6);
    const poolBorder = addMeshWithEdges(foundationGroup, poolBorderGeo, matMarble);
    poolBorder.position.set(-3.2, 0.35, 6.0);

    // Eau turquoise translucide miroitante
    const poolWaterGeo = new THREE.BoxGeometry(4.9, 0.32, 3.1);
    poolWaterMesh = addMeshWithEdges(foundationGroup, poolWaterGeo, matWater);
    poolWaterMesh.position.set(-3.2, 0.43, 6.0);

    // Deux Transats Contemporains en Teck & Coussin blanc
    [-4.5, -3.5].forEach(xPos => {
      const loungerWood = addMeshWithEdges(foundationGroup, new THREE.BoxGeometry(0.7, 0.15, 1.8), matBrownWood);
      loungerWood.position.set(xPos, 0.4, 4.2);
      const loungerCushion = addMeshWithEdges(foundationGroup, new THREE.BoxGeometry(0.65, 0.08, 1.7), matFabric);
      loungerCushion.position.set(xPos, 0.52, 4.2);
    });

    // Petite table basse de piscine
    const poolTable = addMeshWithEdges(foundationGroup, new THREE.BoxGeometry(0.5, 0.25, 0.5), matBrownWood);
    poolTable.position.set(-2.6, 0.45, 4.2);

    // =========================================================================
    // NIVEAU 1 : REZ-DE-CHAUSSÉE (AVEC AMÉNAGEMENT SALON & ESCALIER SUSPENDU)
    // =========================================================================
    // Piliers structuraux en Marron Teck / Pierre Noble
    const colGeo = new THREE.BoxGeometry(0.5, 3.0, 0.5);
    footingPositions.forEach(pos => {
      const col = addMeshWithEdges(rdcGroup, colGeo, matBrownPillar);
      col.position.set(pos[0], 2.1, pos[2]);
    });

    // Murs extérieurs maçonnés
    const rearWallL = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(3.5, 3.0, 0.25), matWhiteWall);
    rearWallL.position.set(-3.25, 2.1, -3.95);
    const rearWallR = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(3.5, 3.0, 0.25), matWhiteWall);
    rearWallR.position.set(3.25, 2.1, -3.95);
    const rearWallTop = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(3.0, 0.8, 0.25), matWhiteWall);
    rearWallTop.position.set(0, 3.2, -3.95);
    const rearWallBottom = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(3.0, 1.0, 0.25), matWhiteWall);
    rearWallBottom.position.set(0, 1.1, -3.95);

    // Fenêtre arrière
    const rearWinFrame = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(2.9, 1.2, 0.28), matAzureFrame, true);
    rearWinFrame.position.set(0, 2.2, -3.95);
    const rearWinGlass = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(2.7, 1.0, 0.08), matGlass, true);
    rearWinGlass.position.set(0, 2.2, -3.95);

    // Mur latéral gauche
    const leftWallA = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(0.25, 3.0, 2.5), matWhiteWall);
    leftWallA.position.set(-4.95, 2.1, -2.5);
    const leftWallB = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(0.25, 3.0, 2.5), matWhiteWall);
    leftWallB.position.set(-4.95, 2.1, 2.5);
    const leftWallTop = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(0.25, 0.8, 2.8), matWhiteWall);
    leftWallTop.position.set(-4.95, 3.2, 0);
    const leftWallBot = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(0.25, 0.9, 2.8), matWhiteWall);
    leftWallBot.position.set(-4.95, 1.05, 0);

    const leftWinFrame = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(0.28, 1.3, 2.7), matAzureFrame, true);
    leftWinFrame.position.set(-4.95, 2.15, 0);
    const leftWinGlass = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(0.08, 1.1, 2.5), matGlass, true);
    leftWinGlass.position.set(-4.95, 2.15, 0);

    // Façade avant droite
    const frontWallR = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(1.2, 3.0, 0.25), matWhiteWall);
    frontWallR.position.set(4.4, 2.1, 3.95);

    const frontLintel = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(9.0, 0.4, 0.25), matWhiteWall);
    frontLintel.position.set(-0.5, 3.4, 3.95);

    // A. PORTE D'ENTRÉE PIVOTANTE MONUMENTALE (BOIS NOBLE, BLEU & OR)
    const doorFrameGeo = new THREE.BoxGeometry(1.5, 2.8, 0.28);
    const doorFrame = addMeshWithEdges(rdcGroup, doorFrameGeo, matAzureFrame, true);
    doorFrame.position.set(3.0, 1.8, 3.95);

    doorPivot = new THREE.Group();
    doorPivot.position.set(2.35, 0.4, 3.95);
    rdcGroup.add(doorPivot);

    const doorLeafGeo = new THREE.BoxGeometry(1.3, 2.6, 0.08);
    const doorLeaf = addMeshWithEdges(doorPivot, doorLeafGeo, matBrownWood, true);
    doorLeaf.position.set(0.65, 1.4, 0);

    const handleGeo = new THREE.CylinderGeometry(0.025, 0.025, 1.2, 8);
    const handle = addMeshWithEdges(doorPivot, handleGeo, matGoldYellow, true);
    handle.position.set(1.2, 1.4, 0.07);

    // B. GRANDE BAIE VITRÉE COULISSANTE DU SALON
    const bayFrameGeo = new THREE.BoxGeometry(6.0, 2.8, 0.28);
    const bayFrame = addMeshWithEdges(rdcGroup, bayFrameGeo, matAzureFrame, true);
    bayFrame.position.set(-1.4, 1.8, 3.95);

    const fixedPanelGeo = new THREE.BoxGeometry(2.8, 2.6, 0.06);
    const fixedGlass = addMeshWithEdges(rdcGroup, fixedPanelGeo, matGlass, true);
    fixedGlass.position.set(-2.8, 1.8, 3.92);

    const fixedFrameOutline = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(2.85, 2.65, 0.08), matAzureFrame, true);
    fixedFrameOutline.position.set(-2.8, 1.8, 3.92);

    slidingGlassPanel = new THREE.Group();
    slidingGlassPanel.position.set(0, 0, 0);
    rdcGroup.add(slidingGlassPanel);

    const slidingPanelGeo = new THREE.BoxGeometry(2.8, 2.6, 0.06);
    const slidingGlass = addMeshWithEdges(slidingGlassPanel, slidingPanelGeo, matGlass, true);
    slidingGlass.position.set(-0.2, 1.8, 3.98);

    const slidingFrame = addMeshWithEdges(slidingGlassPanel, new THREE.BoxGeometry(2.85, 2.65, 0.08), matAzureFrame, true);
    slidingFrame.position.set(-0.2, 1.8, 3.98);

    const slidingGrip = addMeshWithEdges(slidingGlassPanel, new THREE.BoxGeometry(0.05, 0.7, 0.05), matGoldYellow, true);
    slidingGrip.position.set(-1.5, 1.8, 4.05);

    // Auvent architectural suspendu avec spots LED encastrés
    const porchGeo = new THREE.BoxGeometry(3.6, 0.3, 2.2);
    const porch = addMeshWithEdges(rdcGroup, porchGeo, matBrownPillar);
    porch.position.set(2.6, 3.6, 4.8);
    const porchTrim = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(3.65, 0.06, 2.25), matGoldYellow);
    porchTrim.position.set(2.6, 3.75, 4.8);

    // 2 Spots LED encastrés sous l'auvent
    [1.8, 3.4].forEach(xSpot => {
      const spotLightMesh = addMeshWithEdges(rdcGroup, new THREE.CylinderGeometry(0.12, 0.12, 0.05, 12), matLedGlow);
      spotLightMesh.position.set(xSpot, 3.42, 4.8);
    });

    // =========================================================================
    // 1.1 NOUVEAUTÉ PRESTIGE : AMÉNAGEMENT DU SALON CONTEMPORAIN (VISIBLE PAR TRANSPARENCE)
    // =========================================================================
    // Canapé d'angle design (Assise principale + méridienne)
    const sofaMain = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(2.4, 0.45, 0.9), matFabric);
    sofaMain.position.set(-2.2, 0.85, 1.6);
    const sofaBack = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(2.4, 0.6, 0.25), matFabric);
    sofaBack.position.set(-2.2, 1.25, 1.2);
    const sofaLounge = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(0.9, 0.45, 1.4), matFabric);
    sofaLounge.position.set(-3.3, 0.85, 2.2);

    // Table basse contemporaine Teck & Verre
    const coffeeTable = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(1.2, 0.28, 0.7), matBrownWood);
    coffeeTable.position.set(-2.0, 0.75, 2.6);

    // Îlot central de cuisine / Bar américain
    const kitchenIsland = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(2.2, 1.0, 0.8), matMarble);
    kitchenIsland.position.set(1.4, 1.1, -1.2);

    // =========================================================================
    // 1.2 NOUVEAUTÉ PRESTIGE : ESCALIER D'ARCHITECTE SUSPENDU
    // =========================================================================
    const stairLimon = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(0.15, 3.6, 0.3), matAzureFrame);
    stairLimon.rotation.z = -0.55;
    stairLimon.position.set(-4.0, 2.0, -1.8);

    for (let st = 0; st < 9; st++) {
      const step = addMeshWithEdges(rdcGroup, new THREE.BoxGeometry(1.0, 0.06, 0.32), matBrownWood);
      step.position.set(-4.2 + st * 0.28, 0.7 + st * 0.32, -1.8);
    }

    // =========================================================================
    // NIVEAU 2 : ÉTAGE R+1 (SUITE PARENTALE, CORNER WINDOW & BALCON PANORAMIQUE)
    // =========================================================================
    const floorSlabGeo = new THREE.BoxGeometry(10.8, 0.45, 8.8);
    const floorSlab = addMeshWithEdges(r1Group, floorSlabGeo, matConcrete);
    floorSlab.position.set(0, 3.8, 0);

    // Bandeau LED architectural chaud sous le porte-à-faux
    const ledgeLed = addMeshWithEdges(r1Group, new THREE.BoxGeometry(10.85, 0.06, 0.1), matLedGlow);
    ledgeLed.position.set(0, 3.55, 4.42);

    const r1MainGeo = new THREE.BoxGeometry(7.6, 2.8, 7.6);
    const r1Main = addMeshWithEdges(r1Group, r1MainGeo, matWhiteWall);
    r1Main.position.set(-1.2, 5.4, -0.2);

    // Porte-fenêtre coulissante d'accès au balcon
    const r1BalconyFrame = addMeshWithEdges(r1Group, new THREE.BoxGeometry(2.6, 2.4, 0.25), matAzureFrame, true);
    r1BalconyFrame.position.set(1.4, 5.3, 3.65);

    balconyDoor = new THREE.Group();
    balconyDoor.position.set(0, 0, 0);
    r1Group.add(balconyDoor);

    const r1BalconyDoorMesh = addMeshWithEdges(balconyDoor, new THREE.BoxGeometry(1.25, 2.3, 0.06), matGlass, true);
    r1BalconyDoorMesh.position.set(0.8, 5.3, 3.68);
    const r1BalconyDoorFrame = addMeshWithEdges(balconyDoor, new THREE.BoxGeometry(1.3, 2.35, 0.08), matAzureFrame, true);
    r1BalconyDoorFrame.position.set(0.8, 5.3, 3.68);

    // Corner window panoramique d'angle
    const cornerGlassSouth = addMeshWithEdges(r1Group, new THREE.BoxGeometry(2.8, 2.0, 0.06), matGlass, true);
    cornerGlassSouth.position.set(-3.4, 5.3, 3.62);
    const cornerFrameSouth = addMeshWithEdges(r1Group, new THREE.BoxGeometry(2.85, 2.05, 0.08), matAzureFrame, true);
    cornerFrameSouth.position.set(-3.4, 5.3, 3.62);

    const cornerGlassWest = addMeshWithEdges(r1Group, new THREE.BoxGeometry(0.06, 2.0, 2.4), matGlass, true);
    cornerGlassWest.position.set(-4.98, 5.3, 2.4);
    const cornerFrameWest = addMeshWithEdges(r1Group, new THREE.BoxGeometry(0.08, 2.05, 2.45), matAzureFrame, true);
    cornerFrameWest.position.set(-4.98, 5.3, 2.4);

    const cornerPost = addMeshWithEdges(r1Group, new THREE.BoxGeometry(0.12, 2.05, 0.12), matGoldYellow, true);
    cornerPost.position.set(-4.85, 5.3, 3.62);

    // Balcon en porte-à-faux avec garde-corps vitré sécurit
    const balconyGlassGeo = new THREE.BoxGeometry(3.6, 1.05, 0.08);
    const balconyGlass = addMeshWithEdges(r1Group, balconyGlassGeo, matGlass, true);
    balconyGlass.position.set(3.4, 4.5, 3.9);

    const railCapGeo = new THREE.BoxGeometry(3.65, 0.08, 0.1);
    const railCap = addMeshWithEdges(r1Group, railCapGeo, matGoldYellow, true);
    railCap.position.set(3.4, 5.05, 3.9);

    // Brise-soleil en bois Teck noble
    for (let l = 0; l < 5; l++) {
      const louvreGeo = new THREE.BoxGeometry(0.08, 2.4, 0.35);
      const louvre = addMeshWithEdges(r1Group, louvreGeo, matBrownWood, true);
      louvre.position.set(2.4 + l * 0.38, 5.3, 3.65);
    }

    // =========================================================================
    // 2.1 NOUVEAUTÉ PRESTIGE : JARDINIÈRE SUSPENDUE PAYSAGÈRE SUR LE BALCON
    // =========================================================================
    const planterBox = addMeshWithEdges(r1Group, new THREE.BoxGeometry(2.4, 0.35, 0.45), matMarble);
    planterBox.position.set(3.4, 4.2, 4.15);

    for (let f = 0; f < 5; f++) {
      const bush = addMeshWithEdges(r1Group, new THREE.DodecahedronGeometry(0.25, 1), matGreenery);
      bush.position.set(2.4 + f * 0.45, 4.5, 4.15);
    }

    // Lit king-size contemporain de la suite parentale R+1
    const bedBase = addMeshWithEdges(r1Group, new THREE.BoxGeometry(2.2, 0.4, 2.0), matBrownWood);
    bedBase.position.set(-1.8, 4.3, 0);
    const bedHead = addMeshWithEdges(r1Group, new THREE.BoxGeometry(2.4, 1.0, 0.15), matFabric);
    bedHead.position.set(-1.8, 4.8, -0.95);

    // =========================================================================
    // NIVEAU 3 : TOITURE TERRASSE, PERGOLA BIOCLIMATIQUE & PANNEAUX SOLAIRES
    // =========================================================================
    const roofSlabGeo = new THREE.BoxGeometry(11.2, 0.4, 9.2);
    const roofSlab = addMeshWithEdges(roofGroup, roofSlabGeo, matBrownPillar);
    roofSlab.position.set(0, 7.0, 0);

    // Acrotères Bleu Royal avec liseré supérieur Jaune Doré
    const acroGeoF = new THREE.BoxGeometry(11.2, 0.5, 0.25);
    addMeshWithEdges(roofGroup, acroGeoF, matNavyBlue).position.set(0, 7.35, 4.45);
    const acroGeoB = new THREE.BoxGeometry(11.2, 0.5, 0.25);
    addMeshWithEdges(roofGroup, acroGeoB, matNavyBlue).position.set(0, 7.35, -4.45);

    const acroTrimF = new THREE.BoxGeometry(11.25, 0.08, 0.28);
    addMeshWithEdges(roofGroup, acroTrimF, matGoldYellow).position.set(0, 7.62, 4.45);
    const acroTrimB = new THREE.BoxGeometry(11.25, 0.08, 0.28);
    addMeshWithEdges(roofGroup, acroTrimB, matGoldYellow).position.set(0, 7.62, -4.45);

    // Bandeau LED sous l'acrotère du toit
    const roofLedF = addMeshWithEdges(roofGroup, new THREE.BoxGeometry(11.25, 0.05, 0.08), matLedGlow);
    roofLedF.position.set(0, 7.15, 4.5);

    // Pergola bioclimatique en chêne teck
    for (let p = 0; p < 7; p++) {
      const beamGeo = new THREE.BoxGeometry(0.15, 0.2, 5.2);
      const beam = addMeshWithEdges(roofGroup, beamGeo, matPergola);
      beam.position.set(-3.0 + p * 0.8, 8.4, 0);
    }
    const pergolaPostGeo = new THREE.BoxGeometry(0.2, 1.4, 0.2);
    [ [-3.0, 7.7, -2.5], [-3.0, 7.7, 2.5], [1.8, 7.7, -2.5], [1.8, 7.7, 2.5] ].forEach(pos => {
      const post = addMeshWithEdges(roofGroup, pergolaPostGeo, matBrownPillar);
      post.position.set(pos[0], pos[1], pos[2]);
    });

    // 3 Panneaux Solaires Photovoltaïques
    for (let s = 0; s < 3; s++) {
      const solarGeo = new THREE.BoxGeometry(1.6, 0.08, 2.4);
      const solar = addMeshWithEdges(roofGroup, solarGeo, matAzureFrame);
      solar.rotation.x = -0.2;
      solar.position.set(3.2, 7.4, -2.0 + s * 2.2);

      const solarTrimGeo = new THREE.BoxGeometry(1.65, 0.03, 2.45);
      const solarTrim = addMeshWithEdges(roofGroup, solarTrimGeo, matGoldYellow);
      solarTrim.rotation.x = -0.2;
      solarTrim.position.set(3.2, 7.37, -2.0 + s * 2.2);
    }
  }

  function applyRenderMode(mode) {
    currentMode = mode;
    const isWire = mode === 'blueprint';
    const isConcrete = mode === 'concrete';

    modelGroup.traverse((child) => {
      if (child.isMesh && child.material) {
        if (child.userData.edgeLines) {
          child.userData.edgeLines.visible = isWire;
        }

        if (isWire) {
          child.material.transparent = true;
          child.material.opacity = child.userData.isGlass ? 0.15 : 0.22;
          child.material.color.setHex(0x0f172a);
          child.material.roughness = 0.9;
          child.material.metalness = 0.1;
        } else if (isConcrete) {
          if (child.userData.isGlass) {
            child.material.transparent = true;
            child.material.opacity = 0.25;
            child.material.color.setHex(0x38bdf8);
          } else {
            child.material.transparent = false;
            child.material.opacity = 1;
            child.material.color.setHex(child.userData.isOpening ? 0xf59e0b : 0x94a3b8);
            child.material.roughness = 0.9;
            child.material.metalness = 0.05;
          }
        } else {
          // Mode Prestige Réaliste
          child.material.transparent = child.userData.origTransparent;
          child.material.opacity = child.userData.origOpacity;
          child.material.color.setHex(child.userData.origColor);
          child.material.roughness = child.userData.origRoughness;
          child.material.metalness = child.userData.origMetalness;
        }
      }
    });

    if (scene) {
      if (isWire) {
        scene.background.set(0x020617);
        scene.fog.color.set(0x020617);
      } else if (isNightMode) {
        scene.background.set(0x030712);
        scene.fog.color.set(0x030712);
      } else {
        scene.background.set(0x0f172a);
        scene.fog.color.set(0x0f172a);
      }
    }
  }

  function toggleNightMode(night) {
    isNightMode = night;
    if (scene) {
      const bgColor = night ? 0x030712 : 0x0f172a;
      scene.background.set(bgColor);
      scene.fog.color.set(bgColor);
    }

    if (ambientLight) ambientLight.intensity = night ? 0.25 : 0.7;
    if (sunLight) {
      sunLight.intensity = night ? 0.22 : 1.4;
      sunLight.color.setHex(night ? 0x38bdf8 : 0xfff7ed);
    }
    if (fillLight) fillLight.intensity = night ? 0.15 : 0.45;
    if (interiorLight) {
      interiorLight.intensity = night ? 3.5 : 1.4;
      interiorLight.color.setHex(night ? 0xffea79 : 0xfbbf24);
    }
    if (poolLight) {
      poolLight.intensity = night ? 2.8 : 1.2;
    }

    // Intensifier la lueur des bandeaux LED
    ledMaterials.forEach(m => {
      m.emissiveIntensity = night ? 1.0 : 0.4;
    });
  }

  function setupInteractions() {
    // Bouton Mode Nuit VIP / Mode Solaire
    const btnNight = document.getElementById('feeBtnBimNight');
    if (btnNight) {
      btnNight.addEventListener('click', () => {
        isNightMode = !isNightMode;
        btnNight.classList.toggle('active', isNightMode);
        const icon = btnNight.querySelector('i');
        const span = btnNight.querySelector('span');
        if (icon) {
          icon.className = isNightMode ? 'fa fa-sun mr-1' : 'fa fa-moon mr-1';
        }
        if (span) {
          span.textContent = isNightMode ? 'Mode Solaire' : 'Mode Nuit';
        }
        toggleNightMode(isNightMode);
      });
    }

    // Bouton Ouvrir / Fermer les Ouvertures (Portes & Baies)
    const btnDoors = document.getElementById('feeBtnBimOpenDoors');
    const hudDoorLabel = document.getElementById('feeBimDoorLabel');

    if (btnDoors) {
      btnDoors.addEventListener('click', () => {
        areDoorsOpen = !areDoorsOpen;
        btnDoors.classList.toggle('active', areDoorsOpen);
        const icon = btnDoors.querySelector('i');
        const span = btnDoors.querySelector('span');
        if (icon) {
          icon.className = areDoorsOpen ? 'fa fa-door-closed mr-1' : 'fa fa-door-open mr-1';
        }
        if (span) {
          span.textContent = areDoorsOpen ? 'Fermer les Baies' : 'Ouvrir les Baies';
        }

        if (hudDoorLabel) {
          hudDoorLabel.textContent = areDoorsOpen ? 'Grandes Ouvertes (Pivot & Rails)' : 'Fermées';
          hudDoorLabel.style.color = areDoorsOpen ? '#22c55e' : '#ea580c';
        }

        if (interiorLight) {
          interiorLight.intensity = areDoorsOpen ? 2.8 : (isNightMode ? 3.5 : 1.4);
        }

        if (areDoorsOpen && modelGroup) {
          modelGroup.rotation.y = 0.15;
          autoRotate = false;
          const btnRotate = document.getElementById('feeBtnBimRotate');
          if (btnRotate) btnRotate.classList.remove('active');
        }
      });
    }

    // Bouton Écorché / Explode
    const btnExplode = document.getElementById('feeBtnBimExplode');
    if (btnExplode) {
      btnExplode.addEventListener('click', () => {
        isExploded = !isExploded;
        btnExplode.classList.toggle('active', isExploded);
        const icon = btnExplode.querySelector('i');
        if (icon) {
          icon.className = isExploded ? 'fa fa-compress mr-1' : 'fa fa-layer-group mr-1';
        }
      });
    }

    // Boutons de Modes de Rendu
    const modeBtns = document.querySelectorAll('[data-bim-mode]');
    modeBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        modeBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        applyRenderMode(btn.getAttribute('data-bim-mode'));
      });
    });

    // Bouton Rotation Auto
    const btnRotate = document.getElementById('feeBtnBimRotate');
    if (btnRotate) {
      btnRotate.addEventListener('click', () => {
        autoRotate = !autoRotate;
        btnRotate.classList.toggle('active', autoRotate);
      });
    }

    // Bouton Réinitialiser Caméra
    const btnReset = document.getElementById('feeBtnBimReset');
    if (btnReset) {
      btnReset.addEventListener('click', () => {
        const width = window.innerWidth;
        if (width <= 768) {
          camera.position.set(30, 24, 36);
        } else {
          camera.position.set(19, 14, 23);
        }
        if (controls) {
          controls.target.set(0, 3.2, 0);
          controls.update();
        }
      });
    }
  }

  function onWindowResize() {
    const container = document.getElementById('feeBimCanvasWrap');
    if (!container || !renderer || !camera) return;
    const width = container.clientWidth;
    const height = container.clientHeight;
    camera.aspect = width / height;
    if (width <= 768) {
      camera.position.set(30, 24, 36);
    } else {
      camera.position.set(19, 14, 23);
    }
    camera.updateProjectionMatrix();
    renderer.setSize(width, height);
  }

  function animate() {
    animationFrameId = requestAnimationFrame(animate);

    // 1. Interpolation douce de la vue éclatée
    const factor = isExploded ? 1 : 0;
    const speed = 0.08;

    foundationGroup.position.y += ((targetPositions.foundation.exploded * factor) - foundationGroup.position.y) * speed;
    rdcGroup.position.y += ((targetPositions.rdc.exploded * factor) - rdcGroup.position.y) * speed;
    r1Group.position.y += ((targetPositions.r1.exploded * factor) - r1Group.position.y) * speed;
    roofGroup.position.y += ((targetPositions.roof.exploded * factor) - roofGroup.position.y) * speed;

    // 2. Interpolation fluide des ouvertures
    const doorTargetAngle = areDoorsOpen ? -1.65 : 0;
    if (doorPivot) {
      doorPivot.rotation.y += (doorTargetAngle - doorPivot.rotation.y) * 0.12;
    }

    const slidingTargetX = areDoorsOpen ? -2.2 : 0;
    if (slidingGlassPanel) {
      slidingGlassPanel.position.x += (slidingTargetX - slidingGlassPanel.position.x) * 0.12;
    }

    const balconyTargetX = areDoorsOpen ? 0.9 : 0;
    if (balconyDoor) {
      balconyDoor.position.x += (balconyTargetX - balconyDoor.position.x) * 0.12;
    }

    // 3. Légère ondulation de surface de l'eau de la piscine
    if (poolWaterMesh) {
      poolWaterMesh.position.y = 0.43 + Math.sin(Date.now() * 0.0025) * 0.012;
    }

    // 4. Rotation continue fluide
    if (autoRotate && modelGroup) {
      modelGroup.rotation.y += 0.005;
    }

    // 5. Contrôles
    if (controls) {
      controls.update();
    }

    renderer.render(scene, camera);
  }

  // Initialisation au chargement du DOM
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initBIM);
  } else {
    initBIM();
  }
})();
