<?
require_once __DIR__ . '/CheckKI.php';
require_once __DIR__ . '/AddCommentinDeal.php'; 
use FCB\CheckKI\CheckKIHelper;
use FCB\AddCommentinDeal\AddComment;
use Bitrix\Crm\Service;
use Bitrix\Crm\Service\Container;

AddEventHandler("crm", "OnBeforeCrmDealUpdate", function($arr){

    $stage = ['C8:4', 'C8:5', 'C8:UC_2SK0NX', 'C8:7'];  // Стадии производства на которые нельзя перемещать сделку если сделка КИ не завершена

    if (in_array($arr['STAGE_ID'], $stage)){
        $factory = Service\Container::getInstance()->getFactory(\CCrmOwnerType::Deal); // Получение информации о текущей сделке
        $arrDeal = $factory -> getItems([
            'filter' => [
                'ID' => $arr['ID']
            ],
            'select' => [
                'CONTACT_ID'
            ]
        ]);

        foreach($arrDeal as $item){
            $data = $item->getData();
            $contactID = $data['CONTACT_ID'];
        }

        if (!$contactID){
            return false;
        }

        $CheckKI = (new CheckKIHelper()) -> isStageAllowed($contactID);  // Запуск функции проверки сделки в воронке KI

        if (!$CheckKI){
            (new AddComment()) -> AddCommentInDeal($arr['ID']);
        }

        return $CheckKI;
    }
})
?> 
