<?php
namespace FCB\CheckKI;
use \Bitrix\Main\Loader;
use Bitrix\Crm\Service;
use Bitrix\Crm\Service\Container;
Loader::requireModule('crm');

class CheckKIHelper
{
    protected $stageList = ['C41:UC_XDCQMG', 'C41:UC_8X1Q4Q', 'C41:LOSE', 'C41:WON']; // Финальные стадии воронки КИ
    protected $categoryId = 41; // Воронка сделок Клиенты с имуществом

    protected function checkStageKI(int $contact): ?string
    {
        $factory = Service\Container::getInstance()->getFactory(\CCrmOwnerType::Deal); // Получение информации о сделках КИ

        $arrDeal = $factory -> getItems([
            'filter' => [
                'CONTACT_ID'=> $contact,
                'CATEGORY_ID'=> $this->categoryId
            ],
            'select' => [
                'STAGE_ID'
            ]
        ]);
        foreach($arrDeal as $item){
            $data = $item->getData();
            $resultStageDeal = $data['STAGE_ID'];
        }

        return $resultStageDeal; // Возвращает стадию
    }

    public function isStageAllowed (int $contact): bool
    {
        $stage = $this -> checkStageKI($contact);

        if (is_null($stage)){
            return true;
        }

        $result = in_array($stage, $this->stageList);

        return $result;
    }
}
?>
