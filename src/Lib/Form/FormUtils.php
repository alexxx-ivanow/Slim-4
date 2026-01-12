<?

namespace App\Lib\Form;

use Respect\Validation\Validator as v;

class FormUtils
{
    public static function translatorForm($message)
    {
        $messages = [
            '{{name}} must contain only letters (a-z) and digits (0-9)' => 'поле должно содержать только латиницу и цифры',
            '{{name}} must have a length between {{minValue}} and {{maxValue}}' => 'длина поля должна быть между {{minValue}} и {{maxValue}}',
        ];
        if(array_key_exists($message, $messages)) {
            return $messages[$message];
        } else {
            return '';
        }
    }
    public static function validatorsForm()
    {
        $pagetitleValidator = v::length(2, 20);
        $aliasValidator = v::alnum()->noWhitespace()->length(4, 10);
        return [
            'pagetitle' => $pagetitleValidator,
            'alias' => $aliasValidator
        ];
    }
}