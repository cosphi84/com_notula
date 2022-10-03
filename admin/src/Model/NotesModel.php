<?php

namespace FtUntagC\Component\Notula\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Utilities\ArrayHelper;
use Joomla\Database\ParameterType;


class NotesModel extends ListModel
{
    public function __construct($config = array())
    {
        if(empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(

            );
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true);

        $query->select(
            $this->getState('list.select',
                array(
                    $db->quoteName('a.id'),
                    $db->quoteName('a.asset_id'),
                    $db->quoteName('a.title'),
                    $db->quoteName('a.alias'),
                    $db->quoteName('a.meeting_time'),
                    $db->quoteName('a.venue'),
                    $db->quoteName('a.presented_by'),
                    $db->quoteName('a.notulen'),
                    $db->quoteName('a.catid'),
                    $db->quoteName('a.state'),
                    $db->quoteName('a.access'),
                    $db->quoteName('a.created'),
                    $db->quoteName('a.created_by'),
                    $db->quoteName('a.created_by_alias'),
                    $db->quoteName('a.modified'),
                    $db->quoteName('a.ordering'),
                    $db->quoteName('a.publish_up'),
                    $db->quoteName('a.publish_down'),
                )
            )
        )
        ->select(
            array(
                $db->quoteName('ag.title', 'access_level'),
                $db->quoteName('c.title', 'category_name'),
                $db->quoteName('c.created_user_id', 'category_uid'),
                $db->quoteName('c.level', 'category_level'),
                $db->quoteName('c.published', 'category_published'),
                $db->quoteName('parent.title', 'parent_category_title'),
                $db->quoteName('parent.id', 'parent_category_id'),
                $db->quoteName('parent.created_user_id', 'parent_category_uid'),
                $db->quoteName('parent.level', 'parent_category_level'),
            )
        )

        ->select('COUNT('.$db->quoteName('m.id').') AS participant' )
        ->from($db->quoteName('#__meeting', 'a'))
        ->join('LEFT', $db->quoteName('#__viewlevels', 'ag'), $db->quoteName('ag.id'). ' = '. $db->quoteName('a.access'))
        ->join('LEFT', $db->quoteName('#__categories', 'c'), $db->quoteName('c.id'). ' = '. $db->quoteName('a.catid'))
        ->join('LEFT', $db->quoteName('#__categories', 'parent'), $db->quoteName('parent.id'). ' = '. $db->quoteName('c.parent_id'))
        ->join('LEFT', $db->quoteName('#__meeting_participant', 'm'), $db->quoteName('m.meeting_id'). ' = '. $db->quoteName('a.id'));
        
        $access = $this->getState('filter.access');
        if (is_numeric($access)) {
            $access = (int) $access;
            $query->where($db->quoteName('a.access') . ' = :access')
                ->bind(':access', $access, ParameterType::INTEGER);
        } elseif (is_array($access)) {
            $access = ArrayHelper::toInteger($access);
            $query->whereIn($db->quoteName('a.access'), $access);
        }

        $published = (string) $this->getState('filter.published');

        if ($published !== '*') {
            if (is_numeric($published)) {
                $state = (int) $published;
                $query->where($db->quoteName('a.state') . ' = :state')
                    ->bind(':state', $state, ParameterType::INTEGER);
            }
        }else{
            $query->where($db->quoteName('a.state'). ' IN (1.0)');
        }

        // Filter by categories and by level
        $categoryId = $this->getState('filter.category_id', array());
        $level      = (int) $this->getState('filter.level');

        if (!is_array($categoryId)) {
            $categoryId = $categoryId ? array($categoryId) : array();
        }

        // Case: Using both categories filter and by level filter
        if (count($categoryId)) {
            $categoryId = ArrayHelper::toInteger($categoryId);
            $categoryTable = Table::getInstance('Category', 'JTable');
            $subCatItemsWhere = array();

            foreach ($categoryId as $key => $filter_catid) {
                $categoryTable->load($filter_catid);

                // Because values to $query->bind() are passed by reference, using $query->bindArray() here instead to prevent overwriting.
                $valuesToBind = [$categoryTable->lft, $categoryTable->rgt];

                if ($level) {
                    $valuesToBind[] = $level + $categoryTable->level - 1;
                }

                // Bind values and get parameter names.
                $bounded = $query->bindArray($valuesToBind);

                $categoryWhere = $db->quoteName('c.lft') . ' >= ' . $bounded[0] . ' AND ' . $db->quoteName('c.rgt') . ' <= ' . $bounded[1];

                if ($level) {
                    $categoryWhere .= ' AND ' . $db->quoteName('c.level') . ' <= ' . $bounded[2];
                }

                $subCatItemsWhere[] = '(' . $categoryWhere . ')';
            }

            $query->where('(' . implode(' OR ', $subCatItemsWhere) . ')');
        } elseif ($level = (int) $level) {
            // Case: Using only the by level filter
            $query->where($db->quoteName('c.level') . ' <= :level')
                ->bind(':level', $level, ParameterType::INTEGER);
        }

        // Add the list ordering clause.
        $orderCol  = $this->state->get('list.ordering', 'a.id');
        $orderDirn = $this->state->get('list.direction', 'DESC');

        if ($orderCol === 'a.ordering' || $orderCol === 'category_title') {
            $ordering = [
                $db->quoteName('c.title') . ' ' . $db->escape($orderDirn),
                $db->quoteName('a.ordering') . ' ' . $db->escape($orderDirn),
            ];
        } else {
            $ordering = $db->escape($orderCol) . ' ' . $db->escape($orderDirn);
        }

        $query->order($ordering);

        return $query;
    }
}