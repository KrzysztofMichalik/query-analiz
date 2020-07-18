# Uruchomienie programu
* Pobierz  repozytorium, 
* Następnie w konsoli uruchom polecenie composer install
* Skonfiguruj plik .env zgodnie ze swoim środowiskiem i utworzoną bazą danych
* Uruchom polecenie php artisan key:generate
* Uruchom polecenie php artisan migrate:fresh
* Uruchom polecenie php artisan serve


# Wnioski

W celu zbadania wydajności systemu przy dużej ilości produktów. Wygenerowałem 10.000 wpisów w tabeli products oraz 15.000 wpisów w tabeli categories. ( Niespójność w językach nazewnictwa w bazie danych wynika z standardów jakich używa Laravel - modele przyjmują tabele w liczbie mnogiej swoich nazw.)

Do wygenerowania testowej bazy, posłużyłem się wbudowaną w Laravel biblioteką php faker, klasami factory oraz seedami dostepnymi w katalogu database.

Poniżej przedstawiam wyniki kwerendy EXPLAIN ANALYZE wykonanej na zapytaniu:
"SELECT p.nazwa FROM categories as outer_categories JOIN products as p ON outer_categories.id_produktu = p.id WHERE outer_categories.nazwa = 'Trampki' AND 
(SELECT COUNT(id_produktu) FROM categories as inner_categories WHERE inner_categories.id_produktu = outer_categories.id_produktu AND nazwa='Outlet') = 0;

* -> Nested loop inner join  (cost=2032.51 rows=1488) (actual time=14.872..28347.626 rows=2677 loops=1)
* -> Filter: ((outer_categories.nazwa = 'Trampki') and ((select #2) = 0))  (cost=1511.85 rows=1488) (actual time=14.855..28338.616 rows=2677 loops=1)
* -> Table scan on outer_categories  (cost=1511.85 rows=14876) (actual time=0.027..8.354 rows=15000 loops=1)
* -> Select #2 (subquery in condition; dependent)
* -> Aggregate: count(inner_categories.id_produktu)  (actual time=7.301..7.301 rows=1 loops=3879)
* -> Filter: ((inner_categories.id_produktu = outer_categories.id_produktu) and (inner_categories.nazwa = 'Outlet'))  (cost=1377.97 rows=149) (actual time=6.107..7.300 rows=0 loops=3879)
* -> Table scan on inner_categories  (cost=1377.97 rows=14876) (actual time=0.002..6.329 rows=15000 loops=3879)
* -> Single-row index lookup on p using PRIMARY (id=outer_categories.id_produktu)  (cost=0.25 rows=1) (actual time=0.003..0.003 rows=1 loops=2677)

Poniżej skolei przedstawione są wyniki kwerendy EXPLAIN ANALYZE tej samej kwerendy jednak na bazie danych z założonym dodanym indeksem "nazwa_kategorii" na kolumne categories.nazwa

* -> Nested loop inner join  (cost=1818.30 rows=3879) (actual time=6.515..16104.773 rows=2677 loops=1)
* -> Filter: ((select #2) = 0)  (cost=460.65 rows=3879) (actual time=6.509..16098.969 rows=2677 loops=1)
* -> Index lookup on outer_categories using nazwa_kategorii (nazwa='Trampki')  (cost=460.65 rows=3879) (actual time=0.032..6.105 rows=3879 loops=1)
* -> Select #2 (subquery in condition; dependent)
* -> Aggregate: count(inner_categories.id_produktu)  (actual time=4.147..4.147 rows=1 loops=3879)
* -> Filter: (inner_categories.id_produktu = outer_categories.id_produktu)  (cost=109.90 rows=372) (actual time=3.463..4.147 rows=0 loops=3879)
* -> Index lookup on inner_categories using nazwa_kategorii (nazwa='Outlet')  (cost=109.90 rows=3715) (actual time=0.003..3.842 rows=3715 loops=3879)
* -> Single-row index lookup on p using PRIMARY (id=outer_categories.id_produktu)  (cost=0.25 rows=1) (actual time=0.002..0.002 rows=1 loops=2677)

Wnioski można przedstawić na wybranych operacjach: 
Operacja Join: 
Czas trwania zgjapytania skrócił się z 14.87sek do 6.51sek, koszt jednostek obliczeniowych z 2032.51 zmniejszył się do 1818.30

Operacja wyszukiwana kategorii Trampki
Użycie indeksu, parametry w stosunku do pierwszego zapytania różnią się diametralnie. Wystarczy spojrzeć obecny cost 460.65 było 1511, obecny time 0.032 było 14.85

Już po tej krótkiej analizie widać, że indeksy to jest rozwiązanie pozwalające usprawnić działanie systemu. 
