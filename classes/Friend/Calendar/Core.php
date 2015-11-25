<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Friend_Calendar_Core
 */
class Friend_Calendar_Core extends Friend_Abstract_Entity
{
    /**
     * @var Model_Abstract_Event
     */
    protected $_events;

    /**
     * @var string
     */
    protected $_action;

    /**
     * @var string
     */
    protected $_caption;

    /**
     * @var Month
     */
    protected $_month = null;

    /**
     * @param Controller_Entity $controller
     * @param Model_Abstract_Event $events
     * @param string $action
     * @param string $caption
     * @throws HTTP_Exception_404
     */
    public function __construct(Controller_Entity $controller,
                                Model_Abstract_Event $events,
                                $action = null,
                                $caption = null)
    {
        parent::__construct($controller);

        if (empty($action))
        {
            $action = 'kalendarz';
        }

        if (empty($caption))
        {
            $caption = 'Kalendarz imprez';
        }

        $this->_events = $events;
        $this->_action = $action;
        $this->_caption = $caption;

        $currentMonth = Month::getCurrent()->getUrlName();
        $currentYear = (int) date('Y');
        $year = null;

        $params = explode('-', $this->_controller->request->param('params'));

        if (!empty($params))
        {
            $this->_month = array_shift($params);
            $year = (int) array_shift($params);
        }

        if ($this->_month === $currentMonth AND $year === $currentYear)
        {
            $url = URL::site($this->_action);

            if (!empty($this->_entity))
            {
                $url = $this->_entity->getURL().'/'.$this->_action;
            }

            $this->_controller->redirect($url, 301);
        }

        if (empty($this->_month) AND empty($year))
        {
            $this->_month = $currentMonth;
            $year = $currentYear;
        }

        if (($year < 2015) OR ($year > date('Y')+2)
            OR (!Month::isValidUrlName($this->_month)))
        {
            throw new HTTP_Exception_404('Nie znaleziono strony o podanym adresie');
        }

        $this->_month = Month::createFromUrlName($this->_month, $year);
        $date = $year.'-'.$this->_month->getNumericName();

        $this->_events->newerOrEqualThan($date.'-01')->olderOrEqualThan($date.'-31')->orderByDate();

        if (!empty($this->_entity))
        {
            Helper_Meta::addTitle($this->_entity->name);
        }

        Helper_Meta::addTitle($this->_caption);
        Helper_Meta::addTitle($this->_month->getFullName().' '.$year);
    }

    /**
     * @return string
     */
    public function getUrlName()
    {
        return $this->_month->getUrlName().'-'.$this->_month->getYear();
    }

    /**
     * @return Box_Bar
     */
    public function getNavigationBar()
    {
        if (!empty($this->_entity))
        {
            Helper_Locator::add($this->_entity->name, $this->_entity->getURL());
        }
        else
        {
            Helper_Locator::add($this->_caption, URL::site($this->_action));
        }

        Helper_Locator::add($this->_month->getFullName().' '.$this->_month->getYear(),
            URL::site($this->_action.'/'.$this->_month->getUrlName().'-'.$this->_month->getYear()));

        $navigation = new Component_CalendarNavigation(
            $this->_month,
            $this->_entity,
            $this->_action
        );

        return new Box_Bar(Helper_Locator::render().$navigation->render());
    }

    /**
     * @return Box_Big
     */
    public function getEventsBox()
    {
        return new Box_Biggest(new Component_Calendar($this->_month, $this->_events), $this->_getBoxCaption());
    }

    /**
     * @return Box_Small
     */
    public function getEventsBoxMobile()
    {
        return new Box_Small(new Component_List($this->_events), $this->_getBoxCaption());
    }

    /**
     * @return string
     */
    protected function _getBoxCaption()
    {
        return 'Wydarzenia '.Helper_Inflector::locative($this->_month->getFullName());
    }
}
