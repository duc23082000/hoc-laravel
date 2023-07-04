<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LessonStatusEnum extends Enum
{
    public const Public = 0;
    public const Private = 1;
    // const OptionThree = 2;
    public static function statusName(){
        return [
            'Public' => self::Public,
            'Private' => self::Private
        ];
    }
}

