# Live zetten

Dit is de volgorde waarin het thema op deklokjesman.com komt. Lees stap 0 en
stap 7 voordat je begint, dan weet je hoe je terug kunt.

## Wat er klaar moet staan

| | Nodig | Nu live |
|---|---|---|
| WordPress | 6.4 of hoger | 7.0.8 |
| WooCommerce | 8.0 of hoger | 11.2.1 |
| PHP | 8.0 of hoger | controleren in Site Health |
| Advanced Custom Fields | gratis versie volstaat | installeren |

ACF Pro is niet nodig. Er zitten geen repeaters in, omdat het ontwerp precies
vier USP's per banner en vijf punten op de anatomiefoto kent.

## 0. Eerst een back-up

Maak een volledige back-up van bestanden en database, of zet een staging-kopie
klaar. Stap 2 schrijft naar 204 producten. Zonder back-up is dat niet terug te
draaien.

## 1. Plugin en thema installeren

De twee pakketten maak je met `./build.sh`. Die komen in `dist/`.

1. **Plugins, Nieuwe plugin, Plugin uploaden**: `dkm-core-1.0.0.zip`, daarna
   activeren. Doe de plugin eerst, want het thema verwacht hem.
2. **Plugins**: installeer en activeer Advanced Custom Fields als die er nog
   niet is.
3. **Weergave, Thema's, Nieuw thema toevoegen, Thema uploaden**:
   `dkm-theme-2.0.0.zip`. Nog **niet** activeren.

## 2. Datalaag vullen

Dit kan terwijl het oude thema nog actief is, want het raakt alleen
productgegevens. Via WP-CLI of de terminal van je host:

```bash
wp dkm create-attributes
```

```bash
wp dkm backfill-attributes --dry-run
```

Lees wat de droge test meldt. Je hoort te zien: 204 rijen, 9 waarden afgeleid
uit de titel, 0 rijen zonder product, 0 horloges zonder model. Klopt dat, draai
hem dan echt:

```bash
wp dkm backfill-attributes
```

```bash
wp dkm sync-skus --dry-run
```

```bash
wp dkm sync-skus
```

Het commando is idempotent. Draai je het een tweede keer, dan meldt het
`changed: 0, unchanged: 204`, en een product dat al een SKU heeft blijft met
rust tenzij je `--force` meegeeft.

De CSV zit in de plugin, in `dkm-core/data/product-attributes.csv`. Heb je een
nieuwere export, geef die dan mee met `--file=/pad/naar/bestand.csv`.

Heeft je host geen WP-CLI, vraag dan of ze het eenmalig voor je draaien. Het
alternatief is 204 producten met de hand aanpassen in de beheeromgeving.

## 3. Thema activeren

**Weergave, Thema's**: activeer DKM. Vanaf hier ziet de winkel er anders uit.

Verschijnt er een rode melding dat DKM Core niet actief is, ga dan terug naar
stap 1. Zonder die plugin blijven de reviews, de UGC en de doos bij een horloge
leeg, maar blijft de winkel wel verkopen.

## 4. Pagina's instellen

**Belangrijk.** WooCommerce 11 zet winkelwagen en afrekenen standaard op de
nieuwe blokken. Die negeren de templates van het thema volledig, dus die twee
pagina's moeten op de klassieke shortcode:

- Pagina **Winkelwagen**: haal alle blokken weg en zet er één shortcode-blok in
  met `[woocommerce_cart]`.
- Pagina **Afrekenen**: hetzelfde met `[woocommerce_checkout]`.

Verder:

- Maak een pagina **Kwaliteitsinformatie** en kies rechts bij Paginasjabloon
  voor **Kwaliteitsinformatie**. Het thema vindt de pagina daaraan, dus de
  naam van de pagina mag je zelf kiezen.
- **Instellingen, Lezen**: zet de homepage op een statische pagina.
- **Weergave, Menu's**: koppel een menu aan Hoofdmenu. Doe je dat niet, dan
  vult het thema zelf de kwaliteiten, Dames, Brillen, Dozen en Sale in.

## 5. Beeld en teksten

- **Producten, Categorieën**: geef Superclone, BP Swiss Made en AAA+ elk een
  categorie-afbeelding. Die verschijnt in het megamenu, in de kwaliteitsrijen
  op de homepage en op de collectiepagina.
- **Homepage bewerken**: vul onder Hero de brede hero-afbeelding (minstens 1600
  bij 900), de kop, het accentwoord en de tekst. Vul onder Anatomie de
  close-up en per punt de titel, de uitleg en de positie in procenten.
- **Producten, Categorieën**: per categorie kun je onder Collectiebanner een
  eigen foto, kop en vier USP's zetten. Laat je het leeg, dan gebruikt het
  thema de standaardteksten.
- **Reviews**: nieuw item, naam van de klant, sterren, en de screenshot als
  uitgelichte afbeelding. De WhatsApp-balk wordt bij het opslaan één keer
  weggesneden, 11 procent bovenaan en 6 procent onderaan. Dat gebeurt server
  side, dus niet opnieuw bij elke bezoeker.
- **UGC-video's**: nieuw item, het videobestand bij Video, een poster als
  uitgelichte afbeelding en een onderschrift. Staand formaat 9 bij 16.

## 6. Customizer

**Weergave, Customizer, De Klokjesman**:

- **Contactgegevens**: WhatsApp-nummer als `31647974408`, het standaardbericht,
  e-mailadres, Snapchat en de link naar De Outfitman.
- **Cijfers op de homepage**: aantal geleverde horloges, reviews, gemiddelde
  beoordeling en het aanbevelingspercentage.
- **Actiebalk**: laat die uit staan tot Black Friday. Zet je hem aan, vul dan
  ook de laatste actiedag in. De aftelling loopt tot middernacht van die dag en
  daarna verdwijnt de balk vanzelf. De hele rode modus hangt aan die ene
  schakelaar.

## 7. Controleren

Loop dit af op zowel een computer als een telefoon:

- [ ] Homepage: hero, de drie kwaliteitsrijen op één regel, de carousel, de
      bestsellers met werkende filters, de reviews, de anatomiepunten.
- [ ] Megamenu opent en sluit, ook met Escape.
- [ ] Collectiepagina: filteren op kwaliteit en op model, tellers die
      meebewegen, sorteren, Meer laden.
- [ ] Productpagina per kwaliteit: Superclone, BP en AAA+ zien er elk anders
      uit en tonen hun eigen specificaties.
- [ ] Doos kiezen: het totaal op de knop verandert mee.
- [ ] In winkelwagen: de lade schuift open en toont het horloge met de doos
      eronder.
- [ ] Winkelwagenpagina: de doos staat ingesprongen onder het horloge en heeft
      geen eigen verwijderknop. Haal je het horloge weg, dan gaat de doos mee.
- [ ] Afrekenen: iDEAL staat erbij en een testbestelling komt door.
- [ ] Bedankpagina en Mijn account zien er als het thema uit.
- [ ] Geen zijwaartse scroll op 390 pixels breed.
- [ ] Toetsenbord: Tab door de pagina, open het megamenu met Enter, loop er met
      Tab in rond en sluit het met Escape. De focus hoort daarna terug op de
      knop te staan waarmee je het opende.

Gaat er iets mis bij afrekenen, kijk dan eerst of de pagina de shortcode uit
stap 4 heeft.

## 8. Terug als het misgaat

1. **Weergave, Thema's**: activeer het oude thema. De winkel draait meteen weer
   zoals eerst, want de producten zijn niet aangeraakt door het thema.
2. Laat DKM Core gewoon aan staan. De attributen, reviews en UGC blijven dan
   bestaan en je hoeft stap 2 niet opnieuw te doen.
3. Alleen als je ook de datalaag terug wilt: zet de back-up uit stap 0 terug.

## Wat nog moet gebeuren

- De UGC-sectie draait nu op posters zonder video. Zet de TikTok- en
  Reels-bestanden erin bij UGC-video's.
