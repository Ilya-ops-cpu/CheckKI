<?php
namespace FCB\AddCommentInDeal;
class AddComment
{
    protected $text = 'Работа в воронке "Клиенты с имуществом" не завершена. Завершите работу с имуществом.';
    protected $user = 2986; // ID пользователя Система CRM ФЦБ
    public function AddCommentInDeal(int $dealID): void
    {
        $resId = \Bitrix\Crm\Timeline\CommentEntry::create(
            [
            'TEXT' => $this->text,
            'SETTINGS' => [],
            'AUTHOR_ID' => $this->user,
            'BINDINGS' => [
                ['ENTITY_TYPE_ID' => \CCrmOwnerType::Deal, 'ENTITY_ID' => $dealID]
            ]
        ]);
    }
}
?>
