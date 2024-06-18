<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Context;
use Bitrix\Main\Grid\Options as gridOptions;
use Bitrix\Main\Loader;
use Bitrix\Main\UI\Filter\Options as filterOptions;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Web\Json;
use Elisad\Cgroups\CgroupsTable;

class CgroupsListComponent extends CBitrixComponent
{
    const SORTABLE_FIELDS = ['ID', 'NAME', 'ASSIGNED_BY_ID'];
    const FILTER_FIELDS = ['NAME'];

    public function executeComponent()
    {
        if ($this->StartResultCache()) {
            $gridId = 'CGROUPS_GRID';

            $gridOptions = new gridOptions($gridId);
            $sorting = $gridOptions->getSorting();
            $sort = array_filter($sorting['sort'], fn($key) => in_array($key, self::SORTABLE_FIELDS), ARRAY_FILTER_USE_KEY);
            if (empty($sort)) {
                $sort = ['ID' => 'asc'];
            }

            $filterOption = new filterOptions($gridId);
            $filterData = $filterOption->getFilter();
            $filter = array_filter($filterData, fn($key) => in_array($key, self::FILTER_FIELDS), ARRAY_FILTER_USE_KEY);
            if (!empty($filter)) {
                $filter = ['%NAME' => $filter['NAME']];
            }

            $pager = new PageNavigation($gridId);
            $navParams = $gridOptions->GetNavParams();
            $pageSize = $navParams['nPageSize'];
            $pager->setPageSize($pageSize);
            $total = $this->getTotal($filter);
            $pager->setRecordCount($total);
            $request = Context::getCurrent()->getRequest();
            if ($request->offsetExists('page')) {
                $currentPage = $request->get('page');
                $pager->setCurrentPage($currentPage > 0 ? $currentPage : $pager->getPageCount());
            } else {
                $pager->setCurrentPage(1);
            }

            $cgroups = $this->getCgroups($sort, $filter, $pager->getLimit(), $pager->getOffset());

            $this->arResult['gridId'] = $gridId;
            $this->arResult['headers'] = $this->getHeaders();
            $this->arResult['rows'] = $this->getRows($cgroups);
            $this->arResult['sort'] = $sort;
            $this->arResult['filter'] = $this->getFilterDescription();
            $this->arResult['total'] = $total;
            $this->arResult['pagination'] = [
                'PAGE_NUM' => $pager->getCurrentPage(),
                'ENABLE_NEXT_PAGE' => $pager->getCurrentPage() < $pager->getPageCount(),
                'URL' => $request->getRequestedPage(),
            ];
            $this->includeComponentTemplate();
        }
    }

    protected function getHeaders()
    {
        $headers = [
            ['id' => 'ID', 'name' => '№', 'sort' => 'ID', 'first_order' => 'desc', 'default' => true],
            ['id' => 'NAME', 'name' => 'Название', 'sort' => 'NAME', 'default' => true],
            ['id' => 'INTERESTS', 'name' => 'Интересы', 'default' => true],
            ['id' => 'ASSIGNED_BY', 'name' => 'Ответственный', 'sort' => 'ASSIGNED_BY_ID', 'default' => true]
        ];
        return $headers;
    }

    protected function getCgroups($order, $filter, $limit, $offset)
    {
        $select = [
            'ID',
            'NAME',
            'INTERESTS',
            'ASSIGNED_BY_ID',
            'ASSIGNED_NAME' => 'ASSIGNED_BY.NAME',
            'ASSIGNED_LASTNAME' => 'ASSIGNED_BY.LAST_NAME',
            'ASSIGNED_SECOND' => 'ASSIGNED_BY.SECOND_NAME',
            'ASSIGNED_LOGIN' => 'ASSIGNED_BY.LOGIN'
        ];
        $cgroups = CgroupsTable::getList(compact('select', 'filter', 'order', 'limit', 'offset'))->fetchAll();
        return $cgroups;
    }

    protected function getTotal($filter)
    {
        $total = CgroupsTable::getCount($filter);
        return $total;
    }

    protected function getRows($cgroups)
    {
        Loader::includeModule('crm');
        $rows = [];
        foreach ($cgroups as $item) {
            $vars = ['CGROUP_ID' => $item['ID']];
            $template = $this->arParams['SEF_URL_TEMPLATES']['details'];
            $viewUrl = CComponentEngine::makePathFromTemplate($template, $vars);
            $link = '<a href="' . $viewUrl . '">' . $item['NAME'] . '</a>';

            $tags = explode(',', $item['INTERESTS']);
            $label = '';
            foreach ($tags as $tag) {
                $label .= '<span class="ui-label ui-label-tag-secondary ui-label-fill">' . $tag . '</span>';
            }

            $user = [
                'ID' => $item['ASSIGNED_BY_ID'],
                'NAME' => $item['ASSIGNED_NAME'],
                'LAST_NAME' => $item['ASSIGNED_LASTNAME'],
                'SECOND_NAME' => $item['ASSIGNED_SECOND'],
                'LOGIN' => $item['ASSIGNED_LOGIN']
            ];
            $profile = Option::get('intranet', 'path_user', '', SITE_ID);
            $params = [
                'PREFIX' => 'CGROUP_' . $item['ID'] . '_RESPONSIBLE',
                'USER_ID' => $item['ASSIGNED_BY_ID'],
                'USER_NAME'=> CUser::FormatName(CSite::GetNameFormat(), $user),
                'USER_PROFILE_URL' => $profile
            ];
            $assigned = CCrmViewHelper::PrepareUserBaloonHtml($params);

            $cols = [
                'ID' => $item['ID'],
                'NAME' => $link,
                'INTERESTS' => $label,
                'ASSIGNED_BY' => $assigned
            ];

            $viewClick = 'BX.Crm.Page.open(' . Json::encode($viewUrl) . ')';
            $editUrl = '';
            $editClick = 'BX.Crm.Page.open(' . Json::encode($editUrl) . ')';
            $deleteUrl = '';
            $gridManagerId = 'manager';
            $arg1 = Json::encode($gridManagerId);
            $arg2 = 'BX.CrmUIGridMenuCommand.remove';
            $arg3 = '{ pathToRemove: ' . Json::encode($deleteUrl) . ' }';
            $deleteClick = 'BX.CrmUIGridExtension.processMenuCommand(' . $arg1 . ',' . $arg2 . ',' . $arg3 . ')';
            $actions = [
                ['TITLE' => 'Просмотреть', 'TEXT' => 'Просмотреть', 'ONCLICK' => $viewClick, 'DEFAULT' => true],
                ['TITLE' => 'Редактировать', 'TEXT' => 'Редактировать', 'ONCLICK' => $editClick],
                ['TITLE' => 'Удалить', 'TEXT' => 'Удалить', 'ONCLICK' => $deleteClick]
            ];

            $row = ['id' => $item['ID'], 'columns' => $cols, 'actions' => $actions];
            $rows[] = $row;
        }
        return $rows;
    }

    protected function getFilterDescription()
    {
        $filter = [
            ['id' => 'NAME', 'name' => 'Название', 'default' => true]
        ];
        return $filter;
    }
}