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
     * @var int
     */
    protected $_year;

    /**
     * @param int $number
     * @param int $year
     */
    public function __construct($number, $year)
    {
        $this->_number = $number;
        $this->_year = $year;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->_year;
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
        return static::_strPad($this->_number);
    }

    /**
     * @return Month
     */
    public function next()
    {
        $newYear = $this->_year;
        $newNumber = ($this->_number+1);

        if ($newNumber > 12)
        {
            $newNumber = 1;
            $newYear++;
        }

        return new Month($newNumber, $newYear);
    }

    /**
     * @return Month
     */
    public function previous()
    {
        $newYear = $this->_year;
        $newNumber = ($this->_number-1);

        if ($newNumber < 1)
        {
            $newNumber = 12;
            $newYear--;
        }

        return new Month($newNumber, $newYear);
    }

    /**
     * @return array
     */
    public function getSheet()
    {
        $weeks = array();

        $date = new DateTime($this->_year.'-'.$this->_number);

        for ($i=1; $i <= $date->format('t'); $i++)
        {
            $date = new DateTime($this->_year.'-'.$this->_number.'-'.static::_strPad($i));
            $weekNumber = $date->format('W');

            if (!isset($weeks[$weekNumber]))
            {
                $weeks[$weekNumber] = array();
            }
        }

        foreach ($weeks as $weekNumber => $week)
        {
            for ($i=1; $i<=7; $i++)
            {
                $year = $this->_year;

                if (($this->_number == 1) AND ($weekNumber > 40))
                {
                    $year--;
                }

                if (($this->_number == 12) AND ($weekNumber < 10))
                {
                    $year++;
                }

                $date = new DateTime($year."W".static::_strPad($weekNumber).$i);
                $weeks[$weekNumber][$date->format('Y-m-d')] = array();
            }
        }

        return $weeks;
    }

    /**
     * @return Month
     */
    public static function getCurrent()
    {
        return new Month(date('n'), date('Y'));
    }

    /**
     * @param string $month
     * @param int $year
     * @return Month
     */
    public static function createFromUrlName($month, $year)
    {
        return new Month(array_search($month,static::$_months)+1, $year);
    }

    /**
     * @param string $month
     * @return bool
     */
    public static function isValidUrlName($month)
    {
        return in_array($month, static::$_months);
    }

    /**
     * @param $value
     * @return string
     */
    protected static function _strPad($value)
    {
        return str_pad($value, 2, '0', STR_PAD_LEFT);
    }
}
