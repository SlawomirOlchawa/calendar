<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Month
 */
class Month
{
    /**
     * @var string[]
     */
    protected static $_months = array('styczen', 'luty', 'marzec', 'kwiecien', 'maj', 'czerwiec',
        'lipiec', 'sierpien', 'wrzesien', 'pazdziernik', 'listopad', 'grudzien');

    /**
     * @var string[]
     */
    protected static $_monthsFull = array('Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj',
        'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień');

    /**
     * @var int
     */
    protected $_number;

    /**
     * @param int $number
     */
    public function __construct($number)
    {
        $this->_number = $number;
    }

    /**
     * @return string
     */
    public function getUrlName()
    {
        return static::$_months[$this->_number-1];
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return static::$_monthsFull[$this->_number-1];
    }

    /**
     * @return string
     */
    public function getNumericName()
    {
        return str_pad($this->_number, 2, '0', STR_PAD_LEFT);
    }

    /**
     * @param int|null $year
     * @return Month
     */
    public function next(&$year=null)
    {
        $newNumber = ($this->_number+1);

        if ($newNumber > 12)
        {
            $newNumber = 1;

            if (!empty($year))
            {
                $year++;
            }
        }

        return new Month($newNumber);
    }

    /**
     * @param int|null $year
     * @return Month
     */
    public function previous(&$year=null)
    {
        $newNumber = ($this->_number-1);

        if ($newNumber < 1)
        {
            $newNumber = 12;

            if (!empty($year))
            {
                $year--;
            }
        }

        return new Month($newNumber);
    }

    /**
     * @return Month
     */
    public static function getCurrent()
    {
        return new Month(date('n'));
    }

    /**
     * @param string $month
     * @return Month
     */
    public static function createFromUrlName($month)
    {
        return new Month(array_search($month,static::$_months)+1);
    }

    /**
     * @param string $month
     * @return bool
     */
    public static function isValidUrlName($month)
    {
        return in_array($month, static::$_months);
    }
}
