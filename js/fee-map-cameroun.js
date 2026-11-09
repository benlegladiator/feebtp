/**
 * FEE BTP SARL - Carte Interactive des Chantiers & Réalisations au Cameroun
 * Moteur Leaflet.js haute fluidité avec tuiles OpenStreetMap & filtres géographiques
 */

(function() {
  'use strict';

  let map;
  let markersLayer;

  const projectsGeo = [
    {
      id: 1,
      title: "Siège Social & Bureau d'Études FEE BTP",
      city: "Dschang",
      type: "Direction & Ingénierie",
      status: "Actif 24/7",
      lat: 5.4435,
      lng: 10.0532,
      surface: "Direction Générale",
      desc: "Siège administratif, bureau d'études 2D/3D et parc matériel.",
      image: "images/IMG-20261015-WA0120.jpg",
      link: "contact.php"
    },
    {
      id: 2,
      title: "Villa Duplex Contemporaine R+1",
      city: "Dschang",
      type: "Bâtiment Haut Standing",
      status: "En cours (74%)",
      lat: 5.4320,
      lng: 10.0680,
      surface: "380 m² couverte",
      desc: "Fondations en semelles isolées, poteaux béton armé et mur de soutènement.",
      image: "images/IMG-20261015-WA0079.jpg",
      link: "project.php"
    },
    {
      id: 3,
      title: "Stabilisation & Mur de Soutènement Banché",
      city: "Dschang",
      type: "Génie Civil & Pente",
      status: "Livré & Garanti",
      lat: 5.4510,
      lng: 10.0415,
      surface: "Longueur 42 ml • H 4.5m",
      desc: "Calculs de poussée des terres et drainage avec barbacanes anti-glissement.",
      image: "images/IMG-20261015-WA0073.jpg",
      link: "project.php"
    },
    {
      id: 4,
      title: "Immeuble Mixte Résidentiel & Commercial R+3",
      city: "Bafoussam",
      type: "Immeuble & Commerces",
      status: "Livré avec Succès",
      lat: 5.4778,
      lng: 10.4176,
      surface: "920 m² sur 4 niveaux",
      desc: "Structure béton banché, dalle nervurée et finitions complètes clés en main.",
      image: "images/IMG-20261015-WA0129.jpg",
      link: "project.php"
    },
    {
      id: 5,
      title: "Terrassement & Plateforme d'Entrepôt",
      city: "Mbouda",
      type: "Terrassement & VRD",
      status: "Livré",
      lat: 5.6289,
      lng: 10.2568,
      surface: "2 400 m² viabilisés",
      desc: "Décapage, nivellement laser et compactage lourd par nos propres engins.",
      image: "images/IMG-20261029-WA0105.jpg",
      link: "project.php"
    },
    {
      id: 6,
      title: "Rénovation & Aménagement d'Espace Standing",
      city: "Douala",
      type: "Rénovation & Second Œuvre",
      status: "Terminé",
      lat: 4.0280,
      lng: 9.6980,
      surface: "450 m²",
      desc: "Modernisation des corps d'état secondaires, plomberie et électricité aux normes.",
      image: "images/IMG-20261015-WA0062.jpg",
      link: "project.php"
    },
    {
      id: 7,
      title: "Étude d'Ingénierie & Villa Urbaine",
      city: "Yaoundé",
      type: "Études & Suivi de Chantier",
      status: "Phase Exécution",
      lat: 3.8920,
      lng: 11.5120,
      surface: "520 m²",
      desc: "Conduite rigoureuse des travaux pour maître d'ouvrage résidentiel.",
      image: "images/IMG-20261015-WA0070.jpg",
      link: "project.php"
    }
  ];

  function initMap() {
    const mapEl = document.getElementById('feeCamerounMap');
    if (!mapEl) return;

    if (typeof L === 'undefined') {
      console.warn('Leaflet non chargé.');
      return;
    }

    // Coordonnées centrées sur Dschang / Région Ouest
    const centerCoords = [5.45, 10.15];
    map = L.map('feeCamerounMap', {
      center: centerCoords,
      zoom: 8,
      scrollWheelZoom: false,
      zoomControl: true
    });

    // Fond de carte OpenStreetMap haute lisibilité
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 18,
      attribution: '&copy; OpenStreetMap &bull; <strong>FEE BTP SARL</strong>'
    }).addTo(map);

    markersLayer = L.layerGroup().addTo(map);

    populateMarkers(projectsGeo);
    setupFilters();

    // Redimensionnement fluide lors des rotations d'écrans mobiles
    window.addEventListener('resize', () => {
      if (map) {
        map.invalidateSize();
      }
    });
  }

  function createCustomIcon(proj) {
    const isHQ = proj.city === 'Dschang' && proj.type.includes('Direction');
    return L.divIcon({
      className: 'fee-map-marker-container',
      html: `
        <div class="fee-map-custom-marker ${isHQ ? 'hq-marker' : ''}" style="${isHQ ? 'background: linear-gradient(135deg, #ea580c, #7c2d12);' : ''}">
          <i class="fa ${isHQ ? 'fa-building' : 'fa-hard-hat'}"></i>
        </div>
      `,
      iconSize: [36, 36],
      iconAnchor: [18, 18],
      popupAnchor: [0, -22]
    });
  }

  function createPopupContent(proj) {
    return `
      <div class="fee-map-popup-card">
        <div class="fee-map-popup-img" style="background-image: url('${proj.image}');">
          <span class="fee-map-popup-badge">${proj.type}</span>
        </div>
        <div class="fee-map-popup-body">
          <h6 class="fee-map-popup-title">${proj.title}</h6>
          <div class="fee-map-popup-meta">
            <i class="fa fa-map-marker text-danger mr-1"></i> ${proj.city} (Cameroun)
            &bull; <strong style="color: #ea580c;">${proj.status}</strong>
          </div>
          <p style="font-size: 11.5px; color: #475569; margin-bottom: 8px; line-height: 1.4;">${proj.desc}</p>
          <div style="background: #f8fafc; padding: 6px 10px; border-radius: 6px; font-size: 11px; margin-bottom: 10px;">
            <strong>Envergure :</strong> ${proj.surface}
          </div>
          <a href="${proj.link}" class="btn btn-sm btn-primary btn-block text-white font-weight-bold" style="border-radius: 50px; font-size: 11px;">
            Consulter ce projet BTP &rarr;
          </a>
        </div>
      </div>
    `;
  }

  function populateMarkers(list) {
    markersLayer.clearLayers();
    const bounds = [];

    list.forEach(proj => {
      const marker = L.marker([proj.lat, proj.lng], {
        icon: createCustomIcon(proj)
      });
      marker.bindPopup(createPopupContent(proj), { maxWidth: 320 });
      markersLayer.addLayer(marker);
      bounds.push([proj.lat, proj.lng]);
    });

    if (bounds.length > 0 && map) {
      map.fitBounds(bounds, { padding: [40, 40], maxZoom: 12 });
    }
  }

  function setupFilters() {
    const filterBtns = document.querySelectorAll('.fee-map-filter-btn');
    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const city = btn.getAttribute('data-city');
        if (!city || city === 'all') {
          populateMarkers(projectsGeo);
        } else if (city === 'national') {
          const filtered = projectsGeo.filter(p => p.city === 'Douala' || p.city === 'Yaoundé');
          populateMarkers(filtered);
        } else {
          const filtered = projectsGeo.filter(p => p.city.toLowerCase() === city.toLowerCase());
          populateMarkers(filtered);
        }
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMap);
  } else {
    initMap();
  }
})();
