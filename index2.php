<?php
//Разбиение и объединение ФИО
function getFullnameFromParts ($surname, $name, $patronomyc) {
    $personFullName = "{$surname} {$name} {$patronomyc}";
    return $personFullName;
};
$name = "Иван";
$surname = "Иванов";
$patronomyc = "Иванович";

$personFullName = getFullnameFromParts ($surname, $name, $patronomyc);
//print_r($personFullName); 

function getPartsFromFullname($personFullName) {
    $partsOfFullName = explode(' ', $personFullName);
    $surname = $partsOfFullName[0];
    $name = $partsOfFullName[1];
    $patronomyc = $partsOfFullName[2];
    
    return [
        'surname' => $surname,
        'name' => $name,
        'patronomyc' => $patronomyc
    ];
}
$result = getPartsFromFullname($personFullName);
// print_r($result);


//Сокращение ФИО
function getShortName($personFullName) {
    $nameParts = getPartsFromFullname($personFullName);

    $shortName = $nameParts['name'] . ' ' . mb_substr($nameParts['surname'], 0, 1) . '.';

    return $shortName;
};
$shortName = getShortName($personFullName);
//  print_r($shortName);



// Функция определения пола по ФИО
function getGenderFromName($personFullName) {
    $namePartsForGender = getPartsFromFullname($personFullName);
    $summaryOfSex = 0;

// признаки женского пола
    if (mb_substr($namePartsForGender['patronomyc'], -3) === 'вна' || 
        mb_substr($namePartsForGender['name'], -1) === 'а' || 
        mb_substr($namePartsForGender['surname'], -2) === 'ва') {
        $summaryOfSex--;
    }
// признаки мужского пола
    if (mb_substr($namePartsForGender['patronomyc'], -2) === 'ич' ||
        mb_substr($namePartsForGender['name'], -1) === 'й' || 
        mb_substr($namePartsForGender['name'], -1) === 'н' &&
        mb_substr($namePartsForGender['surname'], -1) === 'в') {
        $summaryOfSex++;
    }

    if ($summaryOfSex > 0) {
        return "1 (мужской пол)"; 
    } elseif ($summaryOfSex < 0) {
        return "-1 (женский пол)"; 
    } else {
        return "0 (неопределенный пол)"; 
    }
}
$genderSummary = getGenderFromName($personFullName);
// print_r($genderSummary);


//Определение полового состава (аргумент - массив - example_persons_array)
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

function getGenderDescription($example_persons_array) {
    $totalPersons = count($example_persons_array);
    $maleNumber = 0;
    $femaleNumber = 0;
    $undefinedNumber = 0;

    foreach ($example_persons_array as $personName) {
        $gender = getGenderFromName($personName['fullname']);
        
        if ($gender === "1 (мужской пол)") {
            $maleNumber++;
        } elseif ($gender === "-1 (женский пол)") {
            $femaleNumber++;
        } else {
            $undefinedNumber++;
        }
    }

    $malePercent = ($maleNumber / $totalPersons) * 100;
    $femalePercent = ($femaleNumber / $totalPersons) * 100;
    $undefinedPercent = ($undefinedNumber / $totalPersons) * 100;

    return "Гендерный состав аудитории:\n---------------------------\n" .
           "Мужчины - " . round($malePercent, 1) . "%\n" .
           "Женщины - " . round($femalePercent, 1) . "%\n" .
           "Не удалось определить - " . round($undefinedPercent, 1) . "%";
}
$filtered_persons_array = array_filter($example_persons_array, function($personName) {
    return isset($person['fullname']);
});

$genderDescription = getGenderDescription($example_persons_array);
// print_r ($genderDescription);



//Подбор идеальной пары
function getPerfectPartner($surname, $name, $patronomyc, $personsArray) {
    $personFullName = getFullnameFromParts(mb_strtoupper($surname), mb_strtoupper($name), mb_strtoupper($patronomyc));
    $genderSummary = getGenderFromName($personFullName);

    $compatiblePartners = [];

    foreach ($personsArray as $person) {
        if (getGenderFromName($person['fullname']) !== $genderSummary) {
            $compatiblePartners[] = $person;
        }
    }

    $randomPartner1 = $compatiblePartners[array_rand($compatiblePartners)];
    
    $potentialPartners = array_filter($compatiblePartners, function($partner) use ($genderSummary) {
        return getGenderFromName($partner['fullname']) !== $genderSummary;
    });

    $randomPartner2 = $potentialPartners[array_rand($potentialPartners)];

    $shortName1 = getShortName($randomPartner1['fullname']);
    $shortName2 = getShortName($randomPartner2['fullname']);
    $harmonyPercent= rand(5000, 10000) / 100; 

    $resultPartner = "{$shortName1} + {$shortName2} = \n♡ Идеально на " . number_format($harmonyPercent, 2) . "% ♡";

    return $resultPartner;
}

$resultPartner = getPerfectPartner($surname, $name, $patronomyc, $example_persons_array);
print_r($resultPartner);
?>





















