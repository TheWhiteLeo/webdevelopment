<?php
// 1. Знайти добуток усіх чисел у масиві. Користувач вводить масив чисел. Знайти добуток усіх елементів.
//$numbers = trim(fgets(STDIN));
//
//$array_numbers = explode(",", $numbers);
//$product = 1;
//foreach ($array_numbers as $number) {
//    $product *= $number;
//}
//
//echo $product;

// 2. Знайти всі досконалі числа в масиві. Створити масив із 15 чисел від 1 до 1000. Вивести всі досконалі числа (наприклад: 6, 28, 496).
//$numbers = [6, 12, 28, 45, 120, 250, 496, 500, 612, 701, 812, 888, 900, 99, 1000];
//
//foreach ($numbers as $number) {
//    if ($number <= 1) continue;
//
//    $divisors = [1];
//    for ($i = 2; $i * $i <= $number; $i++) {
//        if ($number % $i == 0) {
//            $divisors[] = $i;
//
//            if ($i * $i != $number) $divisors[] = $number / $i;
//        }
//    }
//
//    if (array_sum($divisors) == $number) echo "$number ";
//}

//3. Кількість нулів у масиві. Користувач вводить масив чисел. Порахувати, скільки з них дорівнюють нулю.
//$numbers = trim(fgets(STDIN));
//
//$array_numbers = explode(",", $numbers);
//$zeros = 0;
//foreach ($array_numbers as $number) {
//    if ($number == 0) $zeros++;
//}
//
//echo $zeros;

//4. Сума квадратів непарних чисел у масиві. Згенерувати масив із 20 чисел від 1 до 50. Знайти суму квадратів лише непарних.
//$numbers = [];
//for ($i = 0; $i < 20; $i++) {
//    $numbers[] = rand(1, 50);
//    echo "$numbers[$i] ";
//}
//
//$sum = 0;
//for ($i = 0; $i < 20; $i++) {
//    if ($numbers[$i] % 2 != 0) $sum += $numbers[$i] ** 2;
//}
//
//echo "\n$sum";

//5. Обмін першого та останнього елементів масиву. Створити масив із 8 випадкових чисел. Поміняти місцями перший та останній елемент масиву.
//$numbers = [];
//for ($i = 0; $i < 8; $i++) {
//    $numbers[$i] = rand(1, 100);
//    echo "$numbers[$i] ";
//}
//
//$temp = $numbers[0];
//$numbers[0] = $numbers[count($numbers) - 1];
//$numbers[count($numbers) - 1] = $temp;
//
//print_r($numbers);

//6. Знайти середнє арифметичне додатних чисел у масиві. Створити масив із 10 випадкових чисел від -50 до 50. Знайти середнє арифметичне додатних чисел.
//$numbers = [];
//for ($i = 0; $i < 10; $i++) {
//    $numbers[$i] = rand(-50, 50);
//    echo "$numbers[$i] ";
//}
//
//$sum = array_sum($numbers);
//echo "\nThe average value is " . $sum / count($numbers);

//7. Перетворення ПІБ на email-формат. Користувач вводить рядок “Гарбузюк Олег”. Згенерувати email у форматі: harbuzyuk.oleh@example.com (усі літери маленькі).
//$lowercase_translit_dict = [
//    'а'=>'a', 'б'=>'b', 'в'=>'v', 'г'=>'h', 'ґ'=>'g', 'д'=>'d', 'е'=>'e', 'є'=>'ye',
//    'ж'=>'zh', 'з'=>'z', 'и'=>'y', 'і'=>'i', 'ї'=>'yi', 'й'=>'y', 'к'=>'k', 'л'=>'l',
//    'м'=>'m', 'н'=>'n', 'о'=>'o', 'п'=>'p', 'р'=>'r', 'с'=>'s', 'т'=>'t', 'у'=>'u',
//    'ф'=>'f', 'х'=>'kh', 'ц'=>'ts', 'ч'=>'ch', 'ш'=>'sh', 'щ'=>'shch', 'ю'=>'yu',
//    'я'=>'ya', 'ь'=>'', '’'=>'', '\''=>''
//];
//
//$full_name = trim(fgets(STDIN));
//$lowercase = mb_strtolower($full_name);
//
//$lowercase_translit = str_replace(" ", ".", strtr($lowercase, $lowercase_translit_dict));
//$email = "$lowercase_translit@example.com";
//
//echo $email;

//8. Перевірити чи рік — кратний 400. Користувач вводить рік. Перевірити, чи кратний він 400.
//$year = trim(fgets(STDIN));
//
//if (is_numeric($year)) {
//    echo ($year % 400 == 0) ? "Кратний\n" : "Не кратний\n";
//} else {
//    echo "Check year\n";
//}

//9. Добуток елементів з парними індексами та вивід непарних індексів. Створити массив заповнивши його випадковими числами від 0 до 100 (rand).
// Порахувати добуток елементів, які більше 0 та у яких парні індекси. Вивести результат на екран і вивести елементи, які більше нуля і у яких не парний індекс.
//$numbers = [];
//for ($i = 0; $i < 20; $i++) {
//    $numbers[$i] = rand(0, 100);
//    echo "$numbers[$i] ";
//}
//
//$product = 1;
//$odd_indexes = "";
//for ($i = 0; $i < 20; $i++) {
//    if ($numbers[$i] <= 0) continue;
//
//    if ($i % 2 == 0) $product *= $numbers[$i];
//
//    else $odd_indexes .= "$numbers[$i] ";
//}
//echo "\n$product\n";
//echo $odd_indexes;

//10. Перевірка на високосний рік. Перевірити чи високосний рік. Вам потрібно розробити програму, яка перевіряла б введене користувачем число (рік). Число може бути в діапазоні від 1 до 9999.
//$year = trim(fgets(STDIN));
//
//if (is_numeric($year)) {
//    $isLeap = $year % 4 == 0 && ($year % 100 != 0 || ($year % 400 == 0));
//
//    echo $isLeap ? "Високосний рік\n" : "Звичайний рік\n";
//} else {
//    echo "Check year\n";
//}
