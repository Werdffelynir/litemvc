<?php


if (!function_exists('limitChars')) {
  /**
   * Возвращает лимитированое количество символов из строки
   *
   * @param string $text  входящая строка
   * @param int    $limit количество выводимых символов
   * @param string $more  строка в конце результата
   * @return string
   */
  function limitChars($text, $limit=200, $more=''){
    $text=  mb_substr($text,0,$limit);
    if(mb_substr($text, mb_strlen($text)-1,1) && mb_strlen($text) == $limit) {
      $textReturn = mb_substr($text,0,mb_strlen($text)-mb_strlen(strrchr($text,' ')));
      if(!empty($textReturn)) {
        return $textReturn.$more;
      }
    }
    return $text;
  }
}

if (!function_exists('limitWords')) {
  /**
   * Возвращает лимитированое количество целых слов из строки
   *
   * @param string $input_text  входящая строка
   * @param int    $limit       количество выводимых слов
   * @param string $end_str     строка в конце результата
   * @return string
   */
  function limitWords($input_text, $limit = 50, $end_str = '') {
    $input_text = strip_tags($input_text);
    $words = explode(' ', $input_text);
    if ($limit < 1 || sizeof($words) <= $limit) {
      return $input_text;
    }
    $words = array_slice($words, 0, $limit);
    $out = implode(' ', $words);
    return $out.$end_str;
  }
}


/**
 * Счетчик
 *
 * @since 0.3.0
 *
 * timerStart();
 * echo timerStart();
 */
if (!function_exists('timerStart')) {
  function timerStart()
  {
    define('START_TIME',microtime(true));
  }
}
if (!function_exists('timerStop')) {
  function timerStop()
  {
    return round(microtime(true)-START_TIME,4);
  }
}



