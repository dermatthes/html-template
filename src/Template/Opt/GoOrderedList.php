<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 09.09.16
     * Time: 10:47
     */

    namespace Html5\Template\Opt;


    class GoOrderedList {

        private $itemCount = 0;
        private $list = [];
        private $item = [];

        private $isClean = false;

        public function add($priority, $what, $alias=null) {
            if ($alias === null)
                $alias = "aas__{$this->itemCount}";

            $this->list[] = [$priority, $alias];
            $this->item[$alias] = $what;
            $this->itemCount++;
            $this->isClean = false;
        }

        private function _build() {
            if ($this->isClean)
                return;
            if ($this->itemCount === 0) {
                $this->isClean = true;
                return;
            }

            usort($this->list, function ($a, $b) {
                if ($a[0] == $b[0])
                    return 0;
                return ($a[0] > $b[0] ? -1 : 1);
            });
            $this->isClean = true;
        }


        public function __debugInfo() {
            $ret = [];
            $this->each (function ($what, $prio) use (&$ret) {
                while (isset ($ret["$prio"])) {
                    $prio .= "+";
                }
                $ret[$prio] = $what;
            });
            return $ret;
        }

        /**
         * <example>
         *  $c->each(function ($what, $prio, $alias) {
         *  });
         * </example>
         *
         * @param callable $fn
         */
        public function each(callable $fn) {
            $this->_build();

            foreach ($this->list as $curListItem) {
                $prio = $curListItem[0];
                $alias = $curListItem[1];
                if ( ! isset ($this->item[$alias]))
                    continue;
                $what = $this->item[$alias];
                if ($fn($what, $prio, $alias) === false)
                    return false;
            }
        }

    }