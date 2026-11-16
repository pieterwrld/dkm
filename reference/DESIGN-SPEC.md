# De Klokjesman — design spec

Referentie-implementatie: `index.html` in deze map. Alles hieronder is daaruit af te lezen,
dit document is de samenvatting zodat je niet door de CSS hoeft te spitten.

## 1. Design tokens

```css
--bg:#fff;        --ink:#0D0D0E;   --ink2:#67655F;  --ink3:#9B978F;
--line:#E9E6E0;   --line2:#D8D4CB; --surf:#F8F6F3;  --surf2:#F1EEE8;

/* champagne goud */
--ch1:#A8864A;  --ch2:#C9AA70;  --ch3:#EFE0BC;  --ch4:#8A6C35;  --chbg:#FCF8F0;
--grad:linear-gradient(100deg,#A8864A 0%,#C9AA70 22%,#F2E4C3 42%,#C9AA70 62%,#A8864A 100%);

/* Black Friday: rood, niet goud */
--red:#D81E27;  --red2:#B0141C;  --redbg:#FFF2F2;

/* status */
--green:#15A34A;  --orange:#E2902B;  --wa:#25D366;

--f:'Archivo',-apple-system,Segoe UI,sans-serif;
--e:cubic-bezier(.4,0,.2,1);  --es:cubic-bezier(.34,1.3,.64,1);
--pad:clamp(14px,3.6vw,40px);
```

De gradient is **altijd in beweging**:

```css
.gr{background:var(--grad);background-size:260% 100%;animation:gmove 7s linear infinite}
@keyframes gmove{to{background-position:260% 0}}
```

Toepassen op: primaire knop, actieve filterchip, cart-teller, "Meest gekozen" lint,
statistiekgetallen, carousel-dot actief, accent op de gekozen doos, cart-plusknop.
`.gr-txt` is dezelfde animatie als tekstvulling.

## 2. Typografie

Archivo, gewichten 300 tot 900, plus italic. Eén font voor alles, ook mobiel.

| Rol | Waarde |
|---|---|
| H1 hero | `clamp(2.3rem,5.6vw,4.6rem)`, 700, `letter-spacing:-.03em` |
| H2 sectie | `clamp(1.7rem,3.6vw,2.9rem)`, 700 |
| H3 | `clamp(1.15rem,2vw,1.6rem)`, 700 |
| Eyebrow | 10.5px, 800, `letter-spacing:.2em`, uppercase, `--ch4` |
| Body | 15px, `line-height:1.55` |
| Lead | `clamp(13.5px,1.25vw,16px)`, `--ink2` |

Accentwoord in koppen altijd `<span class="em gr-txt">`, dus italic 300 in bewegend goud.

## 3. Kwaliteitstiers

| | Superclone | BP Swiss Made | AAA+ |
|---|---|---|---|
| Kleur | `#A8864A` | `#4D6A8A` | `#7B5EA8` |
| Achtergrond | `#FCF7EC` | `#F2F6FA` | `#F7F4FC` |
| Vanaf | €900 | €370 | €150 |
| Subtitel | Niet van echt te onderscheiden | De slimste keuze | Betaalbaar instapmodel |
| Icoon | crown | swiss | spark |
| Glas | Saffierglas | Saffierglas | Krasbestendig mineraalglas |
| Uurwerk | Zwitsers clone, 28.800 vph | Zwitsers of ETA automatisch | Basis automatisch |
| Staal | 904L Oystersteel | 316L roestvrij staal | Roestvrij staal |
| Waterdicht | 50m tot 100m | 30m tot 50m | 30m |

Bullets per tier, letterlijk van de bestaande informatiepagina:

**Superclone**
- 1:1 replica met premium materialen (904L staal, saffierglas)
- Zwitsers of hoogwaardig clone-uurwerk (28.800 vph, vloeiende secondewijzer)
- Perfecte gravures, logo's en gewicht zoals origineel
- Werkende complicaties (GMT, chronograaf, datum)
- Goede waterdichtheid (meestal 50m tot 100m)

**BP Swiss Made**
- Mooie afwerking met roestvrij staal (316L) en saffierglas
- Betrouwbaar Zwitsers of Aziatisch ETA-uurwerk
- Nauwkeurige gravures en logo's
- Werkende complicaties en soepele secondewijzer
- Betaalbaarder dan een superclone

**AAA+**
- Mooie uitstraling voor een lage prijs
- Basis automatisch uurwerk
- Goede imitaties van bekende merken
- Lichtgewicht en comfortabel
- Perfect voor dagelijks gebruik

Premium features op elk horloge: glow in the dark wijzers, waterdicht tegen regen en spatten,
gecontroleerd voor verzending.

## 4. Vergelijkingsmatrix

`y` groen vinkje, `p` oranje minnetje, `n` rood kruisje. Elk kenmerk heeft een i-tje
met uitleg die op klik opent.

| Kenmerk | Super | BP | AAA+ |
|---|---|---|---|
| Automatisch uurwerk | y | y | y |
| Zwitsers of ETA-kaliber | y | y | n |
| Saffierglas | y | y | n |
| 904L Oystersteel | y | p | n |
| Correct gewicht en balans | y | p | n |
| Perfecte gravures en logo's | y | p | p |
| Werkende complicaties | y | y | p |
| Waterdicht | y | y | p |
| Glow in the dark | y | y | y |
| Niet van echt te onderscheiden | y | p | n |

## 5. Componenten en gedrag

| Component | Gedrag |
|---|---|
| BF-bar | Rode bewegende gradient, knipperende stip, countdown tot middernacht |
| Ticker | Marquee, pauzeert op hover |
| Header | Pil, glas, `backdrop-filter`, krimpt bij scroll |
| Megamenu | Full-bleed paneel met 5 thumbnails, scrim erachter, Escape sluit |
| Hero | Ken Burns 30s, gouden lichtveeg 9s, grid-drift 18s, scroll-indicator |
| USP-marquee | Donkere balk, 4 USP's met eigen iconen, doorlopend |
| Kwaliteitsrij | Kort blok plus lang blok op één lijn, hele rij klikbaar, pijl schuift op hover |
| Kies-carousel | `scroll-snap`, pijlen, dots, BP eerst en uitgelicht |
| Productkaart | Hover: lift, gouden rand, foto zoomt 1.06 |
| Filterchip | Actief = bewegende gradient. Teller rekent mee met andere actieve filters |
| Collectiebanner | Eigen foto, kop en 4 USP's per categorie |
| UGC-carousel | Kaarten klappen open in 3D, `rotateY`, schaal en helderheid per afstand |
| Reviewkaart | Eigen header, scrollbaar gesprek, sterren plus Geverifieerd |
| Anatomie | Hotspots met pulse, gekoppeld aan accordeonlijst ernaast |
| Doos-upsell | Radio's, hoverknop "Bekijk deze doos", popup, totaal telt live mee |
| Cart-drawer | Schuift in, biedt dozen aan als die nog niet in het mandje zitten |
| Footer | CTA-blok, 4 USP's, 4 kolommen, socials, gloed en lijnen op donker |

## 6. Iconen

30 eigen SVG-iconen in het `I`-object in `index.html`, viewBox 24, `stroke-width:1.7`,
ronde uiteinden. Namen: truck, shield, chat, box, gem, watch, swiss, drop, glow, weight,
engrave, crown, star, check, minus, close, info, search, cart, arrow, left, play, lock,
refresh, spark, filter, clock, tag, eye, wa, snap, mail.

## 7. Breakpoints

| Breedte | Wat verandert |
|---|---|
| 1100 | Productgrid naar 3 koloms, kwaliteitsrij toont minder feature-pills |
| 1080 | Nav verdwijnt, hamburger en off-canvas verschijnen, megamenu uit |
| 900 | Productpagina en anatomie naar één kolom |
| 860 | Feature-pills in de kwaliteitsrij verdwijnen |
| 760 | Grids naar 2 koloms, hero korter |
| 560 | Kwaliteitsrij compacter, "Bekijk" verdwijnt, kop klemt op 2 regels |

Let op: `html,body{overflow-x:clip}` en off-canvas panelen `visibility:hidden` als ze dicht zijn.
Zonder dat rekt het mobiele menu de pagina op.

## 8. Databronnen

- Catalogus: `https://deklokjesman.com/wp-json/wc/store/v1/products?per_page=100&page=N`, 204 producten.
- Producten zijn `simple`, **zonder attributen, SKU of varianten**.
- Tier zit in `product_cat`: `superclone`, `bp-swiss-made`, `aaa`.
- Overige categorieën: `dames`, `rolex-dozen`, `brillen`, `accessoires`, `2e-hands`, `sale`, `meest-verkocht`.
- Model, maat en wijzerplaat staan alleen in de titel. `product-attributes.csv` bevat de
  geparste waarden voor alle 204 producten om `pa_model`, `pa_maat` en `pa_wijzerplaat` te vullen.
- Dozen: Rolex Doos (Standaard) €65 en Rolex Doos (Premium) €130.
- `average_rating` en `review_count` bestaan al in de API.
