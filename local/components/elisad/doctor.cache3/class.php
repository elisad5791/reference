<?php
use Bitrix\Iblock\Elements\ElementDoctorsTable;
use Bitrix\Main\Data\Cache;

class Doctor3ListComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        $cache = Cache::createInstance();
        $lifeTime = (int) $this->arParams['CACHE_TIME'];
        $cacheId = 'doctor3list';

        if ($cache->initCache($lifeTime, $cacheId, '/')) {
            $vars = $cache->GetVars();
            $this->arResult = $vars['arResult'];
        } else {
            $doctors = $this->getDoctors();
            $this->arResult = [];
            $this->arResult['gridId'] = 'DOCTORS_GRID';
            $this->arResult['columns'] = $this->getColumns();
            $this->arResult['rows'] = $this->getRows($doctors);
            if($cache->startDataCache()) $cache->endDataCache(['arResult' => $this->arResult]);
        }

        $this->includeComponentTemplate();
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

    function getColumns()
    {
        return [
            ['id' => 'ID', 'name' => '№', 'default' => true],
            ['id' => 'FIO', 'name' => 'ФИО', 'default' => true],
            ['id' => 'SCHEDULE', 'name' => 'График работы', 'default' => true],
            ['id' => 'PROCEDURES', 'name' => 'Процедуры', 'default' => true]
        ];
    }

    function getRows($doctors)
    {
        $rows = [];
        foreach ($doctors as $key => $doctor) {
            $cols = [
                'ID' => $doctor['id'],
                'FIO' => $doctor['fio'],
                'SCHEDULE' => $doctor['schedule'],
                'PROCEDURES' => implode(', ', $doctor['procedures'])
            ];
            $row = ['id' => $key, 'columns' => $cols];
            $rows[] = $row;
        }
        return $rows;
    }
}