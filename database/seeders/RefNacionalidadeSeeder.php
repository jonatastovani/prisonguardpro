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
            ['nome' => 'Argentino(a)', 'sigla' => 'ARG', 'pais' => 'Argentina', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Boliviano(a)', 'sigla' => 'BOL', 'pais' => 'Bolívia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Brasileiro(a)', 'sigla' => 'BRA', 'pais' => 'Brasil', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Chileno(a)', 'sigla' => 'CHL', 'pais' => 'Chile', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Colombiano(a)', 'sigla' => 'COL', 'pais' => 'Colômbia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Equatoriano(a)', 'sigla' => 'ECU', 'pais' => 'Equador', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Guianense', 'sigla' => 'GUY', 'pais' => 'Guiana', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Guianense Frances(a)', 'sigla' => 'GUF', 'pais' => 'Guiana Francesa', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Paraguaio(a)', 'sigla' => 'PRY', 'pais' => 'Paraguai', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Peruano(a)', 'sigla' => 'PER', 'pais' => 'Peru', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Surinamês(a)', 'sigla' => 'SUR', 'pais' => 'Suriname', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Uruguaio(a)', 'sigla' => 'URY', 'pais' => 'Uruguai', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Venezuelano(a)', 'sigla' => 'VEN', 'pais' => 'Venezuela', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Canadense', 'sigla' => 'CAN', 'pais' => 'Canadá', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Estadunidense', 'sigla' => 'USA', 'pais' => 'Estados Unidos', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Mexicano(a)', 'sigla' => 'MEX', 'pais' => 'México', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Antiguano(a)', 'sigla' => 'ATG', 'pais' => 'Antígua e Barbuda', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Bahamense', 'sigla' => 'BHS', 'pais' => 'Bahamas', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Barbadiano(a)', 'sigla' => 'BRB', 'pais' => 'Barbados', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Belizenho(a)', 'sigla' => 'BLZ', 'pais' => 'Belize', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Bermudense', 'sigla' => 'BMU', 'pais' => 'Bermudas', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Costa-riquenho(a)', 'sigla' => 'CRC', 'pais' => 'Costa Rica', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Cubano(a)', 'sigla' => 'CUB', 'pais' => 'Cuba', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Dominiquense', 'sigla' => 'DMA', 'pais' => 'Dominica', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Salvadorenho(a)', 'sigla' => 'SLV', 'pais' => 'El Salvador', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Granadino(a)', 'sigla' => 'GRD', 'pais' => 'Granada', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Guatemalteco(a)', 'sigla' => 'GTM', 'pais' => 'Guatemala', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Haitiano(a)', 'sigla' => 'HTI', 'pais' => 'Haiti', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Hondurenho(a)', 'sigla' => 'HND', 'pais' => 'Honduras', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Jamaicano(a)', 'sigla' => 'JAM', 'pais' => 'Jamaica', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Nicaraguense', 'sigla' => 'NIC', 'pais' => 'Nicarágua', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Panamenho(a)', 'sigla' => 'PAN', 'pais' => 'Panamá', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Porto-riquenho(a)', 'sigla' => 'PRI', 'pais' => 'Porto Rico', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Dominicano(a)', 'sigla' => 'DOM', 'pais' => 'República Dominicana', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Santa-lucense', 'sigla' => 'LCA', 'pais' => 'Santa Lúcia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'São-cristovense', 'sigla' => 'KNA', 'pais' => 'São Cristóvão e Nevis', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'São-vicentino(a)', 'sigla' => 'VCT', 'pais' => 'São Vicente e Granadinas', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Trinitário(a)', 'sigla' => 'TTO', 'pais' => 'Trinidad e Tobago', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Albanês(a)', 'sigla' => 'ALB', 'pais' => 'Albânia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Alemão(a)', 'sigla' => 'DEU', 'pais' => 'Alemanha', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Andorrano(a)', 'sigla' => 'AND', 'pais' => 'Andorra', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Armênio(a)', 'sigla' => 'ARM', 'pais' => 'Armênia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Austríaco(a)', 'sigla' => 'AUT', 'pais' => 'Áustria', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Azerbaijano(a)', 'sigla' => 'AZE', 'pais' => 'Azerbaijão', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Belga', 'sigla' => 'BEL', 'pais' => 'Bélgica', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Bielorrusso(a)', 'sigla' => 'BLR', 'pais' => 'Bielorrússia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Bósnio(a) e Herzegovino(a)', 'sigla' => 'BIH', 'pais' => 'Bósnia e Herzegovina', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Búlgaro(a)', 'sigla' => 'BGR', 'pais' => 'Bulgária', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Cipriota', 'sigla' => 'CYP', 'pais' => 'Chipre', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Croata', 'sigla' => 'HRV', 'pais' => 'Croácia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Dinamarquês(a)', 'sigla' => 'DNK', 'pais' => 'Dinamarca', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Eslovaco(a)', 'sigla' => 'SVK', 'pais' => 'Eslováquia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Esloveno(a)', 'sigla' => 'SVN', 'pais' => 'Eslovênia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Espanhol(a)', 'sigla' => 'ESP', 'pais' => 'Espanha', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Estoniano(a)', 'sigla' => 'EST', 'pais' => 'Estônia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Finlandês(a)', 'sigla' => 'FIN', 'pais' => 'Finlândia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Françês(a)', 'sigla' => 'FRA', 'pais' => 'França', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Georgiano(a)', 'sigla' => 'GEO', 'pais' => 'Geórgia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Grego(a)', 'sigla' => 'GRC', 'pais' => 'Grécia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Húngaro(a)', 'sigla' => 'HUN', 'pais' => 'Hungria', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Inglês(a)', 'sigla' => 'GBR', 'pais' => 'Inglaterra', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Irlandês(a)', 'sigla' => 'IRL', 'pais' => 'Irlanda', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Islandês(a)', 'sigla' => 'IS', 'pais' => 'Islândia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Italiano(a)', 'sigla' => 'ITA', 'pais' => 'Itália', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Letão(a)', 'sigla' => 'LVA', 'pais' => 'Letônia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Liechtensteiniense', 'sigla' => 'LIE', 'pais' => 'Liechtenstein', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Lituano(a)', 'sigla' => 'LTU', 'pais' => 'Lituânia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Luxemburguês(a)', 'sigla' => 'LUX', 'pais' => 'Luxemburgo', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Macedônio(a)', 'sigla' => 'MKD', 'pais' => 'Macedônia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Maltês(a)', 'sigla' => 'MLT', 'pais' => 'Malta', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Moldavo(a)', 'sigla' => 'MDA', 'pais' => 'Moldávia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Monegasco(a)', 'sigla' => 'MCO', 'pais' => 'Mônaco', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Norueguês(a)', 'sigla' => 'NOR', 'pais' => 'Noruega', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Neerlandês(a)', 'sigla' => 'NLD', 'pais' => 'Países Baixos', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Polonês(a)', 'sigla' => 'POL', 'pais' => 'Polônia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Português(a)', 'sigla' => 'PRT', 'pais' => 'Portugal', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Tcheco(a)', 'sigla' => 'CZE', 'pais' => 'República Checa', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Romeno(a)', 'sigla' => 'ROU', 'pais' => 'Romênia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Sanmarinense', 'sigla' => 'SMR', 'pais' => 'San Marino', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Sueco(a)', 'sigla' => 'SWE', 'pais' => 'Suécia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Suíço(a)', 'sigla' => 'CHE', 'pais' => 'Suíça', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Ucraniano(a)', 'sigla' => 'UKR', 'pais' => 'Ucrânia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Vaticano(a)', 'sigla' => 'VAT', 'pais' => 'Vaticano', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'África do Sul', 'sigla' => 'ZAF', 'pais' => 'Pretória', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Angolano(a)', 'sigla' => 'AGO', 'pais' => 'Luanda', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Argelino(a)', 'sigla' => 'DZA', 'pais' => 'Argel', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Beninense', 'sigla' => 'BEN', 'pais' => 'Porto Novo', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Botsuanês(a)', 'sigla' => 'BWA', 'pais' => 'Gaborone', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Burquinês(a)', 'sigla' => 'BFA', 'pais' => 'Uagadugu', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Burundinês(a)', 'sigla' => 'BDI', 'pais' => 'Bujumbura', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Cabo-verdiano(a)', 'sigla' => 'CPV', 'pais' => 'Praia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Camaronês(a)', 'sigla' => 'CMR', 'pais' => 'Iaundé', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Chadiano(a)', 'sigla' => 'TCD', 'pais' => 'Ndjamena', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Comorense', 'sigla' => 'COM', 'pais' => 'Moroni', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Costa-marfinense', 'sigla' => 'CIV', 'pais' => 'Abidjan', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Djibutiano(a)', 'sigla' => 'DJI', 'pais' => 'Djibuti', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Egípcio(a)', 'sigla' => 'EGY', 'pais' => 'Cairo', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Eritreiano(a)', 'sigla' => 'ERI', 'pais' => 'Asmara', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Etíope', 'sigla' => 'ETH', 'pais' => 'Adis Abeba', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Gabonês(a)', 'sigla' => 'GAB', 'pais' => 'Libreville', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Gambiano(a)', 'sigla' => 'GMB', 'pais' => 'Banjul', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Ganês(a)', 'sigla' => 'GHA', 'pais' => 'Acra', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Guineense', 'sigla' => 'GIN', 'pais' => 'Conacri', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Guiné-equatoriano(a)', 'sigla' => 'GNQ', 'pais' => 'Malabo', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Guineense-bissauense', 'sigla' => 'GNB', 'pais' => 'Bissau', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Lesotiano(a)', 'sigla' => 'LSO', 'pais' => 'Maseru', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Liberiano(a)', 'sigla' => 'LBR', 'pais' => 'Monróvia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Líbio(a)', 'sigla' => 'LBY', 'pais' => 'Trípoli', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Madagascarense', 'sigla' => 'MDG', 'pais' => 'Antananarivo', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Malauiano(a)', 'sigla' => 'MWI', 'pais' => 'Lilongue', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Maliano(a)', 'sigla' => 'MLI', 'pais' => 'Bamaco', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Marroquino(a)', 'sigla' => 'MAR', 'pais' => 'Rabat', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Mauriciano(a)', 'sigla' => 'MUS', 'pais' => 'Port Louis', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Mauritano(a)', 'sigla' => 'MRT', 'pais' => 'Nuakchott', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Moçambicano(a)', 'sigla' => 'MOZ', 'pais' => 'Maputo', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Namibiano(a)', 'sigla' => 'NAM', 'pais' => 'Windhoek', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Nigeriano(a)', 'sigla' => 'NGA', 'pais' => 'Abuja', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Queniano(a)', 'sigla' => 'KEN', 'pais' => 'Nairobi', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Centro-africano(a)', 'sigla' => 'CAF', 'pais' => 'Bangui', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Congolesa(o) Democrática(o)', 'sigla' => 'COD', 'pais' => 'Kinshasa', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Congolesa(o)', 'sigla' => 'COG', 'pais' => 'Brazzaville', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Ruandês(a)', 'sigla' => 'RWA', 'pais' => 'Kigali', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'São-tomense', 'sigla' => 'STP', 'pais' => 'São Tomé', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Senegalês(a)', 'sigla' => 'SEN', 'pais' => 'Dacar', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Serra-leonense', 'sigla' => 'SLE', 'pais' => 'Freetown', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Seychellense', 'sigla' => 'SYC', 'pais' => 'Vitória', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Somali(a)', 'sigla' => 'SOM', 'pais' => 'Mogadíscio', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Suazi', 'sigla' => 'SWZ', 'pais' => 'Mbabane', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Sudanês(a)', 'sigla' => 'SDN', 'pais' => 'Cartum', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Tanzaniano(a)', 'sigla' => 'TZA', 'pais' => 'Dodoma', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Togolês(a)', 'sigla' => 'TGO', 'pais' => 'Lomé', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Tunisiano(a)', 'sigla' => 'TUN', 'pais' => 'Túnis', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Ugandense', 'sigla' => 'UGA', 'pais' => 'Campala', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Zambiano(a)', 'sigla' => 'ZMB', 'pais' => 'Lusaka', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Zimbabuano(a)', 'sigla' => 'ZWE', 'pais' => 'Harare', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Afegão(a)', 'sigla' => 'AFG', 'pais' => 'Cabul', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Saudita', 'sigla' => 'SAU', 'pais' => 'Riad', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Barenita', 'sigla' => 'BHR', 'pais' => 'Manama', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Bangladeshiano(a)', 'sigla' => 'BGD', 'pais' => 'Daca', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Bruneiano(a)', 'sigla' => 'BRN', 'pais' => 'Bandar Seri Begawan', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Butanês(a)', 'sigla' => 'BTN', 'pais' => 'Timfú', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Cambojano(a)', 'sigla' => 'KHM', 'pais' => 'Phnom Penh', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Qatari', 'sigla' => 'QAT', 'pais' => 'Doha', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Cazaque', 'sigla' => 'KAZ', 'pais' => 'Astana', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Chinês(a)', 'sigla' => 'CHN', 'pais' => 'Pequim', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Cingapuriano(a)', 'sigla' => 'SGP', 'pais' => 'Cingapura', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Norte-coreano(a)', 'sigla' => 'PRK', 'pais' => 'Pyongyang', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Sul-coreano(a)', 'sigla' => 'KOR', 'pais' => 'Seul', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Emiradense', 'sigla' => 'ARE', 'pais' => 'Abu Dhabi', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Filipino(a)', 'sigla' => 'PHL', 'pais' => 'Manila', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Iemenita', 'sigla' => 'YEM', 'pais' => 'Sanaa', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Indian(o)', 'sigla' => 'IND', 'pais' => 'Nova Délhi', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Indonésio(a)', 'sigla' => 'IDN', 'pais' => 'Jacarta', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Iraniano(a)', 'sigla' => 'IRN', 'pais' => 'Teerã', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Iraquiano(a)', 'sigla' => 'IRQ', 'pais' => 'Bagdá', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Israelita', 'sigla' => 'ISR', 'pais' => 'Telaviv', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Japonês(a)', 'sigla' => 'JPN', 'pais' => 'Tóquio', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Jordaniano(a)', 'sigla' => 'JOR', 'pais' => 'Amã', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Kuwaitiano(a)', 'sigla' => 'KWT', 'pais' => 'Cidade do Kuwait', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Laosiano(a)', 'sigla' => 'LAO', 'pais' => 'Vientiane', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Libanês(a)', 'sigla' => 'LBN', 'pais' => 'Beirute', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Malaio(a)', 'sigla' => 'MYS', 'pais' => 'Kuala Lumpur', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Maldivo(a)', 'sigla' => 'MDV', 'pais' => 'Male', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Mongol(a)', 'sigla' => 'MNG', 'pais' => 'Ulan Bator', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Mianmarense', 'sigla' => 'MMR', 'pais' => 'Naypyidaw', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Nepalês(a)', 'sigla' => 'NPL', 'pais' => 'Katmandú', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Omanense', 'sigla' => 'OMN', 'pais' => 'Mascate', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Paquistanês(a)', 'sigla' => 'PAK', 'pais' => 'Islamabad', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Quirguiz', 'sigla' => 'KGZ', 'pais' => 'Bishkek', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Russo(a)', 'sigla' => 'RUS', 'pais' => 'Moscou', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Sírio(a)', 'sigla' => 'SYR', 'pais' => 'Damasco', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Sri-lankês(a)', 'sigla' => 'LKA', 'pais' => 'Colombo', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Tailandês(a)', 'sigla' => 'THA', 'pais' => 'Bangcoc', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Taiwanês(a)', 'sigla' => 'TWN', 'pais' => 'Taipé', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Tajique', 'sigla' => 'TJK', 'pais' => 'Dushanbe', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Turquemeno(a)', 'sigla' => 'TKM', 'pais' => 'Ashkhabad', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Turco(a)', 'sigla' => 'TUR', 'pais' => 'Ancara', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Uzbeque', 'sigla' => 'UZB', 'pais' => 'Tashkent', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Vietnamita', 'sigla' => 'VNM', 'pais' => 'Hanói', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Australiano(a)', 'sigla' => 'AUS', 'pais' => 'Canberra', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Fijiano(a)', 'sigla' => 'FJI', 'pais' => 'Suva', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Marshallino(a)', 'sigla' => 'MHL', 'pais' => 'Majuro', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Salomonense', 'sigla' => 'SLB', 'pais' => 'Honiara', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Kiribatiano(a)', 'sigla' => 'KIR', 'pais' => 'Bairiki', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Micronésio(a)', 'sigla' => 'FSM', 'pais' => 'Palikir', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Nauruano(a)', 'sigla' => 'NRU', 'pais' => 'Yaren', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Neozelandês(a)', 'sigla' => 'NZL', 'pais' => 'Wellington', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Palauense', 'sigla' => 'PLW', 'pais' => 'Koror', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Papuásio(a)-Novo Guineense', 'sigla' => 'PNG', 'pais' => 'Port Moresby', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Samoano(a)', 'sigla' => 'WSM', 'pais' => 'Ápia', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Tonganês(a)', 'sigla' => 'TON', 'pais' => 'Nukualofa', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Tuvaluano(a)', 'sigla' => 'TUV', 'pais' => 'Fongafale', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
            ['nome' => 'Vanuatense', 'sigla' => 'VUT', 'pais' => 'Porto Vila', 'id_user_created' => 1, 'ip_created' => $iplocal, 'created_at' => now()],
        ];
        
        DB::table('ref_nacionalidades')->insert($insert);
    }
}
