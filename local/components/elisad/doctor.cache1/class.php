<?php
use Bitrix\Iblock\Elements\ElementDoctorsTable;

class DoctorListComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->StartResultCache()) {
            $doctors = $this->getDoctors();
            $this->arResult = $doctors;
            $this->SetResultCacheKeys([]);
            $this->includeComponentTemplate();
        }
    }

    function getDoctors()
    {
        $select = ['ID', 'NAME', 'SCHEDULE', 'PROCEDURES.ELEMENT'];
        $res = ElementDoctorsTable::getList(['select' => $select])->fetchCollection();
        $doctors = [];
        foreach ($res as $item) {
            $id = $item->getId();
            $fio = $item->getName();
            $schedule = $item->getSchedule()->getValue();
            $res = $item->getProcedures()->getAll();
            $procedures = [];
            foreach ($res as $proc) {
                $procedures[] = $proc->getElement()->getName();
            }
            $doctors[] = compact('id', 'fio', 'schedule', 'procedures');
        }
        return $doctors;
    }
}