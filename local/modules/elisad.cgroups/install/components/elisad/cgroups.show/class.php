<?php

use Elisad\Cgroups\CgroupsTable;

class CgroupsShowComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->StartResultCache()) {
            $cgroup = CgroupsTable::getById($this->arParams['CGROUP_ID'])->fetch();
            $this->arResult['CGROUP'] = $cgroup;
            $this->arResult['TITLE'] = 'Группа контактов - ' . $cgroup['NAME'];
            $this->arResult['GRID_ID'] = 'CGROUPS_GRID';
            $this->arResult['FORM_ID'] = 'CGROUPS_FORM';
            $this->arResult['TACTILE_FORM_ID'] = 'CGROUPS_TACTILE';
            $this->includeComponentTemplate();
        }
    }
}