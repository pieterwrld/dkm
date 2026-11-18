const I = {
truck:'M3 7.5h10.5v8H3zM13.5 10h3.8L21 13.2v2.3h-7.5M7 19a1.8 1.8 0 1 0 0-3.6A1.8 1.8 0 0 0 7 19Zm10 0a1.8 1.8 0 1 0 0-3.6 1.8 1.8 0 0 0 0 3.6Z',
shield:'M12 21s7-3.5 7-9.3V5.6L12 3 5 5.6v6.1C5 17.5 12 21 12 21Zm-2.6-9.4 1.9 1.9 3.5-3.6',
chat:'M20 14.4a3 3 0 0 1-3 3H8.6L4.5 20.5V6.6a3 3 0 0 1 3-3h9.5a3 3 0 0 1 3 3v7.8Z',
box:'M3.2 7.4 12 3.2l8.8 4.2v9.2L12 20.8l-8.8-4.2V7.4Zm0 0L12 11.7l8.8-4.3M12 11.7v9.1',
gem:'M6.2 3.2h11.6l3.2 5.6L12 20.8 3 8.8l3.2-5.6ZM3 8.8h18M9.4 3.2 6.6 8.8 12 20.8l5.4-12-2.8-5.6',
watch:'M12 19.2a5.6 5.6 0 1 0 0-11.2 5.6 5.6 0 0 0 0 11.2ZM9.3 7.8 9.8 3h4.4l.5 4.8M9.3 19.4l.5 4.3M12 10.8v2.9h2.2',
swiss:'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18ZM12 7.8v8.4M7.8 12h8.4',
drop:'M12 3.2s5.8 6.3 5.8 10.2a5.8 5.8 0 0 1-11.6 0C6.2 9.5 12 3.2 12 3.2Z',
glow:'M12 4.2V6m0 12v1.8M4.2 12H6m12 0h1.8M6.6 6.6 7.9 7.9m8.2 8.2 1.3 1.3m0-10.8-1.3 1.3M7.9 16.1l-1.3 1.3M12 8.4a3.6 3.6 0 1 0 0 7.2 3.6 3.6 0 0 0 0-7.2Z',
weight:'M5.2 8.2h13.6L17.2 20.6H6.8L5.2 8.2ZM9 8.2a3 3 0 1 1 6 0',
engrave:'M3.4 17.3 14.6 6.1l3.4 3.4L6.8 20.7H3.4v-3.4ZM13.2 7.5l3.4 3.4M16.2 4.2l3.6 3.6',
crown:'M3.6 17.4h16.8l1.2-9.6-5.4 3.8L12 5.2 7.8 11.6 2.4 7.8l1.2 9.6Zm0 2.6h16.8',
star:'m12 3.2 2.7 5.7 6.2.9-4.5 4.3 1.1 6.1L12 17.3l-5.5 2.9 1.1-6.1-4.5-4.3 6.2-.9L12 3.2Z',
check:'m4.5 12.2 5 5L19.5 6.6',
minus:'M5.4 12h13.2',
close:'M6 6l12 12M18 6 6 18',
info:'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18ZM12 11v5.2M12 7.6v.4',
search:'M11 18a7 7 0 1 0 0-14 7 7 0 0 0 0 14ZM16.3 16.3 21 21',
cart:'M4.8 7h14.4l-1.3 12.5H6.1L4.8 7ZM9 7V5.4a3 3 0 0 1 6 0V7',
arrow:'M4.8 12h14.4M13.2 6l6 6-6 6',
left:'M19.2 12H4.8M10.8 6l-6 6 6 6',
play:'M8.4 5.4v13.2L19 12 8.4 5.4Z',
lock:'M5.8 10.8h12.4v9.4H5.8v-9.4ZM8.8 10.8V8.2a3.2 3.2 0 0 1 6.4 0v2.6',
refresh:'M20.4 12a8.4 8.4 0 1 1-2.5-5.9M20.4 3.6v5h-5',
spark:'m12 3 1.9 5.4 5.4 1.9-5.4 1.9L12 17.6l-1.9-5.4-5.4-1.9 5.4-1.9L12 3Z',
filter:'M3.6 6.6h16.8M6.6 12h10.8M10.2 17.4h3.6',
clock:'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18ZM12 7.2V12l3.2 2.1',
tag:'M11.3 3.4H20v8.7l-8.6 8.6-8.7-8.7 8.6-8.6ZM16.4 8.2h.01',
eye:'M2.4 12S5.8 5.8 12 5.8 21.6 12 21.6 12 18.2 18.2 12 18.2 2.4 12 2.4 12Zm9.6 2.8a2.8 2.8 0 1 0 0-5.6 2.8 2.8 0 0 0 0 5.6Z',
wa:'M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 2.1.55 4.08 1.6 5.85L2 22l4.37-1.68a9.86 9.86 0 0 0 5.67 1.76c5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2Zm5.8 14.1c-.25.69-1.44 1.32-2 1.4-.51.08-1.16.11-1.87-.12-.43-.13-.98-.32-1.69-.62-2.98-1.29-4.92-4.28-5.07-4.48-.15-.2-1.21-1.61-1.21-3.07 0-1.46.77-2.18 1.04-2.48.27-.3.59-.37.79-.37h.57c.18 0 .43-.07.67.51.25.6.85 2.06.92 2.21.07.15.12.32.02.52-.1.2-.15.32-.3.5l-.44.52c-.15.15-.3.31-.13.61.17.3.75 1.24 1.62 2.01 1.11.99 2.05 1.3 2.35 1.45.3.15.47.12.64-.07.17-.2.74-.86.94-1.16.2-.3.4-.25.67-.15.27.1 1.72.81 2.01.96.3.15.5.22.57.35.07.12.07.72-.18 1.41Z',
snap:'M12 2.6c2.8 0 4.4 1.9 4.4 4.6 0 .7-.05 1.4-.08 1.9.5.2.9 0 1.4-.2.6-.2 1.1.5.6 1.05-.4.5-1.25.86-1.72 1.05-.3.1-.4.3-.3.6.5 1.45 1.9 2.9 3.25 3.3.5.2.5.75 0 .95-.67.3-1.53.4-1.82.67-.2.2-.1.58-.3.77-.2.2-.67.1-1.25 0-.86-.1-1.82-.1-2.59.48-.67.48-1.25 1.15-2.59 1.15s-1.92-.67-2.59-1.15c-.77-.58-1.73-.58-2.59-.48-.58.1-1.05.2-1.25 0-.2-.2-.1-.58-.3-.77-.29-.28-1.15-.38-1.82-.67-.5-.2-.5-.76 0-.96 1.35-.38 2.78-1.82 3.26-3.28.1-.29 0-.48-.3-.58-.48-.2-1.34-.57-1.73-1.05-.48-.57.1-1.24.67-1.05.48.2.86.38 1.34.2-.03-.58-.07-1.25-.07-1.92C7.65 4.5 9.2 2.6 12 2.6Z',
mail:'M3.4 5.6h17.2v12.8H3.4V5.6Zm.6.6 8 5.7 8-5.7'
};
const svg=(n,cls)=>`<svg class="ico ${cls||''}" viewBox="0 0 24 24"${['star','play','wa','snap'].includes(n)?' fill="currentColor" stroke="none"':''}><path d="${I[n]}"/></svg>`;
function paintIcons(root){
(root||document).querySelectorAll('[data-ic]').forEach(e=>{
const n=e.dataset.ic; if(!I[n]||e._p)return; e._p=1;
e.insertAdjacentHTML('afterbegin',svg(n));
});
(root||document).querySelectorAll('svg[data-ic2]').forEach(e=>{
const n=e.dataset.ic2; if(!I[n]||e._p)return; e._p=1;
e.setAttribute('viewBox','0 0 24 24');
if(['star','play','wa','snap'].includes(n)){e.setAttribute('fill','currentColor');e.setAttribute('stroke','none');}
e.innerHTML=`<path d="${I[n]}"/>`;
});
}
const LOGO = `<span class="brand">DE KL<svg class="o" viewBox="0 0 40 40" aria-hidden="true">
<circle class="dial" cx="20" cy="21.5" r="14.6"/>
<circle class="dial" cx="20" cy="21.5" r="11.6" style="opacity:.3"/>
<path class="tick" d="M20 9.3v2.6M20 31.1v2.6M7.8 21.5h2.6M29.6 21.5h2.6"/>
<path class="crown" d="M14.6 6.4 17 8.1 20 4.6l3 3.5 2.4-1.7-.7 3.9h-9.4l-.7-3.9Z"/>
<g class="hh"><path class="hand" d="M20 21.5V15.4"/></g>
<g class="mh"><path class="hand" d="M20 21.5h5.6" style="stroke-width:1.7"/></g>
<circle cx="20" cy="21.5" r="1.5" fill="currentColor"/>
</svg>KJESMAN</span>`;
const T = {
superclone:{n:'Superclone',c:'#A8864A',bg:'#FCF7EC',s:'rgba(168,134,74,.5)',from:900,
sub:'Niet van echt te onderscheiden', icon:'crown',
claim:'De absolute topklasse. 1:1 nagemaakt met 904L staal, saffierglas en een Zwitsers uurwerk, tot en met het gewicht en de gravures.',
feats:['1:1 replica, 904L staal','Saffierglas','Zwitsers clone-uurwerk','Gewicht als origineel'],
bullets:['1:1 replica met premium materialen (904L staal, saffierglas)','Zwitsers of hoogwaardig clone-uurwerk (28.800 vph, vloeiende secondewijzer)','Perfecte gravures, logo’s en gewicht zoals origineel','Werkende complicaties (GMT, chronograaf, datum)','Goede waterdichtheid (meestal 50m tot 100m)'],
who:'Voor wie geen enkel compromis wil en het verschil met het origineel niet wil zien, ook niet van dichtbij.',
glass:'Saffierglas',mov:'Zwitsers clone-uurwerk, 28.800 vph',steel:'904L Oystersteel',water:'50m tot 100m',
x:[['gem','Saffierglas','Nagenoeg onkrasbaar glas met AR-coating, exact zoals het origineel.'],
['swiss','28.800 vph uurwerk','Een vloeiende secondewijzer in plaats van een tikkende. Dit is het eerste waar kenners naar kijken.'],
['weight','Correct gewicht','904L staal weegt zwaarder dan 316L. Je voelt direct dat het klopt zodra je hem omdoet.']]},
bp:{n:'BP Swiss Made',c:'#4D6A8A',bg:'#F2F6FA',s:'rgba(77,106,138,.45)',from:370,
sub:'De slimste keuze', icon:'swiss',
claim:'Zwitsers of ETA-uurwerk in 316L staal met saffierglas. De beste verhouding tussen prijs en kwaliteit die wij voeren.',
feats:['316L staal + saffierglas','Zwitsers of ETA-uurwerk','Nauwkeurige gravures','Werkende complicaties'],
bullets:['Mooie afwerking met roestvrij staal (316L) en saffierglas','Betrouwbaar Zwitsers of Aziatisch ETA-uurwerk','Nauwkeurige gravures en logo’s','Werkende complicaties en soepele secondewijzer','Betaalbaarder dan een superclone'],
who:'Voor wie een serieus horloge wil met een echt uurwerk, zonder de prijs van een superclone te betalen.',
glass:'Saffierglas',mov:'Zwitsers of ETA automatisch',steel:'316L roestvrij staal',water:'30m tot 50m',
x:[['gem','Saffierglas','Ook op dit niveau krijg je saffierglas, dus geen krassen van dagelijks dragen.'],
['swiss','Betrouwbaar automaat','Een ETA-gebaseerd automatisch uurwerk dat jarenlang meegaat en soepel loopt.'],
['engrave','Nauwkeurige gravures','Logo’s en graveringen zijn scherp uitgevoerd, al net niet op superclone-niveau.']]},
aaa:{n:'AAA+',c:'#7B5EA8',bg:'#F7F4FC',s:'rgba(123,94,168,.4)',from:150,
sub:'Betaalbaar instapmodel', icon:'spark',
claim:'Een verzorgd horloge voor een scherpe prijs. Basis automatisch uurwerk, lichtgewicht en comfortabel voor dagelijks gebruik.',
feats:['Scherpe prijs','Basis automaat','Lichtgewicht','Dagelijks dragen'],
bullets:['Mooie uitstraling voor een lage prijs','Basis automatisch uurwerk','Goede imitaties van bekende merken','Lichtgewicht en comfortabel','Perfect voor dagelijks gebruik'],
who:'Voor wie voor het eerst zo’n horloge koopt, of iets zoekt om zorgeloos elke dag te dragen.',
glass:'Krasbestendig mineraalglas',mov:'Basis automatisch uurwerk',steel:'Roestvrij staal',water:'30m',
x:[['spark','Scherpe prijs','Vanaf €150 draag je een horloge dat er van een meter afstand uitstekend uitziet.'],
['refresh','Basis automaat','Een automatisch uurwerk zonder batterij, dat oploopt door de beweging van je pols.'],
['watch','Licht en comfortabel','Lichter dan een superclone, waardoor je hem de hele dag vergeet te dragen.']]}
};
const MATRIX = [
['Automatisch uurwerk',{superclone:'y',bp:'y',aaa:'y'},'Alle drie de niveaus lopen op een automatisch uurwerk, dus zonder batterij.'],
['Zwitsers of ETA-kaliber',{superclone:'y',bp:'y',aaa:'n'},'Superclone en BP gebruiken een Zwitsers of ETA-gebaseerd kaliber. AAA+ heeft een basis automaat.'],
['Saffierglas',{superclone:'y',bp:'y',aaa:'n'},'Saffierglas is nagenoeg onkrasbaar. AAA+ heeft krasbestendig mineraalglas.'],
['904L Oystersteel',{superclone:'y',bp:'p',aaa:'n'},'Superclone gebruikt 904L, hetzelfde staal als het origineel. BP gebruikt 316L, AAA+ standaard roestvrij staal.'],
['Correct gewicht en balans',{superclone:'y',bp:'p',aaa:'n'},'Een superclone weegt exact hetzelfde als het origineel. Dat voel je direct om je pols.'],
['Perfecte gravures en logo’s',{superclone:'y',bp:'p',aaa:'p'},'Bij superclone zijn de graveringen identiek. BP en AAA+ komen in de buurt maar niet exact.'],
['Werkende complicaties',{superclone:'y',bp:'y',aaa:'p'},'GMT, chronograaf en datum werken bij superclone en BP volledig. Bij AAA+ deels decoratief.'],
['Waterdicht',{superclone:'y',bp:'y',aaa:'p'},'Superclone 50m tot 100m, BP 30m tot 50m, AAA+ 30m. Allemaal bestand tegen regen en spatten.'],
['Glow in the dark',{superclone:'y',bp:'y',aaa:'y'},'Alle horloges hebben oplichtende wijzers, zodat je ook in het donker de tijd afleest.'],
['Niet van echt te onderscheiden',{superclone:'y',bp:'p',aaa:'n'},'Alleen de superclone doorstaat inspectie van dichtbij door iemand die weet waar hij op moet letten.']
];
const USPS = [
['truck','Vandaag besteld','Morgen in huis'],
['shield','Kwaliteitsgarantie','Op elk horloge'],
['chat','Persoonlijk advies','Direct via WhatsApp'],
['box','Discreet verzonden','Neutrale verpakking']
];
const BANNERS = {
all:{e:'Alle horloges',t:'Van instapmodel tot superclone, allemaal uit voorraad',
u:[['truck','Vandaag besteld, morgen in huis'],['shield','Kwaliteitscontrole voor verzending'],['refresh','14 dagen omruilgarantie'],['chat','Persoonlijk advies via WhatsApp']]},
superclone:{e:'Superclone',t:'De absolute topklasse, tot in het gewicht nagemaakt',
u:[['gem','Saffierglas met AR-coating'],['swiss','Zwitsers uurwerk, 28.800 vph'],['weight','904L staal, correct gewicht'],['eye','Niet van echt te onderscheiden']]},
bp:{e:'BP Swiss Made',t:'De slimste keuze van het moment',
u:[['swiss','Zwitsers of ETA-uurwerk'],['gem','Saffierglas'],['shield','316L roestvrij staal'],['star','Meest gekozen door klanten']]},
aaa:{e:'AAA+',t:'Betaalbaar beginnen zonder in te leveren op uitstraling',
u:[['tag','Al vanaf €150'],['drop','30m waterdicht'],['shield','Krasbestendig glas'],['watch','Licht en comfortabel']]},
dames:{e:'Dames',t:'Verfijnd, in 31 en 36 millimeter',
u:[['watch','Kleinere kastmaten'],['spark','Diamantwijzerplaten'],['crown','Rosé, goud en staal'],['truck','Gratis verzending']]},
sale:{e:'Black Friday',t:'Tot 30% korting, zolang de voorraad strekt',
u:[['tag','Tijdelijke actieprijzen'],['clock','Op = op, geen nalevering'],['shield','Dezelfde garantie'],['truck','Vandaag besteld, morgen in huis']]},
doos:{e:'Rolex dozen',t:'Maak je horloge compleet met de juiste doos',
u:[['box','Originele presentatiedoos'],['crown','Standaard of premium set'],['spark','Perfect als cadeau'],['tag','Los of bij je horloge']]},
brillen:{e:'Brillen',t:'Dezelfde standaard, ander product',
u:[['eye','Dita, Cartier en John Dalia'],['shield','UV-bescherming'],['box','Inclusief etui'],['truck','Gratis verzending']]},
'2e':{e:'2e hands',t:'Zorgvuldig gecontroleerde tweedehands horloges',
u:[['refresh','Volledig nagekeken'],['shield','Met garantie'],['tag','Scherpere prijs'],['chat','Vraag naar de voorraad']]}
};
const DELIVER = [
['box','In onze eigen verpakking','Elk horloge gaat beschermd en discreet de deur uit. Aan de buitenkant zie je niet wat erin zit.'],
['eye','Gecontroleerd voor verzending','We lopen elk horloge na op de wijzerplaat, het glas, de sluiting en de loop van het uurwerk.'],
['truck','Vandaag besteld, morgen in huis','Bestel je op werkdagen voor 17:00, dan ligt het de volgende dag op de mat.'],
['chat','Vragen onderweg','Twijfel je over de maat of de kleur? Stuur een bericht, we sturen extra foto’s of een video.'],
['refresh','14 dagen omruilen','Zit hij toch niet lekker? Dan ruilen we hem binnen 14 dagen om voor een ander model.']
];
const ANATOMY = [
['Wijzerplaat en index','De print moet haarscherp zijn en de indexen moeten perfect recht staan. Bij een superclone is er geen enkele afwijking te zien.',{t:'22%',l:'46%'}],
['Secondewijzer','Een echt automatisch uurwerk loopt vloeiend, niet in tikjes. Dat is het snelste wat je zelf kunt controleren.',{t:'44%',l:'30%'}],
['Bezel en gravering','De randen moeten strak zijn en de gravering even diep over de hele cirkel. Hier zie je het verschil tussen de niveaus.',{t:'36%',l:'70%'}],
['Kroon en schouders','De kroon moet soepel draaien en de kroonbescherming moet symmetrisch zijn. Bij ons draait hij zoals het hoort.',{t:'58%',l:'80%'}],
['Band en sluiting','Een goede band rammelt niet en de sluiting klikt met één duidelijke klik dicht. Dat voel je meteen.',{t:'74%',l:'44%'}]
];
const $=s=>document.querySelector(s), $$=s=>[...document.querySelectorAll(s)];
const eur=n=>'€'+n.toLocaleString('nl-NL');
const el=(t,c,h)=>{const n=document.createElement(t);if(c)n.className=c;if(h!=null)n.innerHTML=h;return n;};
const short=s=>s.replace(/\s*\((SUPERCLONE|BP SWISS MADE|AAA\+)\)\s*$/i,'').trim();
const saleOf=p=>p.sale?Math.round(p.price*0.7/5)*5:null;
const MKI={y:'check',p:'minus',n:'close'};
const mk=v=>`<span class="mk ${v}">${svg(MKI[v])}</span>`;
function brandOf(p){if(p.tag==='doos')return'Accessoire';if(p.tag==='bril')return(p.name.split('|')[0]||'Eyewear').trim();
const w=short(p.name).split(/\s+/);return w[0]==='Rolex'&&w[1]?w[1].replace(/["|]/g,''):w[0];}
function toast(m){const t=$('#toast');t.innerHTML=svg('check')+'<span>'+m+'</span>';t.classList.add('on');
clearTimeout(t._);t._=setTimeout(()=>t.classList.remove('on'),2300);}
$$('[data-logo]').forEach(e=>e.innerHTML=LOGO);
$$('[data-logo-lt]').forEach(e=>{e.innerHTML=LOGO;e.querySelector('.brand').classList.add('lt');e.querySelector('.brand').style.setProperty('--bs','22px');});
const NAVITEMS=[['superclone','Superclone'],['bp','BP Swiss Made'],['aaa','AAA+'],['dames','Dames'],['brillen','Brillen'],['doos','Dozen'],['sale','Sale']];
(()=>{
const n=$('#nav');
n.appendChild(Object.assign(el('button','', 'Collectie '+svg('arrow').replace('class="ico "','class="ico" style="transform:rotate(90deg)"')),{onclick:e=>{e.stopPropagation();toggleMega(e.currentTarget);}}));
NAVITEMS.slice(0,4).forEach(([k,l])=>{const b=el('button','',l);b.onclick=()=>{closeMega();go('col',k);};n.appendChild(b);});
const inf=el('button','','Informatie');inf.onclick=()=>{closeMega();go('info');};n.appendChild(inf);
const s=el('button','red','Sale');s.onclick=()=>{closeMega();go('col','sale');};n.appendChild(s);
const m=$('#mb');
m.innerHTML=NAVITEMS.map(([k,l])=>`<a onclick="go('col','${k}');mob()" class="${k==='sale'?'red':''}">${l}<svg class="ico" data-ic2="arrow"></svg></a>`).join('')
+`<a onclick="go('info');mob()">Kwaliteitsinformatie<svg class="ico" data-ic2="arrow"></svg></a>`
+`<a href="https://wa.link/q23dxa" target="_blank" style="color:var(--wa)">WhatsApp ons<svg class="ico" data-ic2="wa"></svg></a>`;
const g=$('#megaG');
const thumbs=[['superclone','Superclone','Vanaf €900','ed_superclone.webp'],['bp','BP Swiss Made','Vanaf €370','ed_bp.webp'],
['aaa','AAA+','Vanaf €150','ed_aaa.webp'],
['dames','Dames','31 en 36 mm',(P.find(p=>p.dames)||P[0]).f],
['doos','Dozen','Standaard & premium',(P.find(p=>p.tag==='doos')||P[0]).f]];
thumbs.forEach(([k,t,s,f])=>{
const c=el('div','mega-c',`<img src="${AU(f)}" alt="${t}" loading="lazy"><div><b>${t}</b><span>${s}</span></div>`);
c.onclick=()=>{closeMega();go('col',k);};g.appendChild(c);
});
})();
function toggleMega(btn){
const m=$('#mega'),open=m.classList.contains('on');
$$('.nav>button').forEach(b=>b.classList.remove('open'));
if(open)return closeMega();
m.style.setProperty('--mt',($('.hd').offsetHeight+$('.hd').getBoundingClientRect().top)+'px');
m.classList.add('on');$('#scrim').classList.add('on');btn.classList.add('open');
}
function closeMega(){$('#mega').classList.remove('on');$('#scrim').classList.remove('on');$$('.nav>button').forEach(b=>b.classList.remove('open'));}
function mob(){$('#bg').classList.toggle('on');$('#mb').classList.toggle('on');
document.body.classList.toggle('nobody',$('#mb').classList.contains('on'));}
(()=>{const it=['Vandaag besteld = morgen in huis','Persoonlijk contact via WhatsApp','1200+ horloges geleverd','Black Friday · tot 30% korting','14 dagen omruilgarantie'];
let h='';for(let k=0;k<2;k++)h+='<div class="tick-i">'+it.map(t=>`<span>${t}</span><em>◆</em>`).join('')+'</div>';
$('#tick').innerHTML=h;})();
(()=>{const end=new Date();end.setHours(23,59,59,999);
const t=()=>{const d=Math.max(0,end-new Date());
const p=n=>String(n).padStart(2,'0');
$('#cd').innerHTML=`<b>${p(Math.floor(d/36e5))}</b><b>${p(Math.floor(d/6e4)%60)}</b><b>${p(Math.floor(d/1e3)%60)}</b>`;};
t();setInterval(t,1000);})();
(()=>{const w=$('#umq');let h='';
for(let k=0;k<2;k++){h+='<div class="umq-s">'+USPS.map(([i,a,b])=>
`<div class="umq-i">${svg(i)}<div><b>${a}</b><span>${b}</span></div></div><i class="umq-d"></i>`).join('')+'</div>';}
w.innerHTML=h;})();
function card(p){
const n=el('div','pc rv'),sp=saleOf(p),tn=T[p.tier]?T[p.tier].n:(p.tag==='doos'?'Accessoire':p.tag==='bril'?'Bril':'Overig');
const col=T[p.tier]?T[p.tier].c:'#67655F';
n.innerHTML=`<div class="pc-img"><div class="pc-tags">
${p.tier!=='other'?`<span class="tg" style="color:${col}">${tn}</span>`:''}
${p.sale?'<span class="tg sale">-30%</span>':''}</div>
<img src="${AU(p.f)}" alt="${short(p.name)}" loading="lazy"></div>
<div class="pc-b"><span class="pc-m">${p.model||brandOf(p)}${p.mm?' · '+p.mm+' mm':''}</span>
<span class="pc-n">${short(p.name)}</span>
<span class="pc-p">${sp?`<s>${eur(p.price)}</s>${eur(sp)}`:eur(p.price)}</span></div>`;
n.onclick=()=>openPd(p);return n;
}
(()=>{const w=$('#qrows');
['superclone','bp','aaa'].forEach(k=>{
const t=T[k],n=P.filter(p=>p.tier===k).length,r=el('div','qrow rv');
r.style.cssText=`--qc:${t.c};--qbg:${t.bg};--qs:${t.s}`;
r.innerHTML=`<div class="qbar qshort"><img src="${AU("ed_"+k+".webp")}" alt="${t.n}" loading="lazy">
<div><span class="qpill">${t.n}</span><b>Vanaf ${eur(t.from)}</b><small>${n} modellen</small></div></div>
<div class="qbar qlong"><div class="qfeat">${t.feats.map(f=>`<div>${svg('check')}${f}</div>`).join('')}</div>
<div class="qmeta"><b>${t.sub}</b><span>Bekijk</span></div>
<div class="qgo">${svg('arrow')}</div></div>`;
r.onclick=()=>go('col',k);w.appendChild(r);
});})();
function carInit(id){
const c=document.getElementById(id);
const dots=document.getElementById(id+'D');
if(dots){dots.innerHTML='';[...c.children].forEach((_,i)=>{const d=el('i');d.onclick=()=>carTo(id,i);dots.appendChild(d);});}
c.onscroll=()=>carSync(id);carSync(id);
}
function carIdx(c){const k=[...c.children];const x=c.scrollLeft+c.clientWidth/2;
let best=0,bd=1e9;k.forEach((n,i)=>{const d=Math.abs(n.offsetLeft+n.offsetWidth/2-x);if(d<bd){bd=d;best=i;}});return best;}
function carSync(id){const c=document.getElementById(id),d=document.getElementById(id+'D');
const i=carIdx(c);if(d)[...d.children].forEach((n,j)=>n.classList.toggle('on',j===i));
const p=document.getElementById(id+'P'),n2=document.getElementById(id+'N');
if(p)p.disabled=c.scrollLeft<4;
if(n2)n2.disabled=c.scrollLeft>=c.scrollWidth-c.clientWidth-4;}
function carTo(id,i){const c=document.getElementById(id),n=c.children[i];if(n)
c.scrollTo({left:n.offsetLeft-(c.clientWidth-n.offsetWidth)/2,behavior:'smooth'});}
function carGo(id,d){const c=document.getElementById(id);carTo(id,Math.max(0,Math.min(c.children.length-1,carIdx(c)+d)));}
(()=>{const w=$('#qcar');
['bp','superclone','aaa'].forEach((k,i)=>{
const t=T[k],c=el('div','qc rv'+(i===0?' feat':''));
const lis=MATRIX.slice(0,6).map(([lab,m,tip])=>{const v=m[k];
return `<div class="qc-li ${v==='n'?'off':''}">${mk(v)}<span>${lab}</span>
<span class="inf" onclick="tgTip(this,event)">i<span class="tip">${tip}</span></span></div>`;}).join('');
c.innerHTML=`${i===0?'<span class="qc-rib gr">Meest gekozen</span>':''}
<h3>${t.n}</h3><p class="sub">${t.sub}</p>
<div class="qc-pr">${eur(t.from)}<small>vanaf</small></div>
<img class="ph" src="${AU("ed_"+k+".webp")}" alt="${t.n}" loading="lazy">
<div class="qc-l">${lis}</div>
<button class="btn ${i===0?'btn-g':'btn-o'} btn-full">Shop ${t.n}</button>`;
c.querySelector('button.btn').onclick=()=>go('col',k);
w.appendChild(c);
});})();
function tgTip(e,ev){ev.stopPropagation();const o=e.classList.contains('on');
$$('.inf.on').forEach(n=>n.classList.remove('on'));if(!o)e.classList.add('on');}
document.addEventListener('click',()=>$$('.inf.on').forEach(n=>n.classList.remove('on')));
let bsT='all',bsM='all';
function renderBsFilters(){
const tw=$('#bsTier');tw.innerHTML='';
[['all','Alles'],['superclone','Superclone'],['bp','BP Swiss Made'],['aaa','AAA+']].forEach(([k,l])=>{
const n=P.filter(p=>p.tag==='watch'&&(k==='all'||p.tier===k)).length;
const b=el('button','ch'+(bsT===k?' on':''),`${l}<b>${n}</b>`);
b.onclick=()=>{bsT=k;bsM='all';renderBsFilters();renderBs();};tw.appendChild(b);});
const mw=$('#bsModel');mw.innerHTML='';
const a=el('button','chs'+(bsM==='all'?' on':''),'Alle modellen');
a.onclick=()=>{bsM='all';renderBsFilters();renderBs();};mw.appendChild(a);
const pool=P.filter(p=>p.tag==='watch'&&(bsT==='all'||p.tier===bsT)),m={};
pool.forEach(p=>{if(p.model)m[p.model]=(m[p.model]||0)+1;});
Object.entries(m).sort((x,y)=>y[1]-x[1]).forEach(([k,c])=>{
const b=el('button','chs'+(bsM===k?' on':''),`${k} <b style="opacity:.5">${c}</b>`);
b.onclick=()=>{bsM=k;renderBsFilters();renderBs();};mw.appendChild(b);});
}
function renderBs(){
const g=$('#bsGrid');g.innerHTML='';
const l=P.filter(p=>p.tag==='watch'&&(bsT==='all'||p.tier===bsT)&&(bsM==='all'||p.model===bsM))
.sort((a,b)=>b.best-a.best).slice(0,8);
if(!l.length){g.appendChild(el('div','empty','Geen modellen in deze combinatie.'));return;}
l.forEach((p,i)=>{const c=card(p);c.style.transitionDelay=(i*40)+'ms';g.appendChild(c);});
paintIcons(g);obs();
}
let ugcI=0;
const UGLAB=['Unboxing','Pols shot','Superclone check','Levering binnen','Van onze klant','Ambassador drop','Daily wear'];
const UGC=REVS.slice(0,6).map((f,i)=>({f,t:UGLAB[i]||'Van onze klant'}));
(()=>{$('#ugcOrb').innerHTML=`<svg viewBox="0 0 200 200" style="width:100%;height:auto">
<defs><linearGradient id="og" x1="0" y1="0" x2="1" y2="1">
<stop offset="0" stop-color="#F2E4C3"/><stop offset="1" stop-color="#A8864A"/></linearGradient></defs>
<g fill="none" stroke="url(#og)" stroke-width="1.4">
<circle cx="100" cy="108" r="66"/><circle cx="100" cy="108" r="56"/><circle cx="100" cy="108" r="44" stroke-dasharray="3 7"/>
<path d="M100 42v12M100 162v12M34 108h12M154 108h12"/>
<path d="M86 30 90 14h20l4 16M86 186l4 16h20l4-16"/>
<path d="M78 22 84 27 100 12l16 15 6-5-2 12H80l-2-12Z" fill="url(#og)" stroke="none"/>
</g>
<g stroke="url(#og)" stroke-width="2.4" stroke-linecap="round">
<path d="M100 108V70"><animateTransform attributeName="transform" type="rotate" from="0 100 108" to="360 100 108" dur="60s" repeatCount="indefinite"/></path>
<path d="M100 108h30"><animateTransform attributeName="transform" type="rotate" from="0 100 108" to="360 100 108" dur="12s" repeatCount="indefinite"/></path>
</g><circle cx="100" cy="108" r="3" fill="#F2E4C3"/></svg>`;})();
function renderUgc(){
const st=$('#ugc');st.innerHTML='';
UGC.forEach((u,i)=>{const c=el('div','ugc-card',
`<img src="${AU(u.f)}" alt="${u.t}" loading="lazy">
<div class="ply"><i>${svg('play')}</i></div>
<div class="cap"><b>${u.t}</b><span>De Klokjesman</span></div>`);
c.onclick=()=>{ugcI=i;layoutUgc();};st.appendChild(c);});
layoutUgc();
}
function layoutUgc(){
const k=[...$('#ugc').children],n=k.length,step=window.innerWidth<700?58:116;
k.forEach((c,i)=>{let o=i-ugcI;if(o>n/2)o-=n;if(o<-n/2)o+=n;const a=Math.abs(o);
c.style.transform=`translate(-50%,-50%) translateX(${o*step}px) scale(${Math.max(1-a*.13,.5)}) rotateY(${o*-9}deg)`;
c.style.zIndex=50-a;c.style.opacity=a>2?0:1-a*.16;
c.style.filter=a?`brightness(${1-a*.16})`:'none';});
$('#ugcT').textContent=UGC[ugcI].t;
}
function ugcGo(d){ugcI=(ugcI+d+UGC.length)%UGC.length;layoutUgc();}
addEventListener('resize',()=>{layoutUgc();});
(()=>{const g=$('#rvGrid');
REVS.slice(0,6).forEach(f=>{
g.appendChild(el('div','rvc rv',
`<div class="rvc-h"><span class="rvc-av">DK</span>
<div><b>De Klokjesman</b><span>Online</span></div>
<span class="rvc-wa">${svg('wa')}</span></div>
<div class="rvc-s"><img src="${AU(f)}" alt="Klantgesprek" loading="lazy"></div>
<div class="rvc-f"><span class="st">★★★★★</span>
<span class="vf">${svg('check')}Geverifieerd</span></div>`));
});})();
(()=>{
const hero=P.find(p=>p.tier==='superclone')||P[0];
$('#anaImg').innerHTML=`<img src="${AU(hero.f)}" alt="Detail">`+
ANATOMY.map(([t,d,pos],i)=>`<button class="hot" style="top:${pos.t};left:${pos.l}" data-h="${i}">${i+1}</button>`).join('');
$('#anaList').innerHTML=ANATOMY.map(([t,d],i)=>
`<div class="ana-i${i===0?' on':''}" data-a="${i}"><div class="t"><span class="n">${i+1}</span><b>${t}</b></div><p>${d}</p></div>`).join('');
const sel=i=>{$$('.ana-i').forEach(n=>n.classList.toggle('on',+n.dataset.a===i));
$$('.hot').forEach(n=>n.classList.toggle('on',+n.dataset.h===i));};
$$('.hot').forEach(b=>b.onclick=()=>sel(+b.dataset.h));
$$('.ana-i').forEach(b=>b.onclick=()=>sel(+b.dataset.a));
sel(0);
})();
let F={tier:'all',model:[],size:[],max:1400,pill:null},shown=12;
function inPill(p){
if(F.pill==='dames')return!!p.dames;
if(F.pill==='sale')return!!p.sale;
if(F.pill==='doos')return p.tag==='doos';
if(F.pill==='brillen')return p.tag==='bril';
if(F.pill==='2e')return false;
return p.tag==='watch';
}
function colPool(){
return P.filter(p=>inPill(p)&&(F.tier==='all'||p.tier===F.tier)
&&(!F.model.length||F.model.includes(p.model))
&&(!F.size.length||F.size.includes(p.mm))
&&((saleOf(p)||p.price)<=F.max));
}
function facet(key){
const base=P.filter(p=>inPill(p)&&(F.tier==='all'||p.tier===F.tier)),m={};
base.forEach(p=>{const v=p[key];if(v)m[v]=(m[v]||0)+1;});
return Object.entries(m).sort((a,b)=>key==='mm'?a[0]-b[0]:b[1]-a[1]);
}
function renderColFilters(){
const tw=$('#colTier');tw.innerHTML='';
[['all','Alles'],['superclone','Superclone'],['bp','BP Swiss Made'],['aaa','AAA+']].forEach(([k,l])=>{
const s=F.tier;F.tier=k;const n=colPool().length;F.tier=s;
const b=el('button','ch'+(F.tier===k?' on':''),`${l}<b>${n}</b>`);
b.onclick=()=>{F.tier=k;F.model=[];F.size=[];shown=12;renderColFilters();renderCol();};tw.appendChild(b);});
const pw=$('#colPills');pw.innerHTML='';
[[null,'Horloges'],['dames','Dames'],['doos','Dozen'],['brillen','Brillen'],['2e','2e hands'],['sale','Sale']].forEach(([k,l])=>{
const b=el('button','chs'+(F.pill===k?' on':''),l);
b.onclick=()=>{F.pill=k;F.model=[];F.size=[];shown=12;renderColFilters();renderCol();};pw.appendChild(b);});
[['#fModel','model','model'],['#fSize','mm','size']].forEach(([sel,key,fk])=>{
const w=$(sel);w.innerHTML='';
const f=facet(key);
if(!f.length){w.innerHTML='<span style="font-size:11.5px;color:var(--ink3)">Niet beschikbaar</span>';return;}
f.forEach(([v,c])=>{
const b=el('button','chs'+(F[fk].includes(v)?' on':''),`${v}${key==='mm'?' mm':''} <b style="opacity:.5">${c}</b>`);
b.onclick=()=>{const a=F[fk],i=a.indexOf(v);i<0?a.push(v):a.splice(i,1);shown=12;renderColFilters();renderCol();};
w.appendChild(b);});});
const n=F.model.length+F.size.length+(F.max<1400?1:0),fc=$('#fCount');
fc.style.display=n?'inline-block':'none';fc.textContent=n;
}
function priceIn(){F.max=+$('#fPrice').value;$('#fPriceV').textContent=eur(F.max);shown=12;renderCol();renderColFilters();}
function clearF(){F.model=[];F.size=[];F.max=1400;$('#fPrice').value=1400;$('#fPriceV').textContent='€1.400';shown=12;renderColFilters();renderCol();}
function tgDrawer(){$('#drawer').classList.toggle('on');}
function renderQcards(){
const w=$('#qcards');w.innerHTML='';
['superclone','bp','aaa'].forEach(k=>{
const t=T[k],n=P.filter(p=>p.tier===k).length,c=el('div','qcard');
c.style.cssText=`--qc:${t.c}`;
c.innerHTML=`<img src="${AU("ed_"+k+".webp")}" alt="${t.n}" loading="lazy">
<div class="qcard-b"><h4>${t.n}</h4><p>${t.sub} · ${n} modellen</p>
<div class="row"><b>Vanaf ${eur(t.from)}</b><span>Bekijk</span></div></div>`;
c.onclick=()=>{F.pill=null;F.tier=k;shown=12;renderColFilters();renderCol();
window.scrollTo({top:$('.fbar').offsetTop-70,behavior:'smooth'});};
w.appendChild(c);});
}
function renderBanner(){
const b=BANNERS[F.pill||F.tier]||BANNERS.all;
const img=['superclone','bp','aaa'].includes(F.tier)?'ed_'+F.tier+'.webp':
F.pill==='doos'?(P.find(p=>p.tag==='doos')||P[0]).f:
F.pill==='brillen'?(P.find(p=>p.tag==='bril')||P[0]).f:
F.pill==='dames'?(P.find(p=>p.dames)||P[0]).f:'hero.webp';
$('#colBanner').innerHTML=`<div class="cban"><div class="cban-img"><img src="${AU(img)}" alt=""></div>
<div class="cban-glow"></div>
<div class="cban-b"><span class="eyebrow">${b.e}</span><h4>${b.t}</h4>
<div class="cban-u">${b.u.map(([i,t])=>`<div>${svg(i)}${t}</div>`).join('')}</div></div></div>`;
}
function renderCol(){
let l=colPool();const s=$('#sortSel').value;
if(s==='asc')l.sort((a,b)=>(saleOf(a)||a.price)-(saleOf(b)||b.price));
else if(s==='desc')l.sort((a,b)=>(saleOf(b)||b.price)-(saleOf(a)||a.price));
else if(s==='az')l.sort((a,b)=>a.name.localeCompare(b.name));
else l.sort((a,b)=>b.best-a.best);
$('#colCount').textContent=l.length;
const tn={all:'Alle horloges',superclone:'Superclone',bp:'BP Swiss Made',aaa:'AAA+'};
const pn={dames:'Dames',doos:'Rolex dozen',brillen:'Brillen','2e':'2e hands',sale:'Sale'};
$('#colTitle').textContent=F.pill?pn[F.pill]:tn[F.tier];
$('#colSub').textContent=F.pill==='2e'?'Binnenkort beschikbaar. Vraag naar de actuele voorraad via WhatsApp.':`${l.length} producten beschikbaar.`;
renderBanner();
const g=$('#colGrid');g.innerHTML='';
if(!l.length){g.appendChild(el('div','empty','Geen producten gevonden.<br><button class="chs" style="margin-top:12px" onclick="clearF()">Wis filters</button>'));
$('#moreBtn').style.display='none';paintIcons($('#colBanner'));return;}
l.slice(0,shown).forEach((p,i)=>{const c=card(p);c.style.transitionDelay=(Math.min(i,12)*30)+'ms';g.appendChild(c);});
$('#moreBtn').style.display=l.length>shown?'inline-flex':'none';
$('#moreBtn').textContent=`Meer laden (${l.length-shown})`;
paintIcons();obs();
}
function showMore(){shown+=12;renderCol();}
let pdCur=null,upsSel=0;
const BOXES=P.filter(p=>p.tag==='doos').sort((a,b)=>a.price-b.price);
function openPd(p){
pdCur=p;upsSel=0;closeMega();
const t=T[p.tier],nm=short(p.name),col=t?t.c:'#67655F';
$('#pdName').textContent=nm;$('#pdCrumbName').textContent=nm;
$('#pdCrumbCat').textContent=t?t.n:'Accessoires';
$('#pdCrumbCat').onclick=()=>{F.pill=p.tag==='watch'?null:(p.tag==='doos'?'doos':'brillen');
F.tier=t?p.tier:'all';shown=12;go('col');};
const tier=$('#pdTier');
tier.innerHTML=svg(t?t.icon:'tag')+(t?t.n:(p.tag==='doos'?'Accessoire':'Eyewear'));
tier.style.cssText=`background:${t?t.bg:'var(--surf2)'};color:${col}`;
const gb=$('#pdGalBadge');gb.textContent=t?t.n:'Accessoire';
gb.classList.toggle('gr',!t);gb.style.background=t?col:'';
$('#pdClaim').textContent=t?t.claim:'Maak je horloge compleet met dit accessoire.';
const sp=saleOf(p);
$('#pdPrice').textContent=eur(sp||p.price);
$('#pdWas').style.display=sp?'inline':'none';$('#pdWas').textContent=eur(p.price);
$('#pdOff').style.display=sp?'inline-block':'none';
$('#pdImg').src=AU(p.f);
$('#pdRev').textContent=`4.9 · ${60+(p.id%90)} reviews`;
const th=$('#pdTh');th.innerHTML='';
[p,...P.filter(x=>x.tier===p.tier&&x.id!==p.id).slice(0,3)].forEach((x,i)=>{
const d=el('div',i===0?'on':'',`<img src="${AU(x.f)}" alt="" loading="lazy">`);
d.onclick=()=>{$('#pdImg').src=AU(x.f);[...th.children].forEach(c=>c.classList.remove('on'));d.classList.add('on');};
th.appendChild(d);});
const ow=$('#upsOpts');ow.innerHTML='';
const opts=[{n:'Alleen het horloge',s:'In onze eigen verpakking',p:0,img:p.f,box:null}];
BOXES.forEach(b=>{const prem=b.name.includes('Premium');
opts.push({n:prem?'Met premium Rolex-doos':'Met standaard Rolex-doos',
s:prem?'Houten doos, boekjes, hoesje en kaart':'Presentatiedoos met kussen en hoesje',
p:b.price,img:b.f,box:b});});
opts.forEach((o,i)=>{
const d=el('div','uopt'+(i===0?' on':''));
d.innerHTML=`<span class="urad"><i></i></span>
<img class="uimg" src="${AU(o.img)}" alt="" loading="lazy">
<span class="utxt"><b>${o.n}</b><span>${o.s}</span>
${o.box?`<span class="ulook">${svg('eye')}Bekijk deze doos</span>`:''}</span>
<span class="uprice ${o.p?'':'free'}">${o.p?'+ '+eur(o.p):'Inbegrepen'}</span>`;
d.onclick=()=>{upsSel=i;[...ow.children].forEach(c=>c.classList.remove('on'));d.classList.add('on');pdTotal();};
const look=d.querySelector('.ulook');
if(look)look.onclick=e=>{e.stopPropagation();boxModal(o.box);};
ow.appendChild(d);});
pdTotal();
$('#pdUsp').innerHTML=[['truck','Vandaag besteld, morgen in huis'],['shield','100% kwaliteitsgarantie'],
['lock','Veilig betalen met iDEAL'],['refresh','14 dagen omruilgarantie']]
.map(([i,t2])=>`<div>${svg(i)}${t2}</div>`).join('');
const specs=t?[['Kwaliteit',t.n],['Model',p.model||'—'],['Diameter',p.mm?p.mm+' mm':'—'],
['Wijzerplaat',p.dial||'—'],['Glas',t.glass],['Uurwerk',t.mov],['Materiaal',t.steel],['Waterdicht',t.water]]
:[['Type',p.tag==='doos'?'Presentatiedoos':'Zonnebril'],['Merk',p.name.split('|')[0].trim()]];
$('#pdAcc').innerHTML=`
<div class="acc-i on"><button class="acc-h" onclick="accT(this)">Beschrijving<svg class="ico" data-ic2="close" style="transform:rotate(45deg)"></svg></button>
<div class="acc-c"><div class="acc-b">${t?`${t.bullets.map(x=>'• '+x).join('<br>')}<br><br><b style="color:var(--ink)">Voor wie?</b><br>${t.who}`:'Maak je aankoop compleet met dit accessoire.'}</div></div></div>
<div class="acc-i"><button class="acc-h" onclick="accT(this)">Specificaties<svg class="ico" data-ic2="close" style="transform:rotate(45deg)"></svg></button>
<div class="acc-c"><div class="acc-b"><div class="spec">${specs.map(([a,b])=>`<div><span>${a}</span><span>${b}</span></div>`).join('')}</div></div></div></div>
<div class="acc-i"><button class="acc-h" onclick="accT(this)">Verzending en retour<svg class="ico" data-ic2="close" style="transform:rotate(45deg)"></svg></button>
<div class="acc-c"><div class="acc-b">Vandaag voor 17:00 besteld, morgen in huis. Discreet en verzekerd verzonden in neutrale verpakking. Niet tevreden? Binnen 14 dagen omruilen of retourneren.</div></div></div>`;
if(t){
$('#qxTitle').textContent=`Dit krijg je bij ${t.n}`;
$('#qxLead').textContent=t.who;
$('#qxGrid').innerHTML=t.x.map(([i,b,d])=>`<div class="qx-i">${svg(i)}<b>${b}</b><p>${d}</p></div>`).join('')
+`<div class="qx-i" style="grid-column:1/-1;background:var(--chbg);border-color:var(--ch2)">
${svg('check')}<b>Alles wat je krijgt</b>
<p>${t.bullets.map(x=>'• '+x).join('<br>')}</p></div>`;
$('#qxTitle').closest('.sec').style.display='';
} else $('#qxTitle').closest('.sec').style.display='none';
$('#pdIcar').innerHTML=DELIVER.map(([i,b,d],k)=>`<div class="icar-c">
<img src="${AU((P[(p.id+k*7)%P.length]||P[0]).f)}" alt="" loading="lazy">
<div>${svg(i)}<b>${b}</b><p>${d}</p></div></div>`).join('');
const rel=$('#pdRel');rel.innerHTML='';
P.filter(x=>x.id!==p.id&&x.tag==='watch'&&(x.model===p.model||x.tier===p.tier)).slice(0,4).forEach(x=>rel.appendChild(card(x)));
go('pd');paintIcons();
}
function pdTotal(){const sp=saleOf(pdCur)||pdCur.price,add=upsSel?BOXES[upsSel-1].price:0;
$('#pdTotal').textContent=eur(sp+add);
$('#upsHint').textContent=upsSel?'Toegevoegd':'Kies je doos';}
function accT(b){b.parentElement.classList.toggle('on');}
function boxModal(b){
if(!b)return;
const prem=b.name.includes('Premium');
$('#modalHd').innerHTML=`<img src="${AU(b.f)}" alt="${b.name}">`;
$('#modalB').innerHTML=`<h3>${b.name}</h3><p class="sub">${eur(b.price)}</p>
<div class="modal-l">${(prem
?['Houten Rolex-presentatiedoos met binnenkussen','Buitendoos in de bijpassende kleur','Boekjes, garantiekaart en poetsdoekje','De volledige set zoals bij een officiële dealer']
:['Originele presentatiedoos met binnenkussen','Beschermhoesje','Zonder boekjes en buitendoos','De voordelige manier om het compleet te maken'])
.map(x=>`<div>${svg('check')}<span>${x}</span></div>`).join('')}</div>
<button class="btn btn-g btn-full btn-lg" onclick="closeModal();toast('Doos gekozen')">Kies deze doos</button>`;
$('#modal').classList.add('on');document.body.classList.add('nobody');paintIcons($('#modal'));
}
function closeModal(){$('#modal').classList.remove('on');document.body.classList.remove('nobody');}
let CART=[];
function addCart(){
const sp=saleOf(pdCur)||pdCur.price;
CART.push({n:short(pdCur.name),f:pdCur.f,p:sp,s:T[pdCur.tier]?T[pdCur.tier].n:'Accessoire'});
if(upsSel){const b=BOXES[upsSel-1];CART.push({n:b.name,f:b.f,p:b.price,s:'Accessoire'});}
renderCart();openCart();toast('Toegevoegd aan winkelwagen');
}
function renderCart(){
$('#cc').textContent=CART.length;
const b=$('#cartBody');
if(!CART.length){b.innerHTML='<div class="cart-empty">Je winkelwagen is nog leeg.</div>';$('#cartTot').textContent='€0';return;}
const hasBox=CART.some(c=>c.s==='Accessoire'&&c.n.includes('Doos'));
b.innerHTML=CART.map(c=>`<div class="cart-row"><img src="${AU(c.f)}" alt="">
<div class="t"><b>${c.n}</b><span>${c.s}</span></div><span class="p">${eur(c.p)}</span></div>`).join('')
+(hasBox?'':`<div class="cart-up"><span class="eyebrow">Maak het compleet</span>
${BOXES.map((x,i)=>`<div class="cart-uprow"><img src="${AU(x.f)}" alt="">
<div class="t"><b>${x.name.includes('Premium')?'Premium Rolex-doos':'Standaard Rolex-doos'}</b>
<span>${x.name.includes('Premium')?'Houten doos, boekjes en kaart':'Presentatiedoos met hoesje'}</span></div>
<button class="cart-add" onclick="addBox(${i})">+ ${eur(x.price)}</button></div>`).join('')}</div>`);
$('#cartTot').textContent=eur(CART.reduce((s,c)=>s+c.p,0));
}
function addBox(i){const b=BOXES[i];CART.push({n:b.name,f:b.f,p:b.price,s:'Accessoire'});renderCart();toast('Doos toegevoegd');}
function openCart(){renderCart();$('#cartEl').classList.add('on');$('#cartScrim').classList.add('on');document.body.classList.add('nobody');}
function closeCart(){$('#cartEl').classList.remove('on');$('#cartScrim').classList.remove('on');document.body.classList.remove('nobody');}
$('#cartScrim').onclick=closeCart;
(()=>{
const w=$('#qpick');
['superclone','bp','aaa'].forEach(k=>{
const t=T[k],c=el('button','qpick-c');c.style.cssText=`--qc:${t.c}`;
c.innerHTML=`<img src="${AU("ed_"+k+".webp")}" alt="${t.n}" loading="lazy">
<div class="qpick-b"><h4>${t.n}</h4><p>${t.claim}</p>
<span class="go">Lees de volledige uitleg ${svg('arrow')}</span></div>`;
c.onclick=()=>tierModal(k);w.appendChild(c);});
const tb=$('#cmpTbl');
tb.innerHTML=`<thead><tr><th>Kenmerk</th><th>Superclone</th><th>BP Swiss Made</th><th>AAA+</th></tr></thead>
<tbody>${MATRIX.map(([lab,m])=>`<tr><td>${lab}</td><td>${mk(m.superclone)}</td><td>${mk(m.bp)}</td><td>${mk(m.aaa)}</td></tr>`).join('')}</tbody>`;
$('#infoFeat').innerHTML=[
['glow','Glow in the dark','Alle horloges hebben oplichtende wijzers, zodat je ook in het donker altijd de tijd kunt aflezen.'],
['drop','Waterdicht','Elk horloge is waterdicht en daardoor perfect bestand tegen regen en spatten.'],
['shield','Gecontroleerd','Onze horloges worden met de grootste zorg vervaardigd en voor verzending gecontroleerd.']
].map(([i,b,d])=>`<div class="qx-i">${svg(i)}<b>${b}</b><p>${d}</p></div>`).join('');
})();
function tierModal(k){
const t=T[k];
$('#modalHd').innerHTML=`<img src="${AU("ed_"+k+".webp")}" alt="${t.n}">`;
$('#modalB').innerHTML=`<h3>${t.n}</h3><p class="sub">Vanaf ${eur(t.from)} · ${t.sub}</p>
<p style="font-size:13px;color:var(--ink2);line-height:1.7;margin-bottom:16px">${t.claim}</p>
<div class="modal-l">${t.bullets.map(x=>`<div>${svg('check')}<span>${x}</span></div>`).join('')}</div>
<div class="spec" style="margin-bottom:18px">
${[['Glas',t.glass],['Uurwerk',t.mov],['Materiaal',t.steel],['Waterdicht',t.water]]
.map(([a,b])=>`<div><span>${a}</span><span>${b}</span></div>`).join('')}</div>
<p style="font-size:12.5px;color:var(--ink2);line-height:1.6;margin-bottom:18px"><b style="color:var(--ink)">Voor wie?</b><br>${t.who}</p>
<button class="btn btn-g btn-full btn-lg" onclick="closeModal();go('col','${k}')">Shop ${t.n}</button>`;
$('#modal').classList.add('on');document.body.classList.add('nobody');paintIcons($('#modal'));
}
$('#ftUsp').innerHTML=[
['truck','Morgen in huis','Vandaag voor 17:00 besteld, de volgende dag geleverd.'],
['shield','Kwaliteitsgarantie','Elk horloge wordt voor verzending volledig nagekeken.'],
['chat','Persoonlijk advies','Twijfel je? We denken via WhatsApp met je mee.'],
['box','Discreet verpakt','Neutrale verpakking, aan de buitenkant zie je niets.']
].map(([i,b,d])=>`<div class="ft-u">${svg(i)}<div><b>${b}</b><span>${d}</span></div></div>`).join('');
function go(page,tier){
closeMega();
$$('.pg').forEach(p=>p.classList.remove('on'));
document.getElementById({home:'pg-home',col:'pg-col',pd:'pg-pd',info:'pg-info'}[page]).classList.add('on');
if(page==='col'){
if(tier!==undefined){
if(['dames','sale','doos','brillen','2e'].includes(tier)){F.pill=tier;F.tier='all';}
else{F.pill=null;F.tier=tier||'all';}
F.model=[];F.size=[];shown=12;
}
renderQcards();renderColFilters();renderCol();
}
window.scrollTo({top:0,behavior:'instant'});
paintIcons();obs();
}
addEventListener('scroll',()=>$('#hd').classList.toggle('sc',scrollY>8));
let IO;
function obs(){
IO=IO||new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');IO.unobserve(e.target);}}),
{threshold:.06,rootMargin:'0px 0px -30px'});
$$('.rv:not(.in)').forEach(e=>IO.observe(e));
}
$$('[data-cnt]').forEach(n=>{
new IntersectionObserver(es=>es.forEach(e=>{
if(!e.isIntersecting||n._d)return;n._d=1;
const tgt=+n.dataset.cnt,suf=n.dataset.suf||'+';let c=0;const st=tgt/55;
const iv=setInterval(()=>{c+=st;if(c>=tgt){c=tgt;clearInterval(iv);}n.textContent=Math.floor(c).toLocaleString('nl-NL')+suf;},16);
}),{threshold:.5}).observe(n);
});
paintIcons();
renderBsFilters();renderBs();renderUgc();carInit('qcar');renderCart();obs();
addEventListener('keydown',e=>{if(e.key==='Escape'){closeModal();closeCart();closeMega();}});