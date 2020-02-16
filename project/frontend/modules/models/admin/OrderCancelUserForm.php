<?php


namespace frontend\modules\models\admin;


class OrderCancelUserForm extends AbstractUserModel
{
    public $start;
    public $end;

    public function rules()
    {
        $rules = parent::rules();
        array_push($rules,
            [['start', 'end'], 'required', 'when' => function(){
                return isset($this->start) || isset($this->end);
            }, 'message' => 'Поле {attribute} обязательно для заполнения']);
        array_push($rules,
            [['start', 'end'], 'match', 'pattern' => '/^\d{4}.\d{2}.\d{2}$/', 'when' => function(){
                return isset($this->start) && isset($this->end);
            }, 'message' => 'Неверный формат даты']);
        array_push($rules, ['start', 'isValidDate']);
        return $rules;
    }

    function update()
    {
       $user = $this->getUser();

       $user->start_order_cancel = $this->start;
       $user->end_order_cancel = $this->end;

       return $user->save();
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function isValidDate($attribute, $params)
    {
        if($this->start > $this->end || $this->start < date(getenv('DATE_FORMAT_REQUEST'))){
            $this->addError($attribute, 'Невалидная дата');
        }
    }
}