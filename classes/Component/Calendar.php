<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Component_Calendar
 */
class Component_Calendar extends Tag_Block
{
    /**
     * @var Model_Abstract_Event
     */
    protected $_events;

    /**
     * @var Month
     */
    protected $_month;

    /**
     * @param Month $month
     * @param Model_Abstract_Event $events
     */
    public function __construct(Month $month, Model_Abstract_Event $events)
    {
        parent::__construct();

        $this->_month = $month;
        $this->_events = $events;

        $this->addCSSClass('calendar');
        Helper_Includer::addCSS('media/mod/calendar/css/main.css');
    }

    /**
     * @return string
     */
    protected function _render()
    {
        $weeks = $this->_month->getSheet();
        $events = $this->_events->findAll();

        foreach ($events as $event)
        {
            $date = new DateTime($event->date_start);
            $week = $date->format('W');
            $weeks[$week][$event->date_start][] = $event;
        }

        $table = new Tag_Table();

        $header = new Tag_Table_Row();
        $weekDays = array('Pon.', 'Wt.', 'Śr.', 'Czw.', 'Pt.', 'Sob.', 'Niedz.');

        for ($i=0; $i<7; $i++)
        {
            $caption = new Tag_Table_Header($weekDays[$i]);
            $header->add($caption);

            if ($i>=5)
            {
                $caption->addCSSClass('weekend');
            }
        }

        $table->add($header);

        foreach ($weeks as $week)
        {
            $row = new Tag_Table_Row();
            $table->add($row);

            foreach ($week as $dateString => $day)
            {
                $date = new DateTime($dateString);
                $cell = new Tag_Table_Cell();
                $row->add($cell);

                if ($date->format('m') !== $this->_month->getNumericName())
                {
                    $cell->addCSSClass('daysOutside');
                }

                if ($date->format('j') === date('j') AND $date->format('n') === date('n'))
                {
                    $cell->addCSSClass('thisDay');
                }

                $dayNumber = new Tag_Span($date->format('d'));
                $dayNumber->addCSSClass('dayNumber');
                $cell->add($dayNumber);

                $container = new Tag_Block();
                $container->addCSSClass('smallphoto_list');
                $cell->add($container);

                $list = new Tag_List();
                $container->add($list);

                foreach ($day as $event)
                {
                    $list->add($this->_getListItem($event));
                }
            }

        }

        $this->add($table);

        return parent::_render();
    }

    /**
     * @param Model_Abstract_Event $event
     * @return Tag_HyperLink
     */
    protected function _getListItem(Model_Abstract_Event $event)
    {
        return new Tag_HyperLink($event->name, $event->getUrl());
    }
}
