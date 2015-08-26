<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Component_CalendarNavigation
 */
class Component_CalendarNavigation extends Tag_Span
{
    /**
     * @var Month
     */
    protected $_month;

    /**
     * @var int
     */
    protected $_year;

    /**
     * @var Model_Abstract_Entity
     */
    protected $_entity;

    /**
     * @var string
     */
    protected $_action;

    /**
     * @param Month $month
     * @param int $year
     * @param Model_Abstract_Entity|null $entity
     * @param string $action
     */
    public function __construct(Month $month, $year, Model_Abstract_Entity $entity = null,
                                $action = 'kalendarz')
    {
        parent::__construct();

        $this->_month = $month;
        $this->_year = $year;
        $this->_entity = $entity;
        $this->_action = $action;
        $this->addCSSClass('calendar_navigation');
    }

    /**
     * @param Month $month
     * @param int $year
     * @return string
     */
    protected function _getUrl($month, $year)
    {
        $result = URL::site($this->_action.'/'.$month->getUrlName().'-'.$year);

        if (!empty($this->_entity))
        {
            $result = $this->_entity->getURL().'/'.$this->_action.'/'.$month->getUrlName().'-'.$year;
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function _render()
    {
        $nextYear = $this->_year;
        $previousYear = $this->_year;
        $nextMonth = $this->_month->next($nextYear);
        $previousMonth = $this->_month->previous($previousYear);
        $nextUrl = $this->_getUrl($nextMonth, $nextYear);
        $previousUrl = $this->_getUrl($previousMonth, $previousYear);

        $linkNext = new Tag_HyperLink($nextMonth->getFullName().' '.$nextYear, $nextUrl);
        $linkPrevious = new Tag_HyperLink($previousMonth->getFullName().' '.$previousYear, $previousUrl);
        $labelNext = new Tag_Span('Następny:');
        $labelPrevious = new Tag_Span('Poprzedni:');

        $navigationNext = new Tag_Span();
        $navigationNext->add($labelNext);
        $navigationNext->add($linkNext);
        $navigationNext->addCSSClass('calendar_navigation_item');

        $navigationPrevious = new Tag_Span();
        $navigationPrevious->add($labelPrevious);
        $navigationPrevious->add($linkPrevious);
        $navigationPrevious->addCSSClass('calendar_navigation_item');

        $this->add($navigationNext);
        $this->add($navigationPrevious);

        return parent::_render();
    }
}
