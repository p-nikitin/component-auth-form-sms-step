<?php


class SAuthForm extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams): array
    {
        return $arParams;
    }

    /**
     * @return void
     */
    public function executeComponent()
    {
        $this->includeComponentTemplate();
    }


}
