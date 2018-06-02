# Places

Biblioteca com abertura para comunicação com várias APIs afim de fornecer uma listagem de cidades, estados e países.

## Exemplo

No exemplo abaixo é obtido e impresso a lista de cidades do estado do Ceará/Brasil.

```
<?php
use Ciebit/Places/Places;

$places = new Places;
$cities = $places->addFilter(Places::COUNTRY_ID, 76)->addFilter(Places::STATE_NAME, 'Ceará')->getCities()->getIterator();
while ($cities->valid()) {
    echo $cities->current()->getName()."\n";
    $cities->next();
}

```
