# LV4 Filmovi Web

Projekt je prerada LV3 rješenja u PHP + MySQL aplikaciju prema LV4 predlošku.

## Funkcionalnosti

- registracija i prijava korisnika
- hashiranje lozinki
- PHP sesije
- filtriranje filmova SQL upitima
- osobna videoteka za prijavljene korisnike
- upozorenje za film s niskom prosječnom ocjenom
- galerija slika
- ocjenjivanje slika od 1 do 5
- ažuriranje postojeće ocjene ako isti korisnik ponovno ocijeni istu sliku
- prikaz prosječne ocjene
- komentari uz slike
- admin dashboard
- upload JPG/PNG slika do 5MB
- prepared statements radi zaštite od SQL injectiona

## Pokretanje lokalno preko XAMPP-a

1. Kopiraj mapu `LV4_Filmovi_Web` u `xampp/htdocs/`.
2. Pokreni Apache i MySQL u XAMPP Control Panelu.
3. Otvori phpMyAdmin: `http://localhost/phpmyadmin`.
4. Importaj datoteku `sql/lv4_baza.sql`.
5. Otvori aplikaciju: `http://localhost/LV4_Filmovi_Web/index.php`.

## Test računi

Admin:
- email: `admin@example.com`
- lozinka: `admin123`

Korisnik:
- email: `korisnik@example.com`
- lozinka: `user123`

## Glavne datoteke

- `index.php` - prikaz i filtriranje filmova, dodavanje u osobnu videoteku
- `my_movies.php` - pregled i uklanjanje filmova iz osobne videoteke
- `gallery.php` - galerija postera i prosječne ocjene
- `photo.php` - detalj slike, unos ocjene i komentara
- `myratings.php` - sve ocjene trenutno prijavljenog korisnika
- `dashboard.php` - admin pregled, upload i brisanje slika
- `includes/db.php` - spajanje na bazu
- `includes/auth.php` - funkcije za provjeru prijave i role
- `includes/functions.php` - pomoćne funkcije
- `sql/lv4_baza.sql` - baza i početni podaci
