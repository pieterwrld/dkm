# Prompt voor Claude Code — De Klokjesman thema

Plak onderstaand blok als eerste bericht in je Claude Code sessie, in de map van het thema.
Zet `reference/` (index.html, assets/, DESIGN-SPEC.md, product-attributes.csv) in de root van je repo.

---

## HET BERICHT

Je werkt verder aan het custom WordPress/WooCommerce thema voor **De Klokjesman** (deklokjesman.com),
een Nederlandse webshop in luxe horloges in drie kwaliteitsniveaus: Superclone, BP Swiss Made en AAA+.

In `reference/` staat een werkend design-prototype van de nieuwe site. Begin daarmee:

1. Open `reference/index.html` in de browser en klik alle vier de pagina's door
   (home, collectie, product, kwaliteitsinformatie), plus het megamenu, de cart-drawer
   en de popups op de infopagina. Bekijk ook mobiel op 390px.
2. Lees `reference/DESIGN-SPEC.md`. Daar staan de design tokens, de componenten
   en het gedrag per sectie.
3. Lees `reference/product-attributes.csv`. Daar staat voor alle 204 producten
   welk model, welke maat en welke wijzerplaat uit de titel te halen is.

**Het prototype is de visuele en functionele referentie, niet de code die je overneemt.**
Het is één HTML-bestand met een hardcoded JSON-catalogus. In het thema bouw je alles
op de echte WooCommerce-data. Neem de vormgeving, de interacties en de teksten over,
niet de dataschicht.

### Wat eerst moet gebeuren: de datalaag klopt niet

Ik heb de WooCommerce Store API nagekeken. De producten zijn allemaal `simple`,
hebben **geen attributen, geen SKU en geen varianten**. Model en kastmaat staan
alleen in de producttitel. Daarom kan er nu niet fatsoenlijk gefilterd worden.

Doe dit als eerste, vóór het frontend-werk:

- Maak globale productattributen aan: `pa_model`, `pa_maat`, `pa_wijzerplaat`.
- Backfill ze met `reference/product-attributes.csv` via WP-CLI. 9 horloges hebben
  geen model kunnen matchen; zet die apart in een lijst zodat de klant ze handmatig
  kan aanvullen. Draai de backfill idempotent, zodat hij veilig opnieuw kan.
- Laat de kwaliteitstier uit de bestaande `product_cat` komen
  (`superclone`, `bp-swiss-made`, `aaa`). Maak daar geen nieuwe taxonomie voor.
- Zet een SKU-schema op, ook al is het maar `DKM-{id}`.

### Wat je bouwt

Bouw per sectie een template part of block, met de klassenamen en tokens uit het prototype.
Werk de secties in deze volgorde af en laat na elke sectie zien wat je hebt:

**Globaal**
- Design tokens uit DESIGN-SPEC.md in één `tokens.css`, geen hardcoded kleuren verderop.
- De champagne-gradient is geanimeerd en beweegt continu. Hij zit op de primaire knop,
  actieve filterchips, badges, getallen en de cart-knop.
- Black Friday is **rood** (`--red:#D81E27`), niet goud. Bar met live countdown tot
  middernacht, rode kortingsbadges op saleproducten, eigen salebanner.
- Eigen SVG-iconenset (30 iconen staan in het prototype in het `I`-object). Geen iconfont,
  geen externe iconenbibliotheek.
- Logo: de O in KLOKJESMAN is een SVG-klok met kroon en draaiende wijzers. Staat in het
  prototype als constante `LOGO`. Lever hem als `inc/logo.php` zodat hij overal herbruikbaar is.

**Header**
- Zwevende pil met glas-effect, groter logo dan nu live staat.
- Megamenu op "Collectie" met full-bleed paneel en thumbnails per categorie.
  Thumbnails uit de categorie-afbeelding van WooCommerce, niet hardcoded.
- Mobiel: off-canvas drawer. Let op: geef off-canvas panelen `visibility:hidden`
  als ze dicht zijn, anders rekken ze de pagina op (dat probleem zat in het prototype).

**Home**
- Full-bleed hero met Ken Burns, gouden lichtveeg en bewegend grid.
  De hero-afbeelding moet een **brede** render zijn, geen vierkante productfoto.
  Zet in het thema een ACF-veld voor de hero-afbeelding zodat de klant hem zelf kan wisselen.
- USP-marquee onder de hero, doorlopend.
- Kwaliteitenrijen: Superclone, BP en AAA+ staan elk op **één lijn**, ook op mobiel.
  Kort blok met foto plus los lang blok, hele rij klikbaar.
- "Welke kwaliteit kies jij?" als carousel met snap, pijlen en dots, op desktop én mobiel.
  BP staat eerst en is uitgelicht. Vinkjes groen, minnetjes oranje, kruisjes rood,
  met een i-tje direct achter de tekst dat op klik een tooltip opent.
- Bestsellers met werkende filters op kwaliteit én model, met live tellers.
- UGC-carousel waarin de kaarten openklappen (zie parfum4car.nl). Donkere achtergrond
  met bewegende lijnen en een groot transparant horloge met Rolex-kroontje dat ronddraait.
  **Hier moeten echte video's in.** Maak een ACF repeater of een custom post type `ugc`
  met video, poster en caption. De klant levert TikTok- en Reels-bestanden aan.
- Reviews in Outfitman-stijl: eigen kaartheader met avatar, "Online" en WhatsApp-icoon
  over de originele WhatsApp-header heen, scrollbaar gesprek in de kaart, sterren en
  "Geverifieerd" eronder. Maak dit een CPT `review` met afbeelding, zodat de klant
  screenshots kan blijven toevoegen. Snijd de WhatsApp-chrome er server-side af
  (circa 11% bovenaan, 6% onderaan) in plaats van met een CSS-offset.
- Interactieve sectie "Waar je een superclone aan herkent" met hotspots op een foto.
  In het prototype staan de hotspot-posities hardcoded in procenten. Maak dat in het thema
  instelbaar per afbeelding, anders klopt het niet meer als de foto wisselt.

**Collectie**
- Drie kwaliteitskaarten op één lijn bovenaan, daaronder pills voor Dames, Dozen,
  Brillen, 2e hands en Sale.
- Tussen filter en producten een banner **per collectie** met eigen foto, eigen kop
  en vier eigen USP's. Geen algemene koopwijzer. Maak de banners beheerbaar per
  `product_cat` via ACF.
- Filters: **Modelnaam, Millimeter, Prijs**, met live tellers die meerekenen met de
  al gekozen filters. Bouw dit als echte facetten op `pa_model` en `pa_maat`,
  met AJAX via de Store API (`/wp-json/wc/store/v1/products`) of een eigen endpoint.
  Geen plugin zoals FacetWP, tenzij je kunt onderbouwen waarom dat beter is.
- Sorteren en "Meer laden" met echte paginering, geen client-side slice.

**Productpagina**
- Drie templates, één per tier. Elke tier heeft eigen accentkleur, eigen icoon,
  eigen claim, eigen specificaties en eigen uitleg. De teksten staan in DESIGN-SPEC.md
  en komen van de bestaande informatiepagina.
- Geen btw-regel, geen "gratis verzending" regel, geen payment icons.
- Doos-upsell: alleen het horloge, met standaard Rolex-doos (+€65) of met premium
  Rolex-doos (+€130). Klikbaar, met een knop die een popup opent met wat er in die doos zit.
  Bouw dit als echte WooCommerce cart item meta of een add-on, zodat de doos ook
  daadwerkelijk in de bestelling belandt. Prijs op de knop telt live mee.
- Sectie "Wat houdt deze kwaliteit in?" met de echte tekst per tier.
- Carousel "Zo leveren wij jouw horloge" tussen de accordeons.
- Geen "maak je aankoop compleet" cross-sell meer. Gerelateerde producten blijven.

**Kwaliteitsinformatie-pagina**
- Losse pagina. "Welke kwaliteit kies jij?" met drie klikbare kaarten die een popup
  openen met volledige uitleg en specificaties, daaronder een vergelijkingstabel met
  groen, oranje en rood, en de premium features.

**Cart**
- Drawer die openschuift. Zit er nog geen doos in het mandje, dan worden beide dozen
  aangeboden met een plusknop. Dit moet echte WooCommerce cart-logica zijn.

### Harde eisen

- Mobile first. Test op 390px en controleer met een script dat `scrollWidth` gelijk is
  aan de viewportbreedte op élke template. Horizontale overflow is een bug, geen detail.
- Geen nieuwe build-dependencies zonder dat je het eerst voorlegt. Geen jQuery-afhankelijke
  nieuwe code.
- Afbeeldingen via `wp_get_attachment_image` met srcset en `loading="lazy"`.
  De afbeeldingen in `reference/assets/` zijn verkleinde kopieën puur voor het prototype,
  gebruik ze niet in het thema.
- Alle teksten in het Nederlands. Alle strings door `__()` heen met het theme-textdomain.
- Geen em-dashes in content of code-comments.
- Respecteer `prefers-reduced-motion`: zet de Ken Burns, de marquee en de gradient-animatie
  dan uit.
- Toetsenbordbediening en focus states op megamenu, carousels, popups en de cart-drawer.

### Werkwijze

- Maak eerst een plan en leg dat voor voordat je begint te bouwen.
- Werk in kleine commits per sectie.
- Na elke sectie: screenshot op 1440px en op 390px, en benoem wat er nog niet klopt.
- Loop niet vooruit op secties die ik nog niet heb goedgekeurd.
- Als iets in het prototype niet werkt of slordig is opgelost, zeg dat en stel iets beters voor
  in plaats van het over te nemen.

### Wat in het prototype nog niet af is

Neem deze punten niet klakkeloos over, ze moeten in het thema beter:

- UGC-carousel draait op reviewscreenshots als poster, er zitten geen echte video's in.
- De cart, de zoekfunctie en het afrekenen zijn demo's zonder werking.
- Er zitten 114 van de 204 producten in, als selectie.
- "2e hands" is leeg, die categorie heeft één product op de live site.
- De hotspot-posities van de anatomie-sectie zijn geschat, niet gekoppeld aan de foto.
- De drie producttemplates verschillen nu in tekst en kleur, niet in layout.
  Kijk of een echt andere opbouw per tier beter werkt.
- Geen toegankelijkheidscontrole gedaan.
- De reviewkaarten snijden de WhatsApp-header met CSS weg, dat hoort server-side.

Begin met het plan.
