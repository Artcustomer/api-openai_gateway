<?php

namespace App\Controller\Application;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author David
 */
abstract class AbstractApplicationController extends AbstractController
{

    /**
     * @param Request $request
     * @param array $fieldNames
     * @return array
     */
    public function cleanQueryParameters(Request $request, array $fieldNames)
    {
        $parameters = $request->query->all();
        $keys = array_keys($parameters);
        $allowedKeys = array_intersect($keys, $fieldNames);

        array_walk($parameters,
            function ($value, $key) use (&$parameters) {
                $parameters[$key] = urldecode($value);
            }
        );

        return array_filter(
            $parameters,
            function ($item) use ($allowedKeys) {
                return in_array($item, $allowedKeys);
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}
