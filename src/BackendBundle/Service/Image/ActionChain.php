<?php


namespace BackendBundle\Service\Image;

class ActionChain extends BaseAction
{
    const BEFORE = -1;
    const AFTER  = 1;
    /**
     * [Action => ['label1', -1]]
     */
    private $positions;
    /**
     * @var Action[]
     */
    private $actions = [];
    private $finalized = false;

    public function __construct()
    {
        $this->positions = new \SplObjectStorage();
    }

    public function getLabels()
    {
        return array_map(function(Action $a) { return $a->getLabel();}, $this->actions);
    }

    public function count()
    {
        return count($this->actions);
    }

    /**
     * @param ActionContext $context
     * @return void
     */
    public function process(ActionContext $context)
    {
        if (!$this->finalized) {
            throw new \RuntimeException("Can't proceed with unfinalized chain.");
        }

        foreach ($this->actions as $action) {
            $action->process($context);
        }
    }

    public function before($targetLabel, Action $action)
    {
        $this->addAction($action, $targetLabel, self::BEFORE);
    }

    public function after($targetLabel, Action $action)
    {
        $this->addAction($action, $targetLabel, self::AFTER);
    }

    public function finalize()
    {
        $this->sort();
        $this->finalized = true;
    }

    public function addAction(Action $action, $targetLabel = '', $pos = 0)
    {
        if ($this->finalized) {
            throw new \RuntimeException("Can't add elements to finalized chain.");
        }
        $this->actions[] = $action;
        $this->positions[$action] = [$targetLabel, $pos];
    }

    protected function sort()
    {
        $f = function(Action $a, Action $b) {
            $targetLabelA    = $this->positions[$a][0];
            $targetLabelB    = $this->positions[$b][0];
            $targetPositionA = $this->positions[$a][1];
            $targetPositionB = $this->positions[$b][1];

            if ($targetLabelA === $b->getLabel()
                && $targetLabelB === $a->getLabel()
                && $targetPositionA == $targetPositionB) {
                throw new \LogicException("Can't sort actions due to conflict");
            }

            if ($targetLabelA === $b->getLabel()) {
                return $targetPositionA;
            } elseif ($targetLabelB === $a->getLabel()) {
                return $targetPositionB == 1 ? -1 : 1;
            }

            return 0;
        };

        $arr = $this->actions;
        // bubble sort, we have to compare each against each other
        for ($i = 0; $i < count($arr) - 1; $i++) {
            for ($j = 0; $j < count($arr) - $i - 1; $j++) {
                $a = $arr[$j];
                $b = $arr[$j+1];
                $cmpResult = $f($a, $b);
                if ($cmpResult === 1) {
                    $arr[$j]   = $b;
                    $arr[$j+1] = $a;
                }
            }
        }

        $this->actions = $arr;
    }
}