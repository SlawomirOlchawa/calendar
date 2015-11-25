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
     * @var Model_Abstract_Entity
     */
    protected $_entity;

    /**
     * @var string
     */
    protected $_action;

    /**
     * @param Month $month
     * @param Model_Abstract_Entity|null $entity
     * @param string $action
     */
    public function __construct(Month $month, Model_Abstract_Entity $entity = null, $action = 'kalendarz')
    {
        parent::__construct();

        $this->_month = $month;
        $this->_entity = $entity;
        $this->_action = $action;
        $this->addCSSClass('calendar_navigation');
    }

    /**
     * @param Month $month
     * @return string
     */
    protected function _getUrl($month)
    {
        $result = URL::site($this->_action.'/'.$month->getUrlName().'-'.$month->getYear());

        if (!empty($this->_entity))
        {
            $result = $this->_entity->getURL().'/'.$this->_action.'/'.$month->getUrlName().'-'.$month->getYear();
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function _render()
    {
        $nextMonth = $this->_month->next();
        $previousMonth = $this->_month->previous();
        $nextUrl = $this->_getUrl($nextMonth);
        $previousUrl = $this->_getUrl($previousMonth);

        $linkNext = new Tag_HyperLink($nextMonth->getFullName().' '.$nextMonth->getYear(), $nextUrl);
        $linkPrevious = new Tag_HyperLink($previousMonth->getFullName().' '.$previousMonth->getYear(), $previousUrl);
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
