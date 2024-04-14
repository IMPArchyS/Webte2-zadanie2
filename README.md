# WEBTE2 Zadanie č.2 LS 2023/2024
## 1. Všeobecné pokyny 
- Zadania by mali byť optimalizované pre posledné verzie Google Chrome a Firefox
- Zadania sa odovzdávajú vždy do polnoci dňa, ktorý predchádza cvičeniu.
- Neskoré odovzdanie zadania sa trestá znížením počtu bodov.
- Pre každé zadanie si vytvorte novú databázu (t.j. nemiešajte spolu databázy z rôznych úloh, pokiaľ to v úlohe nebude vyslovene uvedené).
- Je potrebné odovzdať:<br>
  (1) zazipované zadanie aj s nadradeným adresárom (t.j. nie 7zip, RAR, ani žiadny iný formát). Názov ZIP archívu musí byť v tvare idStudenta_priezvisko_z3.zip. Úvodný skript nazvite index.php. Konfiguračný súbor nazvite config.php a umiestnite ho do rovnakého adresára, v ktorom máte umiestnený index.php. Do poznámky priložte linku na funkčné zadanie umiestnené na vašom pridelenom serveri 147.175.105.XX (nodeXX.webte.fei.stuba.sk).<br>
Príklad štruktúry odovzdaného zadania:<br>
12345_mrkvicka_z2.zip:<br>
12345_mrkvicka_z2/<br>
index.php<br>
config.php<br>
12345_mrkvicka_z2.sql (v prípade práce s databázou)<br>
12345_mrkvicka_z2.doc (len v prípade odovzdanej technickej správy)<br>
(2) súbor docker compose<br>
(3) technickú správu<br>
(4) adresu umiestnenia na školskom serveri (uvedte do poznámky v MS Teams).<br>
- V prípade zistenia plagiátorstva je treba počítať s následkami.
## 2. Zadanie cvičenia:
Úloha sa zameriava na získavanie údajov z iných zdrojov pomocou CURL (iné spôsoby nebudú hodnotené) a vytvorenie API na ich ďalšie poskytovanie.

Vytvorte webovú službu, ktorá umožní získať z AIS STU informácie o vašom rozvrhu a o témach bakalárskych a diplomových prác.

### 2.1. Rozvrh:
1. Na základe kliknutia na tlačidlo na stránke získajte z AISu pomocou CURL údaje o vašom osobnom rozvrhu a tieto informácie uložte vhodným spôsobom do databázy. Na stránku umiestnite aj tlačidlo, ktoré umožní vymazať všetky údaje z databázy.
2. Vytvorte API nad uloženými údajmi, kde implementujete metódy, ktoré umožňujú:<br>
  a. Získať všetky rozvrhové akcie, kde bude uvedený deň v týždni, typ rozvrhovej akcie (prednáška, cvičenie, iné), názov predmetu, miestnosť. Odporúča sa využiť json.<br>
  b. Vytvoriť novú rozvrhovú akciu, napríklad konzultáciu alebo telesnú výchovu.<br>
  c. Vymazať vybranú rozvrhovú akciu.<br>
  d. Modifikovať niektorý z údajov v uloženej rozvrhovej akcii.<br>
3. Na webovej stránke zobrazte uložený rozvrh, pričom umožnite vytvorenie, vymazanie a úpravu potrebnej rozvrhovej akcie. Na realizáciu tohoto bodu využívajte vami vytvorené API.

### 2.2. Záverečné práce:
1. Pomocou CURL čerpajte údaje zo stránky https://is.stuba.sk/pracoviste/prehled_temat.pl?lang=sk;pracoviste=642, kde posledné číslo predstavuje kód pracoviska. Všetky kódy pracovísk sú uvedené v tabuľke.
   
| Pracovisko                                            | Kód |
|-------------------------------------------------------|-----|
| Ustav automobilovej mechatroniky                     | 642 |
| Ustav elektroenergetiky a aplikovanej elektrotechniky| 548 |
| Ustav elektroniky a fotoniky                         | 549 |
| Ustav elektrotechniky                                | 550 |
| Ustav informatiky a matematiky                       | 816 |
| Ustav jadrového a fyzikálneho inžinierstva           | 817 |
| Ustav multimediálnych informačných a komunikačných   | 818 |
| technológií                                          |     |
| Ustav robotiky a kybernetiky                         | 356 |

2. Bez ukladania údajov do databázy vytvorte API, ktoré umožní zobraziť voľné (ešte neobsadené) témy záverečných prác pre vybraný ústav a vybraný typ štúdia. Ak je téma vypísaná pre dvoch študentov a zatiaľ je na nej zapísaný iba jeden študent, považuje sa za voľnú. V odpovedi na požiadavku vráťte názov témy, vedúceho práce, garantujúce pracovisko, program, zameranie a abstrakt témy.
3. Na základe vytvoreného endpointu zobrazte na webovej stránke tabuľku s voľnými témami pre zadaný ústav a zvolený typ projektu (diplomový, bakalársky), pričom pre každý projekt uveďte jeho názov, meno školiteľa a študijný program. Umožnite zotriedenie všetkých stĺpcov a filtrovanie údajov vzhľadom na školiteľa a študijný program. Po kliknutí na názov projektu zobrazte jeho anotáciu (abstrakt).
4. Na stránke umožnite aj filtrovanie tém vzhľadom na reťazec obsiahnutý v názve témy alebo v jej abstrakte.

Webovú službu vytvorte pomocou jednej z nasledujúcich alternatív: XML-RPC, JSON-RPC, SOAP alebo REST. Pri zadaní sa bude kontrolovať, či funkcionalita stránky je robena naozaj pomocou zvolenej webovej služby. Pri REST službe si dajte záležať na tom, aby boli skutočne dodržané zásady RESTU.

Nezabudnite správne odchytávať prípadné chyby a posielať správny chybový stav (200, 400, atď.).

Na tomu určenej podstránke popíšte API vytvorenej služby (napr. v štandarde OpenAPI). V prípade, že vytvoríte WSDL dokument pre SOAP, stačí, keď namiesto ručného popisu API iba vizualizujete jednotlivé metódy služby pomocou nejakého voľne dostupného wsdl viewera. Kto však má záujem, môže ručne popísať API aj v tomto prípade. (Pre dokumentáciu API môžete použiť aj knižnicu, vďaka ktorej
