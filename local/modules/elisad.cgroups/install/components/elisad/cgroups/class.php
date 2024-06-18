<?php

class CgroupsComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->StartResultCache()) {
            $folder = $this->arParams['SEF_FOLDER'];
            $templates = $this->arParams['SEF_URL_TEMPLATES'];
            $page = CComponentEngine::parseComponentPath($folder, $templates, $arVariables);
            if (empty($page)) {
                $page = 'LIST';
            }
            $this->arResult['VARIABLES'] = $arVariables;
            $this->includeComponentTemplate($page);
        }
    }
}