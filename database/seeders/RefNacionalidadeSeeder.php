<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefNacionalidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iplocal = config('sistema.ipHost');

        $insert = [
            ['id' => 1, 'nome' => 'Argentino(a)', 'sigla' => 'ARG', 'pais' => 'Argentina', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 2, 'nome' => 'Boliviano(a)', 'sigla' => 'BOL', 'pais' => 'Bolívia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 3, 'nome' => 'Brasileiro(a)', 'sigla' => 'BRA', 'pais' => 'Brasil', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 4, 'nome' => 'Chileno(a)', 'sigla' => 'CHL', 'pais' => 'Chile', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 5, 'nome' => 'Colombiano(a)', 'sigla' => 'COL', 'pais' => 'Colômbia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 6, 'nome' => 'Equatoriano(a)', 'sigla' => 'ECU', 'pais' => 'Equador', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 7, 'nome' => 'Guianense', 'sigla' => 'GUY', 'pais' => 'Guiana', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 8, 'nome' => 'Guianense Frances(a)', 'sigla' => 'GUF', 'pais' => 'Guiana Francesa', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 9, 'nome' => 'Paraguaio(a)', 'sigla' => 'PRY', 'pais' => 'Paraguai', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 10, 'nome' => 'Peruano(a)', 'sigla' => 'PER', 'pais' => 'Peru', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 11, 'nome' => 'Surinamês(a)', 'sigla' => 'SUR', 'pais' => 'Suriname', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 12, 'nome' => 'Uruguaio(a)', 'sigla' => 'URY', 'pais' => 'Uruguai', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 13, 'nome' => 'Venezuelano(a)', 'sigla' => 'VEN', 'pais' => 'Venezuela', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 14, 'nome' => 'Canadense', 'sigla' => 'CAN', 'pais' => 'Canadá', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 15, 'nome' => 'Estadunidense', 'sigla' => 'USA', 'pais' => 'Estados Unidos', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 16, 'nome' => 'Mexicano(a)', 'sigla' => 'MEX', 'pais' => 'México', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 17, 'nome' => 'Antiguano(a)', 'sigla' => 'ATG', 'pais' => 'Antígua e Barbuda', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 18, 'nome' => 'Bahamense', 'sigla' => 'BHS', 'pais' => 'Bahamas', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 19, 'nome' => 'Barbadiano(a)', 'sigla' => 'BRB', 'pais' => 'Barbados', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 20, 'nome' => 'Belizenho(a)', 'sigla' => 'BLZ', 'pais' => 'Belize', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 21, 'nome' => 'Bermudense', 'sigla' => 'BMU', 'pais' => 'Bermudas', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 22, 'nome' => 'Costa-riquenho(a)', 'sigla' => 'CRC', 'pais' => 'Costa Rica', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 23, 'nome' => 'Cubano(a)', 'sigla' => 'CUB', 'pais' => 'Cuba', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 24, 'nome' => 'Dominiquense', 'sigla' => 'DMA', 'pais' => 'Dominica', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 25, 'nome' => 'Salvadorenho(a)', 'sigla' => 'SLV', 'pais' => 'El Salvador', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 26, 'nome' => 'Granadino(a)', 'sigla' => 'GRD', 'pais' => 'Granada', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 27, 'nome' => 'Guatemalteco(a)', 'sigla' => 'GTM', 'pais' => 'Guatemala', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 28, 'nome' => 'Haitiano(a)', 'sigla' => 'HTI', 'pais' => 'Haiti', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 29, 'nome' => 'Hondurenho(a)', 'sigla' => 'HND', 'pais' => 'Honduras', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 30, 'nome' => 'Jamaicano(a)', 'sigla' => 'JAM', 'pais' => 'Jamaica', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 31, 'nome' => 'Nicaraguense', 'sigla' => 'NIC', 'pais' => 'Nicarágua', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 32, 'nome' => 'Panamenho(a)', 'sigla' => 'PAN', 'pais' => 'Panamá', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 33, 'nome' => 'Porto-riquenho(a)', 'sigla' => 'PRI', 'pais' => 'Porto Rico', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 34, 'nome' => 'Dominicano(a)', 'sigla' => 'DOM', 'pais' => 'República Dominicana', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 35, 'nome' => 'Santa-lucense', 'sigla' => 'LCA', 'pais' => 'Santa Lúcia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 36, 'nome' => 'São-cristovense', 'sigla' => 'KNA', 'pais' => 'São Cristóvão e Nevis', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 37, 'nome' => 'São-vicentino(a)', 'sigla' => 'VCT', 'pais' => 'São Vicente e Granadinas', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 38, 'nome' => 'Trinitário(a)', 'sigla' => 'TTO', 'pais' => 'Trinidad e Tobago', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 39, 'nome' => 'Albanês(a)', 'sigla' => 'ALB', 'pais' => 'Albânia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 40, 'nome' => 'Alemão(a)', 'sigla' => 'DEU', 'pais' => 'Alemanha', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 41, 'nome' => 'Andorrano(a)', 'sigla' => 'AND', 'pais' => 'Andorra', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 42, 'nome' => 'Armênio(a)', 'sigla' => 'ARM', 'pais' => 'Armênia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 43, 'nome' => 'Austríaco(a)', 'sigla' => 'AUT', 'pais' => 'Áustria', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 44, 'nome' => 'Azerbaijano(a)', 'sigla' => 'AZE', 'pais' => 'Azerbaijão', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 45, 'nome' => 'Belga', 'sigla' => 'BEL', 'pais' => 'Bélgica', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 46, 'nome' => 'Bielorrusso(a)', 'sigla' => 'BLR', 'pais' => 'Bielorrússia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 47, 'nome' => 'Bósnio(a) e Herzegovino(a)', 'sigla' => 'BIH', 'pais' => 'Bósnia e Herzegovina', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 48, 'nome' => 'Búlgaro(a)', 'sigla' => 'BGR', 'pais' => 'Bulgária', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 49, 'nome' => 'Cipriota', 'sigla' => 'CYP', 'pais' => 'Chipre', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 50, 'nome' => 'Croata', 'sigla' => 'HRV', 'pais' => 'Croácia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 51, 'nome' => 'Dinamarquês(a)', 'sigla' => 'DNK', 'pais' => 'Dinamarca', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 52, 'nome' => 'Eslovaco(a)', 'sigla' => 'SVK', 'pais' => 'Eslováquia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 53, 'nome' => 'Esloveno(a)', 'sigla' => 'SVN', 'pais' => 'Eslovênia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 54, 'nome' => 'Espanhol(a)', 'sigla' => 'ESP', 'pais' => 'Espanha', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 55, 'nome' => 'Estoniano(a)', 'sigla' => 'EST', 'pais' => 'Estônia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 56, 'nome' => 'Finlandês(a)', 'sigla' => 'FIN', 'pais' => 'Finlândia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 57, 'nome' => 'Françês(a)', 'sigla' => 'FRA', 'pais' => 'França', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 58, 'nome' => 'Georgiano(a)', 'sigla' => 'GEO', 'pais' => 'Geórgia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 59, 'nome' => 'Grego(a)', 'sigla' => 'GRC', 'pais' => 'Grécia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 60, 'nome' => 'Húngaro(a)', 'sigla' => 'HUN', 'pais' => 'Hungria', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 61, 'nome' => 'Inglês(a)', 'sigla' => 'GBR', 'pais' => 'Inglaterra', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 62, 'nome' => 'Irlandês(a)', 'sigla' => 'IRL', 'pais' => 'Irlanda', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 63, 'nome' => 'Islandês(a)', 'sigla' => 'IS', 'pais' => 'Islândia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 64, 'nome' => 'Italiano(a)', 'sigla' => 'ITA', 'pais' => 'Itália', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 65, 'nome' => 'Letão(a)', 'sigla' => 'LVA', 'pais' => 'Letônia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 66, 'nome' => 'Liechtensteiniense', 'sigla' => 'LIE', 'pais' => 'Liechtenstein', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 67, 'nome' => 'Lituano(a)', 'sigla' => 'LTU', 'pais' => 'Lituânia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 68, 'nome' => 'Luxemburguês(a)', 'sigla' => 'LUX', 'pais' => 'Luxemburgo', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 69, 'nome' => 'Macedônio(a)', 'sigla' => 'MKD', 'pais' => 'Macedônia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 70, 'nome' => 'Maltês(a)', 'sigla' => 'MLT', 'pais' => 'Malta', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 71, 'nome' => 'Moldavo(a)', 'sigla' => 'MDA', 'pais' => 'Moldávia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 72, 'nome' => 'Monegasco(a)', 'sigla' => 'MCO', 'pais' => 'Mônaco', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 73, 'nome' => 'Norueguês(a)', 'sigla' => 'NOR', 'pais' => 'Noruega', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 74, 'nome' => 'Neerlandês(a)', 'sigla' => 'NLD', 'pais' => 'Países Baixos', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 75, 'nome' => 'Polonês(a)', 'sigla' => 'POL', 'pais' => 'Polônia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 76, 'nome' => 'Português(a)', 'sigla' => 'PRT', 'pais' => 'Portugal', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 77, 'nome' => 'Tcheco(a)', 'sigla' => 'CZE', 'pais' => 'República Checa', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 78, 'nome' => 'Romeno(a)', 'sigla' => 'ROU', 'pais' => 'Romênia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 79, 'nome' => 'Sanmarinense', 'sigla' => 'SMR', 'pais' => 'San Marino', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 80, 'nome' => 'Sueco(a)', 'sigla' => 'SWE', 'pais' => 'Suécia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 81, 'nome' => 'Suíço(a)', 'sigla' => 'CHE', 'pais' => 'Suíça', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 82, 'nome' => 'Ucraniano(a)', 'sigla' => 'UKR', 'pais' => 'Ucrânia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 83, 'nome' => 'Vaticano(a)', 'sigla' => 'VAT', 'pais' => 'Vaticano', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 84, 'nome' => 'África do Sul', 'sigla' => 'ZAF', 'pais' => 'Pretória', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 85, 'nome' => 'Angolano(a)', 'sigla' => 'AGO', 'pais' => 'Luanda', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 86, 'nome' => 'Argelino(a)', 'sigla' => 'DZA', 'pais' => 'Argel', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 87, 'nome' => 'Beninense', 'sigla' => 'BEN', 'pais' => 'Porto Novo', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 88, 'nome' => 'Botsuanês(a)', 'sigla' => 'BWA', 'pais' => 'Gaborone', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 89, 'nome' => 'Burquinês(a)', 'sigla' => 'BFA', 'pais' => 'Uagadugu', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 90, 'nome' => 'Burundinês(a)', 'sigla' => 'BDI', 'pais' => 'Bujumbura', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 91, 'nome' => 'Cabo-verdiano(a)', 'sigla' => 'CPV', 'pais' => 'Praia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 92, 'nome' => 'Camaronês(a)', 'sigla' => 'CMR', 'pais' => 'Iaundé', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 93, 'nome' => 'Chadiano(a)', 'sigla' => 'TCD', 'pais' => 'Ndjamena', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 94, 'nome' => 'Comorense', 'sigla' => 'COM', 'pais' => 'Moroni', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 95, 'nome' => 'Costa-marfinense', 'sigla' => 'CIV', 'pais' => 'Abidjan', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 96, 'nome' => 'Djibutiano(a)', 'sigla' => 'DJI', 'pais' => 'Djibuti', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 97, 'nome' => 'Egípcio(a)', 'sigla' => 'EGY', 'pais' => 'Cairo', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 98, 'nome' => 'Eritreiano(a)', 'sigla' => 'ERI', 'pais' => 'Asmara', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 99, 'nome' => 'Etíope', 'sigla' => 'ETH', 'pais' => 'Adis Abeba', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 100, 'nome' => 'Gabonês(a)', 'sigla' => 'GAB', 'pais' => 'Libreville', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 101, 'nome' => 'Gambiano(a)', 'sigla' => 'GMB', 'pais' => 'Banjul', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 102, 'nome' => 'Ganês(a)', 'sigla' => 'GHA', 'pais' => 'Acra', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 103, 'nome' => 'Guineense', 'sigla' => 'GIN', 'pais' => 'Conacri', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 104, 'nome' => 'Guiné-equatoriano(a)', 'sigla' => 'GNQ', 'pais' => 'Malabo', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 105, 'nome' => 'Guineense-bissauense', 'sigla' => 'GNB', 'pais' => 'Bissau', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 106, 'nome' => 'Lesotiano(a)', 'sigla' => 'LSO', 'pais' => 'Maseru', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 107, 'nome' => 'Liberiano(a)', 'sigla' => 'LBR', 'pais' => 'Monróvia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 108, 'nome' => 'Líbio(a)', 'sigla' => 'LBY', 'pais' => 'Trípoli', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 109, 'nome' => 'Madagascarense', 'sigla' => 'MDG', 'pais' => 'Antananarivo', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 110, 'nome' => 'Malauiano(a)', 'sigla' => 'MWI', 'pais' => 'Lilongue', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 111, 'nome' => 'Maliano(a)', 'sigla' => 'MLI', 'pais' => 'Bamaco', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 112, 'nome' => 'Marroquino(a)', 'sigla' => 'MAR', 'pais' => 'Rabat', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 113, 'nome' => 'Mauriciano(a)', 'sigla' => 'MUS', 'pais' => 'Port Louis', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 114, 'nome' => 'Mauritano(a)', 'sigla' => 'MRT', 'pais' => 'Nuakchott', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 115, 'nome' => 'Moçambicano(a)', 'sigla' => 'MOZ', 'pais' => 'Maputo', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 116, 'nome' => 'Namibiano(a)', 'sigla' => 'NAM', 'pais' => 'Windhoek', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 117, 'nome' => 'Nigeriano(a)', 'sigla' => 'NGA', 'pais' => 'Abuja', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 118, 'nome' => 'Queniano(a)', 'sigla' => 'KEN', 'pais' => 'Nairobi', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 119, 'nome' => 'Centro-africano(a)', 'sigla' => 'CAF', 'pais' => 'Bangui', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 120, 'nome' => 'Congolesa(o) Democrática(o)', 'sigla' => 'COD', 'pais' => 'Kinshasa', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 121, 'nome' => 'Congolesa(o)', 'sigla' => 'COG', 'pais' => 'Brazzaville', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 122, 'nome' => 'Ruandês(a)', 'sigla' => 'RWA', 'pais' => 'Kigali', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 123, 'nome' => 'São-tomense', 'sigla' => 'STP', 'pais' => 'São Tomé', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 124, 'nome' => 'Senegalês(a)', 'sigla' => 'SEN', 'pais' => 'Dacar', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 125, 'nome' => 'Serra-leonense', 'sigla' => 'SLE', 'pais' => 'Freetown', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 126, 'nome' => 'Seychellense', 'sigla' => 'SYC', 'pais' => 'Vitória', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 127, 'nome' => 'Somali(a)', 'sigla' => 'SOM', 'pais' => 'Mogadíscio', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 128, 'nome' => 'Suazi', 'sigla' => 'SWZ', 'pais' => 'Mbabane', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 129, 'nome' => 'Sudanês(a)', 'sigla' => 'SDN', 'pais' => 'Cartum', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 130, 'nome' => 'Tanzaniano(a)', 'sigla' => 'TZA', 'pais' => 'Dodoma', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 131, 'nome' => 'Togolês(a)', 'sigla' => 'TGO', 'pais' => 'Lomé', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 132, 'nome' => 'Tunisiano(a)', 'sigla' => 'TUN', 'pais' => 'Túnis', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 133, 'nome' => 'Ugandense', 'sigla' => 'UGA', 'pais' => 'Campala', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 134, 'nome' => 'Zambiano(a)', 'sigla' => 'ZMB', 'pais' => 'Lusaka', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 135, 'nome' => 'Zimbabuano(a)', 'sigla' => 'ZWE', 'pais' => 'Harare', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 136, 'nome' => 'Afegão(a)', 'sigla' => 'AFG', 'pais' => 'Cabul', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 137, 'nome' => 'Saudita', 'sigla' => 'SAU', 'pais' => 'Riad', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 138, 'nome' => 'Barenita', 'sigla' => 'BHR', 'pais' => 'Manama', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 139, 'nome' => 'Bangladeshiano(a)', 'sigla' => 'BGD', 'pais' => 'Daca', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 140, 'nome' => 'Bruneiano(a)', 'sigla' => 'BRN', 'pais' => 'Bandar Seri Begawan', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 141, 'nome' => 'Butanês(a)', 'sigla' => 'BTN', 'pais' => 'Timfú', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 142, 'nome' => 'Cambojano(a)', 'sigla' => 'KHM', 'pais' => 'Phnom Penh', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 143, 'nome' => 'Qatari', 'sigla' => 'QAT', 'pais' => 'Doha', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 144, 'nome' => 'Cazaque', 'sigla' => 'KAZ', 'pais' => 'Astana', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 145, 'nome' => 'Chinês(a)', 'sigla' => 'CHN', 'pais' => 'Pequim', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 146, 'nome' => 'Cingapuriano(a)', 'sigla' => 'SGP', 'pais' => 'Cingapura', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 147, 'nome' => 'Norte-coreano(a)', 'sigla' => 'PRK', 'pais' => 'Pyongyang', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 148, 'nome' => 'Sul-coreano(a)', 'sigla' => 'KOR', 'pais' => 'Seul', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 149, 'nome' => 'Emiradense', 'sigla' => 'ARE', 'pais' => 'Abu Dhabi', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 150, 'nome' => 'Filipino(a)', 'sigla' => 'PHL', 'pais' => 'Manila', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 151, 'nome' => 'Iemenita', 'sigla' => 'YEM', 'pais' => 'Sanaa', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 152, 'nome' => 'Indian(o)', 'sigla' => 'IND', 'pais' => 'Nova Délhi', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 153, 'nome' => 'Indonésio(a)', 'sigla' => 'IDN', 'pais' => 'Jacarta', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 154, 'nome' => 'Iraniano(a)', 'sigla' => 'IRN', 'pais' => 'Teerã', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 155, 'nome' => 'Iraquiano(a)', 'sigla' => 'IRQ', 'pais' => 'Bagdá', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 156, 'nome' => 'Israelita', 'sigla' => 'ISR', 'pais' => 'Israel', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 157, 'nome' => 'Japonês(a)', 'sigla' => 'JPN', 'pais' => 'Japão', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 158, 'nome' => 'Jordaniano(a)', 'sigla' => 'JOR', 'pais' => 'Amã', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 159, 'nome' => 'Kuwaitiano(a)', 'sigla' => 'KWT', 'pais' => 'Cidade do Kuwait', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 160, 'nome' => 'Laosiano(a)', 'sigla' => 'LAO', 'pais' => 'Vientiane', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 161, 'nome' => 'Libanês(a)', 'sigla' => 'LBN', 'pais' => 'Beirute', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 162, 'nome' => 'Malaio(a)', 'sigla' => 'MYS', 'pais' => 'Kuala Lumpur', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 163, 'nome' => 'Maldivo(a)', 'sigla' => 'MDV', 'pais' => 'Male', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 164, 'nome' => 'Mongol(a)', 'sigla' => 'MNG', 'pais' => 'Ulan Bator', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 165, 'nome' => 'Mianmarense', 'sigla' => 'MMR', 'pais' => 'Naypyidaw', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 166, 'nome' => 'Nepalês(a)', 'sigla' => 'NPL', 'pais' => 'Katmandú', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 167, 'nome' => 'Omanense', 'sigla' => 'OMN', 'pais' => 'Mascate', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 168, 'nome' => 'Paquistanês(a)', 'sigla' => 'PAK', 'pais' => 'Islamabad', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 169, 'nome' => 'Quirguiz', 'sigla' => 'KGZ', 'pais' => 'Bishkek', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 170, 'nome' => 'Russo(a)', 'sigla' => 'RUS', 'pais' => 'Moscou', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 171, 'nome' => 'Sírio(a)', 'sigla' => 'SYR', 'pais' => 'Damasco', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 172, 'nome' => 'Sri-lankês(a)', 'sigla' => 'LKA', 'pais' => 'Colombo', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 173, 'nome' => 'Tailandês(a)', 'sigla' => 'THA', 'pais' => 'Bangcoc', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 174, 'nome' => 'Taiwanês(a)', 'sigla' => 'TWN', 'pais' => 'Taipé', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 175, 'nome' => 'Tajique', 'sigla' => 'TJK', 'pais' => 'Dushanbe', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 176, 'nome' => 'Turquemeno(a)', 'sigla' => 'TKM', 'pais' => 'Ashkhabad', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 177, 'nome' => 'Turco(a)', 'sigla' => 'TUR', 'pais' => 'Ancara', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 178, 'nome' => 'Uzbeque', 'sigla' => 'UZB', 'pais' => 'Tashkent', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 179, 'nome' => 'Vietnamita', 'sigla' => 'VNM', 'pais' => 'Hanói', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 180, 'nome' => 'Australiano(a)', 'sigla' => 'AUS', 'pais' => 'Canberra', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 181, 'nome' => 'Fijiano(a)', 'sigla' => 'FJI', 'pais' => 'Suva', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 182, 'nome' => 'Marshallino(a)', 'sigla' => 'MHL', 'pais' => 'Majuro', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 183, 'nome' => 'Salomonense', 'sigla' => 'SLB', 'pais' => 'Honiara', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 184, 'nome' => 'Kiribatiano(a)', 'sigla' => 'KIR', 'pais' => 'Bairiki', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 185, 'nome' => 'Micronésio(a)', 'sigla' => 'FSM', 'pais' => 'Palikir', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 186, 'nome' => 'Nauruano(a)', 'sigla' => 'NRU', 'pais' => 'Yaren', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 187, 'nome' => 'Neozelandês(a)', 'sigla' => 'NZL', 'pais' => 'Wellington', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 188, 'nome' => 'Palauense', 'sigla' => 'PLW', 'pais' => 'Koror', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 189, 'nome' => 'Papuásio(a)-Novo Guineense', 'sigla' => 'PNG', 'pais' => 'Port Moresby', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 190, 'nome' => 'Samoano(a)', 'sigla' => 'WSM', 'pais' => 'Ápia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 191, 'nome' => 'Tonganês(a)', 'sigla' => 'TON', 'pais' => 'Nukualofa', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 192, 'nome' => 'Tuvaluano(a)', 'sigla' => 'TUV', 'pais' => 'Fongafale', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['id' => 193, 'nome' => 'Vanuatense', 'sigla' => 'VUT', 'pais' => 'Porto Vila', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
        ];
        
        DB::table('ref_nacionalidades')->insert($insert);
    }
}
