<?php

function addDaysToDate($startDate, $daysToAdd)
{
    $nextDay = $startDate;
    // loop for X days
    for ($i = 0; $i < $daysToAdd; $i++) {
        // get what day it is next day
        $nextDay = $startDate->modify('+1 day');

        // if it's Saturday or Sunday get $i-1
        if (in_array($nextDay->format('w'), [0, 6]) || isHoliday($nextDay->getTimestamp())) {
            $i--;
        }
    }
    return $nextDay;
}

/**
 * Fonction permettant de retourner les jours fériés d'une année passée en paramètre
 */
function publicHolidays($year)
{
    if ($year === null) {
        $year = intval(strftime('%Y'));
    }

    $easterDate = easterDate($year);
    $easterDay = date('j', $easterDate);
    $easterMonth = date('n', $easterDate);
    $easterYear = date('Y', $easterDate);
    $holidays = array(
        // Jours fériés fixes
        mktime(0, 0, 0, 1, 1, $year),// 1er janvier
        mktime(0, 0, 0, 1, 6, $year),// Epifania
        mktime(0, 0, 0, 4, 25, $year),// Festa liberazione
        mktime(0, 0, 0, 5, 1, $year),// Fête du travail
        mktime(0, 0, 0, 6, 2, $year),// Festa della Repubblica
        mktime(0, 0, 0, 8, 12, $year),// Ferie Estive
        mktime(0, 0, 0, 8, 13, $year),// Ferie Estive
        mktime(0, 0, 0, 8, 14, $year),// Ferie Estive
        mktime(0, 0, 0, 8, 15, $year),// Ferragosto
        mktime(0, 0, 0, 8, 16, $year),// Ferie Estive
        mktime(0, 0, 0, 8, 19, $year),// Ferie Estive
        mktime(0, 0, 0, 11, 1, $year),// Toussaint
        mktime(0, 0, 0, 12, 23, $year),// Pre-Noël
        mktime(0, 0, 0, 12, 24, $year),// Pre-Noël
        mktime(0, 0, 0, 12, 25, $year),// Noël
        mktime(0, 0, 0, 12, 26, $year),// Saint-Etienne
        mktime(0, 0, 0, 12, 27, $year),// Ferie invernali
        mktime(0, 0, 0, 12, 28, $year),// Ferie invernali
        mktime(0, 0, 0, 12, 29, $year),// Ferie invernali
        mktime(0, 0, 0, 12, 30, $year),// Ferie invernali
        mktime(0, 0, 0, 12, 31, $year),// Ferie invernali

        // Jour fériés qui dépendent de Pâques
        mktime(0, 0, 0, $easterMonth, $easterDay + 1, $easterYear),// Lundi de Pâques
        mktime(0, 0, 0, $easterMonth, $easterDay + 39, $easterYear),// Ascension
        mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear), // Pentecôte
    );

    sort($holidays);

    return $holidays;
}

/**
 * Fonction permettant de retour la date de Pâques au format timestamp
 */
function easterDate($year)
{
    $a = $year % 4;
    $b = $year % 7;
    $c = $year % 19;
    $m = 24;
    $n = 5;
    $d = (19 * $c + $m) % 30;
    $e = (2 * $a + 4 * $b + 6 * $d + $n) % 7;
    $easterdate = 22 + $d + $e;

    if ($easterdate > 31) {
        $day = $d + $e - 9;
        $month = 4;
    } else {
        $day = 22 + $d + $e;
        $month = 3;
    }

    if ($d == 29 && $e == 6) {
        $day = 10;
        $month = 04;
    } elseif ($d == 28 && $e == 6) {
        $day = 18;
        $month = 04;
    }
    return mktime(0, 0, 0, $month, $day, $year);
}

/**
 * Fonction permettant de savoir si un timestamp d'une date passée en paramètre est férié ou pas
 */
function isHoliday($timestamp)
{
    $iYear = strftime('%Y', $timestamp);
    $aHolidays = publicHolidays($iYear);
    /*
    * On est obligé de convertir les timestamps en string à cause des décalages horaires.
    */
    $aHolidaysString = array_map(function ($value) {
        return strftime('%Y-%m-%d', $value);
    }, $aHolidays);

    if (in_array(strftime('%Y-%m-%d', $timestamp), $aHolidaysString)) {
        return true;
    }

    return false;
}
