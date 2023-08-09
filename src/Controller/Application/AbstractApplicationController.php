<?php

namespace App\Controller\Application;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author David
 */
abstract class AbstractApplicationController extends AbstractController
{

    protected ?TranslatorInterface $translator = null;

    /**
     * @param Request $request
     * @param array $fieldNames
     * @return array
     */
    protected function cleanQueryParameters(Request $request, array $fieldNames)
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

    /**
     * Translates the given message
     *
     * @param string $id
     * @param array $parameters
     * @param string|null $domain
     * @param string|null $locale
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function trans(string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        if ($this->translator === null) {
            $this->translator = $this->container->get('translator');
        }

        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    /**
     * @return array
     */
    public static function getSubscribedServices(): array
    {
        $services = parent::getSubscribedServices();

        return array_merge(
            $services,
            [
                'translator' => '?' . TranslatorInterface::class
            ]
        );
    }
}
