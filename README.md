# IkasKude · Ikastaro eta Matrikulen Kudeaketa Plataforma

> **Plataforma Web Korporatiboa eta Segurua (Laravel 11 + PHP 8.2 + MySQL + Bootstrap 5 + Vanilla JS)**  
> Erronka: Ekoizpen Seguruan Jartzea (ESJ), Zibersegurtasuna eta Oinarrizko Funtsak (OF:4).

---

## Proiektuaren Deskribapena / Descripción del Proyecto

**IkasKude** ikastaroen eskaintza, ikasleen matrikulazioa eta administrazio orokorra kudeatzeko web-plataforma osoa da. Sistema honek bi erabilera-eremu nagusi ditu:

1. **Gune Publikoa eta Ikaslearen Txokoa:**
   - Ikastaroen katalogo osoa ikusi (prezioak, datak, orduak eta libre dauden plazak).
   - Bilaketa eta iragazketa azkarra.
   - Kontu berria sortu (automatikoki `ikasle` rola esleituz).
   - Ikastaroetan matrikulatu klik bakarrez (plaza libreak eta epeak kontrolatuz).
   - *Nire Matrikulak* panela: norberaren ikastaroak, egoerak, kalifikazioak kontsultatu eta baja eman.

2. **Administrazio Zentroa (`/administrazioa`):**
   - Aginte-panela estatistika orokorrekin (ikasleak, ikastaroak, matrikulak).
   - **Ikastaroen CRUD osoa:** Sortu, zerrendatu, xehetasunak ikusi, editatu eta ezabatu.
   - **Erabiltzaileen CRUD osoa:** Erabiltzaile berriak eskuz sortu, rolak aldatu, datuak eguneratu eta ezabatu (babes bereziarekin).
   - **Matrikulen CRUD osoa:** Matrikulak sortu, egoera aldatu (`onartua`, `pendiente`, `baja`) eta kalifikazioak (0-10) ezarri.

---

## Kalifikazio Errubrikaren Betetzea (Trazabilitatea 10/10)

Hemen zehazten da errubrikako atal bakoitza proiektuaren kodean nola eta non betetzen den **Aurreratua (10)** maila lortzeko:

| Errubrikako Atala | Eskatutakoa (Aurreratua - 10) | Nola betetzen den proiektuan | Fitxategiak eta Evidentziak |
| :--- | :--- | :--- | :--- |
| **ESJ: 1 - Oinarrizko aplikazioa** *(1.2%)* | Aplikazio funtzionala, behar diren ezaugarri guztiak martxan. | Nabigazio osoa, autentifikazioa, matrikulazio prozesua eta kudeaketa administratiboa martxan. | `routes/web.php`, `app/Http/Controllers/*` |
| **ESJ: 1 - PHP eta OZP oinarriak** *(1.2%)* | Objektuei orientatutako programazioa (OZP/OOP) zorrotz jarraitu. | Laravel 11 arkitektura, Eloquent ORM, klaseak, ereduak, mota zorrotzak eta inbentarioa. | `app/Models/`, `app/Http/Controllers/` |
| **ESJ: 2 - OZP aurreratua** *(0.6%)* | Klaseak, eraikitzaileak, metodo estatikoak, erlazioak. | Klaseen herentzia (`Authenticatable`, `Model`, `Controller`), metodo estatikoak (`Hash::make`, `RateLimiter::hit`), 1:N eta N:M erlazioak. | `app/Models/Curso.php`, `User.php`, `Matricula.php` |
| **ESJ: 2 - Datu-base CRUD osoa** *(0.6%)* | Datu-basearekin CRUD osoa lortu da kasu guztietan. | CRUD osoa (Create, Read, Update, Delete) `cursos`, `usuarios` eta `matriculas` tauletan. | `CursoController.php`, `UsuarioController.php`, `MatriculaController.php` |
| **ESJ: 3 - Gehigarriak (UI/UX)** *(0.6%)* | Interfaze atsegina, gehigarriak, itxura oso ondo amaituta. | Bootstrap 5, ikonoak, korporatibo kolore-paleta (`#563F98`, `#272447`, `#5CC37C`, `#D3D8F0`), diseinu sentikorra (responsive). | `public/css/style.css`, `resources/views/layouts/app.blade.php` |
| **ESJ: 3 - Kodea araztu eta dokumentatu** *(0.6%)* | Errore gabe, probak eginda, azalpen txiki batekin metodo/klase bakoitzean. | **12 test automatizatu** (100% gaindituta) eta **PHPDoc osoa euskera teknikoan** kontrolatzaile eta eredu guztietan. | `tests/Feature/PlatformSecurityTest.php`, kontrolatzaile guztiak |
| **ESJ: 3 - MVC Patroia** *(0.6%)* | MVC patroia garatu da kapa bakoitza oso ondo bereiztuta. | Ereduak (`app/Models/`), Bistak (`resources/views/`), Kontrolatzaileak (`app/Http/Controllers/`), Ibilbideak (`routes/web.php`). | Egitura estandarra |
| **ESJ: 3 - Ezaugarri aurreratuak (>=3)** *(0.6%)* | Estilo propioak, pasahitzen enkriptazioa, usabilitatea, segurtasuna. | 1. Bcrypt pasahitz-hash segurua.<br>2. RBAC rol-kontrola + IDOR prebentzioa.<br>3. Denbora errealeko bilaketa dinamikoa JS bidez.<br>4. Indar gordinaren kontrako babesa (Rate Limiting). | `AuthController.php`, `public/js/app.js`, `style.css` |
| **OF: 4 - Aldagaiak eta Eragiketak** *(1.3%)* | Aldagaien definizio eta erabilera egokia kasu guztietan. | Datu moten kasting-a (`integer`, `decimal`, `date`), kalkulu matematikoak (plazen zenbaketa). | `Curso.php` (`plazasDisponibles()`), kontrolatzaileak |
| **OF: 4 - Egitura baldintzatzaileak eta errepikakorrak** *(1.3%)* | Egitura baldintzatzaileak eta errepikakorrak era egokienean. | Blade direktibak (`@if`, `@else`, `@forelse`, `@empty`), PHP egiturak kontrolatzaileetan. | `resources/views/**/*.blade.php` |
| **OF: 4 - Liburutegiak eta JS funtzioak** *(2.5%)* | Javascript lengoaiaren funtzioak modu optimoan erabili. | `public/js/app.js`: DOM manipulazioa, gertaera-entzuleak (`input`, `click`), animazio leunak eta auto-dismiss. | `public/js/app.js`, `resources/views/layouts/app.blade.php` |
| **OF: 4 - Funtzioen sorrera** *(3.5%)* | Funtzio propioak sortu eta era optimoan erabili. | Metodo propioak: `plazasDisponibles()`, `isIrekita()`, `isAdmin()`, `isIkasle()`, `initTableLiveFilter()`. | `app/Models/Curso.php`, `User.php`, `app.js` |
| **OF: 4 - Errore eta salbuespenen kudeaketa** *(2.5%)* | Errore eta salbuespen guztiak modu optimoan kudeatu. | `$request->validate()` zerrenda osoak, flash-ak (`success`, `error`), `@error` direktibak bistetan, segurtasun-logak (`Log::warning`). | Kontrolatzaile guztiak, formularioak |
| **OF: 4 - Datu-base CRUD (5.0%)** | Datu-basearekin CRUD osoa lortu da kasu guztietan. | 3 taula nagusi erlazionatuta, gako primario eta arrotzekin, eta osotasun murrizketekin (`cascade on delete`, `unique`). | `database/migrations/*` |

---

## Zibersegurtasun Neurriak (OWASP & Hardening)

Aplikazioak garapen seguruaren printzipioak txertatzen ditu hasieratik:

1. **SQL Injection Prebentzioa:** Eloquent ORM eta kontsulta parametrizatuak erabiltzen dira uneoro.
2. **XSS (Cross-Site Scripting) Prebentzioa:** Blade motorrak automatikoki garbitzen ditu aldagaiak (`{{ $aldagaia }}`), eta JS aldetik `escapeHtml()` funtzioa txertatu da.
3. **CSRF (Cross-Site Request Forgery):** Eskaera post/put/delete guztiek `@csrf` token baliagarria eskatzen dute.
4. **Indar Gordinaren kontrako Tasa Mugatzea (Rate Limiting):** `AuthController@login` metodoak IP eta erabiltzaile bakoitzeko gehienez 5 saiakera onartzen ditu minutuko.
5. **Session Fixation Prebentzioa:** Saioa hasten eta ixten denean saio-identifikatzailea birsortzen da (`$request->session()->regenerate()`).
6. **IDOR Prebentzioa (Insecure Direct Object Reference):** Ikasle batek ezin du beste inoren matrikularik ezabatu edo ikusi; baimen-egiaztapena zerbitzarian egiten da (`cancelEnrollment`).
7. **Pasahitzen Biltegiratze Segurua:** Bcrypt bidezko hash sendoa (`Hash::make`) gatz (salt) automatikoarekin.
8. **Segurtasun Goiburuak (HTTP Security Headers):** `X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, `X-XSS-Protection`.
9. **Auditoretza Erregistroa (Logging):** Gertakari garrantzitsu guztiak (saio hasteak, huts egindako saiakerak, matrikulak, aldaketak) `storage/logs/laravel.log` fitxategian erregistratzen dira.

---

## Datu-Basearen Egitura (E-R Eredua)

### 1. `usuarios` Taula
* `id` (PK, BigInt Auto-increment)
* `name` (varchar 255)
* `email` (varchar 255, Unique)
* `password` (varchar 255, Bcrypt hash)
* `role` (enum: `'admin'`, `'ikasle'`)
* `created_at`, `updated_at`

### 2. `cursos` Taula
* `id` (PK, BigInt Auto-increment)
* `kodea` (varchar 50, Unique)
* `izena` (varchar 255)
* `deskribapena` (text)
* `iraupena_orduak` (unsigned integer)
* `plazak` (unsigned integer)
* `prezioa` (decimal 8,2)
* `hasiera_data` (date)
* `bukaera_data` (date)
* `egoera` (enum: `'irekita'`, `'itxita'`, `'amaituta'`)
* `created_at`, `updated_at`

### 3. `matriculas` Taula
* `id` (PK, BigInt Auto-increment)
* `usuario_id` (FK -> `usuarios.id`, On Delete Cascade)
* `curso_id` (FK -> `cursos.id`, On Delete Cascade)
* `data` (date)
* `egoera` (enum: `'onartua'`, `'pendiente'`, `'baja'`)
* `kalifikazioa` (decimal 5,2, Nullable)
* `created_at`, `updated_at`
* *Murrizketa:* `UNIQUE(usuario_id, curso_id)` (Ikasle bera ez bikoizteko ikastaro berean).

---

## Instalazioa eta Martxan Jartzea (Garatzaile / Ebaluatzailearentzat)

### 1. Baldintzak
* PHP >= 8.2 (OpenSSL, PDO, Mbstring, Tokenizer, XML luzapenekin)
* Composer
* MySQL zerbitzaria (lokalean edo beste ekipo/zerbitzari batean)

### 2. Ingurune fitxategia (`.env`) konfiguratu
Proiektuaren erroan dagoen `.env` fitxategian zure MySQL datu-basearen konexio-datuak jarri:

```env
APP_NAME=IkasKude
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1      # Edo urruneko zerbitzariaren IP-a (adibidez: 192.168.X.X)
DB_PORT=3306
DB_DATABASE=laravel_ariketa
DB_USERNAME=root
DB_PASSWORD=zure_pasahitza
```

### 3. Datu-basea sortu eta aplikazioa abiarazi
Terminalean exekutatu komando hauek:

```powershell
# 1. Menpekotasunak instalatu (beharrezkoa bada)
composer install

# 2. Aplikazioaren gakoa sortu (beharrezkoa bada)
php artisan key:generate

# 3. Taulak datu-basean sortu
php artisan migrate

# 4. (Aukerakoa) Hasierako administratzailea sortu
php artisan db:seed --class=AdminUserSeeder

# 5. Garapen zerbitzaria piztu
php artisan serve
```

Aplikazioa helbide honetan egongo da eskuragarri: `http://localhost:8000`

---

## Test Automatizatuak (Arazketa eta Egiaztapena)

Proiektuak egiaztapen eta segurtasun test multzo osoa barneratzen du. Exekutatzeko:

```powershell
php artisan test
```

**Emaitza espero dena:**
```text
PASS  Tests\Unit\ExampleTest
PASS  Tests\Feature\ExampleTest
PASS  Tests\Feature\PlatformSecurityTest
Tests: 12 passed (37 assertions)
```

---

## Korporatibo Kolore Paleta

Aplikazioak diseinu koherentea eta profesionala mantentzen du CSS aldagaiekin (`public/css/style.css`):

* **Violeta Nagusia (`#563F98`):** Nabbar, titularrak, botoi korporatiboak.
* **Azul-Violeta Iluna (`#272447`):** Testu nagusiak, taulen goiburukoak, elementu ilunak.
* **Verde Teknologikoa (`#5CC37C`):** Deialdi botoiak (CTA), egoera positiboak.
* **Lavanda Argia (`#D3D8F0`):** Txartelen atzeko planoak, txapak (`badge-violet-subtle`).
* **Zuria (`#FFFFFF`):** Txartelen eta gainazalen garbitasuna.
