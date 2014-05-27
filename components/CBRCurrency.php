<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 27.05.14
 * Time: 9:16
 */

namespace app\components;

/*
 * Класс для взятия курсов валют с сайта ЦБ РФ по адресу http://www.cbr.ru/scripts/XML_daily.asp?date_req=dd/mm/yyyy
 */
use SimpleXMLElement;
use yii\base\Exception;

class CBRCurrency
{
    const CB_URL = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=';

    public static function getValue($num_code, $date)
    {
        if (is_null($date)) {
            $date = date('d/m/Y');
        }
        // Если файла не существует - формируем его
        $request = static::CB_URL . $date;

        // Подключаемся к сайту
        $data = file_get_contents($request);
        // Если полученные данные - в формате XML
        if (substr($data, 0, 5) == '<?xml') {
            // Записываем данные в массив
            $xml = new SimpleXMLElement($data);
            $currency = [];

            foreach ($xml->Valute as $Currency) {
                $currency[(integer)$Currency->NumCode]['nominal'] = floatval(str_replace(",", ".", (string)$Currency->Nominal));
                $currency[(integer)$Currency->NumCode]['value'] = floatval(str_replace(",", ".", (string)$Currency->Value));
            }

            if (array_key_exists($num_code, $currency)) {
                return $currency[$num_code]['value'];
            } else {
                throw new Exception('Не удалось найти курс валюты');
            }
        } else {
            throw new Exception('Не удалось получить данные от ЦБ РФ');
        }
    }
}
