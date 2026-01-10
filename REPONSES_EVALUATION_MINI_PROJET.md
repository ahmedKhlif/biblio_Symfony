  # Réponses Évaluation Mini-Projet - Biblio Symfony

  **Étudiant:** Ahmed Khlif  
  **Classe:** GLNT  
  **Matière:** Framework web avancé  
  **Enseignant:** M. Faiez Charfi  
  **Projet:** Système de Gestion de Bibliothèque (Biblio)

  ---

  ## 1. CIN
  *[À remplir avec votre numéro de CIN]*

  ---

  ## 2. Nom et prénom
  **Ahmed Khlif**

  ---

  ## 3. Thème graphique

  **☑ J'ai utilisé un thème/template graphique et je l'ai adapté**

  **Frontend:** Bootstrap 4 avec personnalisations CSS + Font Awesome 5  
  **Dashboard Admin:** SB Admin 2 intégré et adapté pour gestion prêts/réservations  
  **Composants:** FullCalendar.js 6 (calendrier disponibilité), Turbo/Hotwire (navigation SPA)  
  **Adaptations:** Styles personnalisés pour stock double (V:X | E:Y), badges disponibilité, workflow visuel prêts

  ---

  ## 4. Dashboard

  **☑ J'ai effectué les deux**

  **1. EasyAdmin Bundle (`/admin`):**  
  CRUD automatique pour 18 entités (User, Livre, Loan, Order, etc.), filtres avancés, actions par lot, export données

  **2. Dashboard Personnalisé (`/backoffice`):**  
  SB Admin 2 avec graphiques, gestion workflow prêts (6 statuts), file d'attente réservations, statistiques avancées

  ---

  ## 5. FrontOffice - Fonctionnalités réalisées

  **🔍 Catalogue et Recherche Avancée:**
  Recherche full-text (titre, description) + filtres simultanés (auteur, catégorie, éditeur, disponibilité), tri dynamique (date/prix/titre), autocomplétion AJAX, pagination KnpPaginator 15 items/page, affichage stock double (V:X | E:Y), validation QueryBuilder avec eager loading relations

  **📚 Système de Prêt et Réservation:**
  Workflow complet 6 statuts (requested→approved→active→returned/overdue/cancelled), validation stock emprunt auto, file d'attente avec position calculée (MAX+1), notifications emails transitions, calendrier FullCalendar.js avec dates retour, EventSubscriber détection retours → notification position 1

  **🛒 Module E-Commerce Complet:**
  Panier persistant DB (Cart/CartItem), ajout/suppression/update quantités AJAX, validation stockVente temps réel, paiement **Stripe 3D Secure**, webhooks confirmation, génération commandes uniques, emails confirmation auto, historique commandes, décrémentation stock si paiement succès

  **⭐ Système d'Avis et Notes:**
  Reviews 1-5 étoiles avec commentaires, upload images (5 max), badge "Achat vérifié" pour achats confirmés, votes "Utile" par communauté, modération admin (approbation/rejet), calcul note moyenne AVG(rating), tri pertinence/date/note

  **📖 Suivi de Lecture Avancé:**
  Progression % + page courante enregistrée, système signets avec notes personnelles, statut complétion auto, historique lecture (lastReadAt), séries de lecture (streaks) détectées auto

  **🎯 Objectifs de Lecture:**
  Types: livres/an, pages/mois, personnalisé, calcul progression auto vers cible, dates début/fin configurables, notifications accomplissement, visualisation graphique progrès

  **👤 Gestion Profil Utilisateur:**
  Modification infos perso, upload avatar, adresses multiples (facturation/livraison), **wishlist avec notifications disponibilité**, livres possédés, auteurs favoris suivi, historique complet d'activité, préférences bannières mémorisées

  **💬 Messagerie Interne:**
  Communication inter-utilisateurs, statut lecture horodaté (lu/non lu), **compteur non lus AJAX temps réel**, affichage temps relatif français ("il y a 2h"), boîte réception/envoyés, recherche messages, suppression archivage

  **🎯 Bannières Promotionnelles:**
  4 types (promotion/announcement/warning/info), 4 positions (top/bottom/sidebar/popup), planification dates début/fin, **ciblage par rôle (USER/ADMIN)**, styles personnalisés CSS, option "fermer" avec mémorisation préférences utilisateur, ordre affichage configurable

  **🔐 Authentification et Sécurité:**
  Inscription validation multi-niveaux, confirmation email token sécurisé, connexion avec gestion sessions, récupération mot de passe email, protection CSRF tous formulaires, hashage bcrypt/argon2

  ---

  ## 6. Méthodes Repository utilisées et leurs classes

  **LivreRepository:**
  `findBySearchCriteria()` - Recherche avancée avec filtres multiples (auteur, catégorie, éditeur) + tri dynamique
  `findAvailableForLoan()` - Livres empruntables (stockEmprunt > 0)
  `findBestSellers()` - Top ventes via JOIN OrderItem + GROUP BY
  `findNewReleases()` - Derniers ajouts ORDER BY date DESC

  **LoanRepository:**
  `findActiveLoans()` - Prêts status IN ('approved', 'active')
  `findOverdueLoans()` - Retards (dueDate < now AND status='active')
  `findPendingRequests()` - Demandes en attente approbation
  `countActiveLoansByBook()` - COUNT pour calcul disponibilité

  **BookReservationRepository:**
  `findActiveReservationsByBook()` - File d'attente ORDER BY position
  `getNextPositionInQueue()` - MAX(position) + 1 pour nouvelle réservation
  `findByUserAndBook()` - Vérification doublon réservation

  **OrderRepository:**
  `findByUser()` - Commandes utilisateur avec leftJoin orderItems
  `getTotalRevenue()` - SUM(totalAmount) WHERE status='completed'
  `findRecentOrders()` - Dashboard admin ORDER BY createdAt DESC

  **OrderItemRepository:**
  `findByOrder()` - Articles d'une commande

  **CartRepository:**
  `findActiveCartByUser()` - Panier actif avec eager loading cartItems
  `clearExpiredCarts()` - DELETE paniers > 30 jours

  **CartItemRepository:**
  `findByCart()` - Articles du panier

  **ReviewRepository:**
  `findByBook()` - Avis par livre ORDER BY createdAt DESC
  `getAverageRating()` - AVG(rating) pour note moyenne

  **MessageRepository:**
  `findConversation()` - Messages entre deux utilisateurs
  `findUnreadCount()` - COUNT messages non lus

  **ReadingProgressRepository:**
  `findByUserAndBook()` - Progression utilisateur pour un livre
  `findMostRead()` - Livres les plus lus

  **ReadingGoalRepository:**
  `findActiveGoals()- Objectifs en cours (date_end > now)
  `findByUser()` - Objectifs d'un utilisateur

  **BannerRepository:**
  `findActiveForRole()` - Bannières actives pour un rôle
  `findByPosition()` - Bannières par position affichage

  **ActivityLogRepository:**
  `findByUser()` - Historique activité utilisateur
  `findRecent()` - Dernières activités dashboard

  **UserRepository:**
  `findByEmail()` - Authentification (implémente UserLoaderInterface)
  `upgradePassword()` - Update hash sécurisé (implémente PasswordUpgraderInterface)
  `findAdmins()` - Liste administrateurs

  **AutheurRepository, CategorieRepository, EditeurRepository:**
  CRUD simples: findAll(), findOne(), filtres basiques

  ---

  ## 7. Gestion de panier

  **☑ OUI**

  **Entités:** Cart (OneToMany CartItem), CartItem (ManyToOne Livre)
  **Persistance:** Sauvegarde en DB, association User, récupération auto à la connexion
  **Fonctionnalités:** Add/remove produits, update quantités AJAX, validation stockVente, calcul totaux auto
  **Conversion:** Panier → Order lors paiement Stripe, décrémentation stock, vidage panier
  ---

  ## 8. Méthodes personnalisées EasyAdmin (20 CRUD)

  **DashboardController:**
  `configureDashboard()` - Config titre/logo/favicon
  `configureMenuItems()` - Menu hiérarchique 4 sections (Bibliothèque, E-Commerce, Utilisateurs, Contenu) pour 18 entités

  **LivreCrudController:**
  `configureCrud()` - Pagination 15 items, tri date DESC
  `configureFields()` - ImageField upload couvertures, IntegerField stockVente/stockEmprunt séparés
  `configureFilters()` - Filtres auteur/catégorie/éditeur/empruntable
  `configureActions()` - Action custom "dupliquer livre"
  `createEntity()` - Initialisation livre vierge
  `persistEntity()` - Valorisation prix si vide

  **LoanCrudController:**
  `configureFields()` - ChoiceField status avec badges colorés (requested/approved/active/returned/overdue/cancelled), DateField dueDate
  `configureFilters()` - Filtres status/user/book/dates
  `persistEntity()` - Décrémentation stockEmprunt si status='approved', envoi email notification
  `updateEntity()` - Incrémentation stockEmprunt si status='returned', notification admin
  `configureActions()` - Action "Approver", "Reject", "Mark Returned"

  **BookReservationCrudController:**
  `configureFields()` - IntegerField position (disabled/readonly), ChoiceField status badges colorés
  `configureFilters()` - Filtres status/book/user/dates
  `persistEntity()` - Calcul auto position via getNextPositionInQueue() MAX+1, envoi email si position=1
  `configureActions()` - Action "Notify" si position=1, "Cancel"

  **OrderCrudController:**
  `configureFields()` - TextField orderNumber (readonly), AssociationField user, MoneyField EUR totalAmount, ChoiceField status
  `configureFilters()` - Filtres status/user/dates
  `configureActions()` - Disable NEW (création via checkout), add DETAIL modal avec OrderItems

  **OrderItemCrudController:**
  `configureFields()` - AssociationField order/livre, IntegerField quantity, MoneyField priceAtTime
  `onlyOnIndex()` - Lecture seule (créés automatiquement)

  **UserCrudController:**
  `configureFields()` - EmailField email, TextField plainPassword (onlyOnForms), ChoiceField roles multiple (ROLE_USER/ROLE_ADMIN), BooleanField isVerified
  `configureFilters()` - Filtres rôles/verified/createdAt
  `persistEntity()` - Hash auto mot de passe avec UserPasswordHasher si plainPassword rempli
  `updateEntity()` - Update password si plainPassword fourni

  **CartCrudController:**
  `configureFields()` - AssociationField user (readonly), MoneyField total (readonly), DateTimeField createdAt
  `onlyOnIndex()` - Lecture seule (gestion via interface utilisateur)

  **CartItemCrudController:**
  `configureFields()` - AssociationField cart/livre, IntegerField quantity, MoneyField priceAtTime
  `onlyOnIndex()` - Lecture seule

  **ReviewCrudController:**
  `configureFields()` - AssociationField book/user, IntegerField rating (1-5), TextEditorField comment, BooleanField verifiedPurchase, BooleanField approved
  `configureFilters()` - Filtres book/rating/verifiedPurchase/approved/createdAt
  `configureActions()` - Action "Approve" + "Reject" conditionnelles si !approved
  `updateEntity()` - Envoi email utilisateur si approbation change

  **AutheurCrudController, CategorieController, EditeurCrudController:**
  `configureFields()` - TextField nom, TextEditorField description
  `configureFilters()` - Recherche par nom
  CRUD standards: create/read/update/delete

  **MessageCrudController:**
  `configureFields()` - AssociationField sender/recipient (readonly), TextEditorField content, BooleanField isRead, DateTimeField createdAt
  `configureFilters()` - Filtres sender/recipient/isRead
  `onlyOnIndex()` - Lecture seule (messages gérés en interface)

  **ReadingProgressCrudController:**
  `configureFields()` - AssociationField user/livre (readonly), IntegerField currentPage, IntegerField percentage, BooleanField completed
  `configureFilters()` - Filtres user/livre/completed

  **ReadingGoalCrudController:**
  `configureFields()` - AssociationField user, ChoiceField type (books_per_year/pages_per_month/custom), IntegerField targetValue, DateField startDate/endDate
  `configureFilters()` - Filtres user/type/active

  **BannerCrudController:**
  `configureFields()` - TextField title, TextEditorField content, ChoiceField type/position, DateField startDate/endDate, ChoiceField targetRole, TextField cssClass
  `configureFilters()` - Filtres type/position/active
  `configureActions()` - Action "Preview"

  **ActivityLogCrudController:**
  `configureFields()` - AssociationField user (readonly), ChoiceField action (readonly), TextField description, DateTimeField createdAt
  `onlyOnIndex()` - Readonly complet (journal d'audit)

  ---

  ## 9. Rôles utilisateurs configurés

  **ROLE_USER:** Catalogue, recherche, emprunt, réservation, achat, panier, commandes, lecture (progression/objectifs), avis, messagerie, profil

  **ROLE_MODERATOR:** Permissions USER + accès /backoffice + modération avis (approve/reject reviews), gestion bannières, historique modération, rapports utilisateurs, suspensions temporaires (hérité de ROLE_USER)

  **ROLE_ADMIN:** Toutes permissions MODERATOR + accès /admin (EasyAdmin) + CRUD 18 entités + approbation prêts + gestion réservations + stats avancées + journaux activité + gestion utilisateurs + exports données

  **Configuration security.yaml:**
  ```yaml
  role_hierarchy:
      ROLE_MODERATOR: ROLE_USER
      ROLE_ADMIN: [ROLE_MODERATOR, ROLE_USER]
  access_control:
      - { path: ^/admin, roles: ROLE_ADMIN }
      - { path: ^/backoffice, roles: [ROLE_USER, ROLE_MODERATOR, ROLE_ADMIN] }
      - { path: ^/profile, roles: ROLE_USER }
  ```

  **Hiérarchie:** ROLE_USER → ROLE_MODERATOR → ROLE_ADMIN (chaque rôle hérite des permissions du précédent)

  ---

  ## 10. Héritage de rôles

  **☑ OUI**

  ```yaml
  security:
      role_hierarchy:
          ROLE_ADMIN: ROLE_USER
  ```

  Admins héritent auto toutes permissions USER. Un seul rôle à attribuer, admins peuvent tester UX utilisateur, hiérarchie claire centralisée.

  ---

  ## 11. API-Platform Framework

  **☐ NON**

  Architecture MVC classique avec Twig templates. Interactions dynamiques via AJAX/Turbo/Hotwire. Pas d'API REST exposée (évolution future possible pour app mobile).

  ---

  ## 12. Déploiement sur internet

  **☐ NON**

  Projet hébergé sur GitHub uniquement, environnement dev local Docker. Prêt production: .env configuré, assets Webpack Encore, 25 migrations, sécurité HTTPS. Déploiement futur possible: Symfony Cloud, Heroku, DigitalOcean, AWS, VPS.

  ---

  ## 13. Lien public GitHub/GitLab

  https://github.com/ahmedKhlif/biblio_Symfony

  **Contenu:** 18 entités, 35+ contrôleurs, 25 migrations, 7 services, README complet, compte rendu LaTeX, 15 diagrammes Mermaid, Docker Compose

  ---

  ## 14. Difficultés rencontrées

  **1. Stock double:**
  Séparer vente/emprunt sans conflits → 2 champs (stockVente, stockEmprunt), migration FLOOR/CEIL répartition

  **2. Workflow prêt:**
  Machine à états 6 statuts (requested→approved→active→returned/overdue/cancelled) → Méthodes transition, EventSubscriber notifications, commande console détection retards

  **3. File d'attente réservations:**
  Attribution position auto, notifications → Repository getNextPositionInQueue() MAX+1, EventSubscriber détection retours

  **4. Intégration Stripe:**
  Webhooks, 3D Secure, erreurs paiement → Service StripePaymentService, gestion exceptions, workflow sécurisé, décrémentation stock seulement si succès

  **5. Turbo/Hotwire + FullCalendar:**
  Réinitialisation JS après navigation → EventListeners turbo:load/before-render, destroy calendar avant navigation

  **6. Performance N+1:**
  Requêtes multiples relations → Eager loading leftJoin + addSelect, indexation colonnes, < 200ms liste paginée

  **7. Migration données:**
  nbExemplaires → stockVente+stockEmprunt → Migration 2 étapes: ajout colonnes puis UPDATE FLOOR/CEIL

  **8. Filtrage avancé:**
  Recherche + filtres + tri dynamique → QueryBuilder conditionnel avec andWhere(), paramètres bindés, indexation

  **9. Uploads fichiers:**
  Validation, sécurité, noms uniques → VichUploaderBundle, contraintes Assert\File, UniqueFilenameNamer, EventListener suppression

  **10. Double admin cohérence:**
  EasyAdmin vs Dashboard custom → Services métier partagés, Doctrine Events synchronisation, Repository communs

  ---

  **Points forts:** 18 entités, double admin, filtrage multi-critères, workflow 6 statuts, file d'attente auto, stock double, e-commerce Stripe, 25 migrations, 7 services, Repository QueryBuilder, FullCalendar, AJAX temps réel, hiérarchie rôles, optimisation N+1

  ---

  **Document prêt pour copier-coller dans formulaire Google Forms** 📋
