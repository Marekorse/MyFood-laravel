<?php
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    return [
        'accepted'             => ':Attribute musí byť akceptovaný.',
        'accepted_if'          => ':Attribute musí byť akceptované ak :other je :value.',
        'active_url'           => ':Attribute má neplatnú URL adresu.',
        'after'                => ':Attribute musí byť dátum po :date.',
        'after_or_equal'       => ':Attribute musí byť dátum po alebo presne :date.',
        'alpha'                => ':Attribute môže obsahovať len písmená.',
        'alpha_dash'           => ':Attribute môže obsahovať len písmená, čísla a pomlčky.',
        'alpha_num'            => ':Attribute môže obsahovať len písmená, čísla.',
        'array'                => ':Attribute musí byť pole.',
        'before'               => ':Attribute musí byť dátum pred :date.',
        'before_or_equal'      => ':Attribute musí byť dátum pred alebo presne :date.',
        'between'              => [
            'array'   => ':Attribute musí mať rozsah :min - :max prvkov.',
            'file'    => ':Attribute musí mať rozsah :min - :max kilobajtov.',
            'numeric' => ':Attribute musí mať rozsah :min - :max.',
            'string'  => ':Attribute musí mať rozsah :min - :max znakov.',
        ],
        'boolean'              => ':Attribute musí byť pravda alebo nepravda.',
        'confirmed'            => ':Attribute konfirmácia sa nezhoduje.',
        'current_password'     => 'Heslo je nesprávne.',
        'date'                 => ':Attribute má neplatný dátum.',
        'date_equals'          => ':Attribute musí byť dátum rovnajúci sa :date.',
        'date_format'          => ':Attribute sa nezhoduje s formátom :format.',
        'declined'             => ':Attribute musí byť zamietnuté.',
        'declined_if'          => ':Attribute musí byť zamietnuté ak :other je :value.',
        'different'            => ':Attribute a :other musia byť odlišné.',
        'digits'               => ':Attribute musí mať :digits číslic.',
        'digits_between'       => ':Attribute musí mať rozsah :min až :max číslic.',
        'dimensions'           => ':Attribute má neplatné rozmery obrázku.',
        'distinct'             => ':Attribute je duplicitný.',
        'email'                => ':Attribute má neplatný formát.',
        'ends_with'            => ':attribute musí obsahovať jednú z týchto hodnôt: :values.',
        'enum'                 => 'The selected :attribute is invalid.',
        'exists'               => 'označený :attribute je neplatný.',
        'file'                 => ':Attribute musí byť súbor.',
        'filled'               => ':Attribute je požadované.',
        'gt'                   => [
            'array'   => ':Attribute musí mať viac prvkov ako :value.',
            'file'    => ':Attribute musí mať viac kilobajtov ako :value.',
            'numeric' => 'Hodnota :attribute musí byť väčšia ako :value.',
            'string'  => ':Attribute musí mať viac znakov ako :value.',
        ],
        'gte'                  => [
            'array'   => ':Attribute musí mať rovnaký alebo väčší počet prvkov ako :value.',
            'file'    => ':Attribute musí mať rovnaký alebo väčší počet kilobajtov ako :value.',
            'numeric' => 'Hodnota :attribute musí byť väčšia alebo rovná ako :value.',
            'string'  => ':Attribute musí mať rovnaký alebo väčší počet znakov ako :value.',
        ],
        'image'                => ':Attribute musí byť obrázok.',
        'in'                   => 'označený :attribute je neplatný.',
        'in_array'             => ':Attribute sa nenachádza v :other.',
        'integer'              => ':Attribute musí byť celé číslo.',
        'ip'                   => ':Attribute musí byť platná IP adresa.',
        'ipv4'                 => ':Attribute musí byť platná IPv4 adresa.',
        'ipv6'                 => ':Attribute musí byť platná IPv6 adresa.',
        'json'                 => ':Attribute musí byť platný JSON reťazec.',
        'lt'                   => [
            'array'   => ':Attribute musí mať menej prvkov ako :value.',
            'file'    => ':Attribute musí mať menej kilobajtov ako :value.',
            'numeric' => 'Hodnota :attribute musí byť menšia ako :value.',
            'string'  => ':Attribute musí mať menej znakov ako :value.',
        ],
        'lte'                  => [
            'array'   => ':Attribute musí mať rovnaký alebo menší počet prvkov ako :value.',
            'file'    => ':Attribute musí mať rovnaký alebo menší počet kilobajtov ako :value.',
            'numeric' => 'Hodnota :attribute musí byť menšia alebo rovná ako :value.',
            'string'  => ':Attribute musí mať rovnaký alebo menší počet znakov ako :value.',
        ],
        'mac_address'          => 'The :attribute must be a valid MAC address.',
        'max'                  => [
            'array'   => ':Attribute nemôže mať viac ako :max prvkov.',
            'file'    => ':Attribute nemôže byť väčší ako :max kilobajtov.',
            'numeric' => ':Attribute nemôže byť väčší ako :max.',
            'string'  => ':Attribute nemôže byť väčší ako :max znakov.',
        ],
        'mimes'                => ':Attribute musí byť súbor s koncovkou: :values.',
        'mimetypes'            => ':Attribute musí byť súbor s koncovkou: :values.',
        'min'                  => [
            'array'   => ':Attribute musí mať aspoň :min prvkov.',
            'file'    => ':Attribute musí mať aspoň :min kilobajtov.',
            'numeric' => ':Attribute musí mať aspoň :min.',
            'string'  => ':Attribute musí mať aspoň :min znakov.',
        ],
        'multiple_of'          => ':attribute musí byť násobkom :value',
        'not_in'               => 'označený :attribute je neplatný.',
        'not_regex'            => ':Attribute má neplatný formát.',
        'numeric'              => ':Attribute musí byť číslo.',
        'password'             => 'Heslo nie je správne',
        'present'              => ':Attribute musí byť odoslaný.',
        'prohibited'           => ':Attribute je zakázané.',
        'prohibited_if'        => ':Attribute je zakázané, keď je :other :value.',
        'prohibited_unless'    => ':Attribute je zakázané, pokiaľ :other nie je v :values.',
        'prohibits'            => ':Attribute zakazuje, aby bolo :other povolené.',
        'regex'                => ':Attribute má neplatný formát.',
        'required'             => ':Attribute je požadované.',
        'required_array_keys'  => 'The :attribute field must contain entries for: :values.',
        'required_if'          => ':Attribute je požadované keď :other je :value.',
        'required_unless'      => ':Attribute je požadované, okrem prípadu keď :other je v :values.',
        'required_with'        => ':Attribute je požadované keď :values je prítomné.',
        'required_with_all'    => ':Attribute je požadované ak :values je nastavené.',
        'required_without'     => ':Attribute je požadované keď :values nie je prítomné.',
        'required_without_all' => ':Attribute je požadované ak žiadne z :values nie je nastavené.',
        'same'                 => ':Attribute a :other sa musia zhodovať.',
        'size'                 => [
            'array'   => ':Attribute musí obsahovať :size prvkov.',
            'file'    => ':Attribute musí mať :size kilobajtov.',
            'numeric' => ':Attribute musí byť :size.',
            'string'  => ':Attribute musí mať :size znakov.',
        ],
        'starts_with'          => ':Attribute musí začínať niektorou z hodnôt: :values',
        'string'               => ':Attribute musí byť reťazec znakov.',
        'timezone'             => ':Attribute musí byť platné časové pásmo.',
        'unique'               => ':Attribute už existuje.',
        'uploaded'             => 'Nepodarilo sa nahrať :attribute.',
        'url'                  => ':Attribute musí mať formát URL.',
        'uuid'                 => ':Attribute musí byť platné UUID.',
        'custom'               => [
            'attribute-name' => [
                'rule-name' => 'custom-message',
            ],
        ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [

        //recipe

        'title' => [
            'required' => 'Prosim vyplnte náízov receptu.',
            'unique' => 'Názov receptu už existuje.',
            'max'=> 'Maximálna dĺžka textu je 255 znakov'
        ],
        'file' => [
            'required' => 'Prosím pridajte obrázok.',
            'image' => 'Priložený súbor nieje podporovaný',
            'mimes' => 'Obrázok músi byt typu jpeg,png,jpg,svg ',
            'max' => 'Maximálna povolená velkosť obrázku je 2048kb ',
        ],
        'time' => [
            'required' => 'Prosím vyplnte čas prípravy.',
            'integer'=> 'Čas musí byt číslo'
        ],
        'chunk' => [
            'required' => 'Prosím vyplnte počet porcii.',
            'integer'=> 'Čas musí byt číslo'
        ],
        'difficulty' => [
            'required' => 'Prosím vyplnte obtiažnosť.',
        ],
        'text' => [
            'required' => 'Prosím vyplnte popis receptu.',
        ],
        'category' => [
            'required' => 'Prosim neserte ma ',
        ],
        'ingredients' => [
            'required' => 'Prosím vyplnte ingrediencie receptu.',
            'not_in'=> 'Prosím vyplnte ingrediencie receptu.'
        ],
        'approach' => [
            'required' => 'Prosím vyplnte postup receptu.',
            'not_in'=> 'Prosím vyplnte postup receptu.'
        ],

        //user

        'name' => [
            'max'=> 'Meno môže mať najviac 255 znakov',
            'required' => 'Prosím vyplnte meno.',
            'not_in'=> 'Prosím vyplnte postup receptu.'
        ],
        'avatar' => [
            'required' => 'Prosím pridajte obrázok.',
            'image' => 'Priložený súbor nieje podporovaný',
            'mimes' => 'Obrázok músi byt typu jpeg,png,jpg,svg ',
            'max' => 'Maximálna povolená velkosť obrázku je 2048kb ',
        ],
        'cover' => [
            'required' => 'Prosím pridajte obrázok.',
            'image' => 'Priložený súbor nieje podporovaný',
            'mimes' => 'Obrázok músi byt typu jpeg,png,jpg,svg ',
            'max' => 'Maximálna povolená velkosť obrázku je 2048kb ',
        ],
        'token' => [
            'required' => 'token je vyžadovaný',
        ],
        'password' => [
            'required' => 'Vyplnte heslo',
            'min'=>'Heslo vyžaduje minimálne 8 znakov',
            'confirmed'=>'Heslá sa musia zhodovať',
        ],
        'email' => [
            'exists'=> 'Mailovú adresu neevidujeme.',
            'required' => 'Email je vyžadovaný.',
            'email'=>'Nie je platný email.',
            'unique'=>'Mailovú adresu už evidujeme.',
            'max'=> 'Email môže mať najviac 255 znakov',
        ],
        'current_password' => [
            'current_password' => 'Zadali ste nesprávne heslo.',
        ],
        'new_password_confirmation' => [
            'required' => 'Vyplnte heslo',
        ],
        'new_password' => [
            'required' => 'Vyplnte nové heslo',
            'min'=>'Heslo vyžaduje minimálne 8 znakov',
            'confirmed'=>'Heslá sa musia zhodovať',
            'different'=> 'Nové heslo nesmie byt rovnaké ako staré.'
        ],
        'subject' => [
            'required' => 'Vyplnte predmet',
        ],
        'text' => [
            'required' => 'Vyplnte text',
        ],
        'images' => [
            'image' => 'Priložený súbor nieje podporovaný',
            'mimes' => 'Obrázok músi byt typu jpeg,png,jpg,svg ',
            'max' => 'Maximálna povolená velkosť obrázku je 2048kb ',
        ],
        'task_name' => [
            'required' => 'Vyplnte Názov',
        ],
        'card_name' => [
            'required' => 'Vyplnte Názov',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
