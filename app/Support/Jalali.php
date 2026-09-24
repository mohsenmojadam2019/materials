<?php
namespace App\Support;
use DateTimeInterface;
use Morilog\Jalali\Jalalian;
final class Jalali {
    public static function date(DateTimeInterface $date, string $format='Y/m/d'): string {
        return self::digits(Jalalian::fromDateTime($date)->format($format));
    }
    public static function today(): string {
        return self::digits(Jalalian::fromDateTime(now())->format('%A %d %B %Y'));
    }
    public static function digits(string|int|float $value): string {
        return strtr((string)$value,['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹']);
    }
}
