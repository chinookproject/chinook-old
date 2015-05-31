<?php
namespace Core\Controllers;

class BaseController
{
    private $beforeFilters = array ( );
    private $afterFilters = array ( );

    public function beforeFilter ( $filterClass, array $filterOptions = array() )
    {
        $this->beforeFilters[$filterClass] = $filterOptions;
    }

    public function afterFilter ( $filterClass, $filterOptions )
    {
        $this->afterFilters[$filterClass] = $filterOptions;
    }

    public function executeBeforeFilters ( $ioc, $controller, $currentAction )
    {
        $result = null;
        $filters = $this->resolveFilters ( $this->beforeFilters, $currentAction );
        foreach ( $filters as $filter )
        {
            $filterClass = $ioc->create ( $filter );
            $result = $filterClass->beforeFilter ( $controller );
            if ( $result !== null )
                break;
        }
        return $result;
    }

    public function executeAfterFilters ( $ioc, $controller, $executedAction )
    {
        $result = null;
        $filters = $this->resolveFilters ( $this->afterFilters, $executedAction );
        foreach ( $filters as $filter )
        {
            $filterClass = $ioc->create ( $filter );
            $result = $filterClass->afterFilter ( $controller );
            if ( $result !== null )
                break;
        }

        return $result;
    }

    private function resolveFilters ( $filterList, $action )
    {
        $filtersToBeExecuted = array ( );

        foreach ( $filterList as $filterClass => $filterOptions )
        {
            // If no filters are supplied, then apply to all actions
            if ( !is_array ( $filterOptions ) || empty ( $filterOptions ) )
            {
                $filtersToBeExecuted[] = $filterClass;
                continue;
            }

            foreach ($filterOptions as $option => $actions)
            {
                if ( !is_array ( $actions ) )
                {
                    $actions = array($actions);
                }

                switch ( $option )
                {
                    case 'on':
                    {
                        if ( in_array ( $action, $actions ) )
                        {
                            $filtersToBeExecuted[] = $filterClass;
                        }
                        break;
                    }
                    case 'except':
                    {
                        if ( !in_array ( $action, $actions ) )
                        {
                            $filtersToBeExecuted[] = $filterClass;
                        }
                        break;
                    }
                }
            }
        }

        return array_unique ( $filtersToBeExecuted );
    }
}