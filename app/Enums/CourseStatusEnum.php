<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CourseStatusEnum extends Enum
{
    public const SAP_TOI = 0;
    private const DANG_HOC = 1;
    private const HOAN_THANH = 2;

    public static function statusName(){
        return [
            'Sắp tới' => self::SAP_TOI,
            'Đang học' => self::DANG_HOC,
            'Hoàn thành' => self::HOAN_THANH
        ];
    }
}
