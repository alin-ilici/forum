<?php
namespace Forum\CoreBundle\Services;

use Doctrine\Bundle\DoctrineBundle\Twig\DoctrineExtension;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Validator\Validator;
use Symfony\Component\DependencyInjection\Container;

class ToolService
{
    /**
     * @var Validator
     */
    protected $validator;
    /**
     * @var Container
     */
    protected $container;

    /**
     * An array of temporary things that may be passed
     * from application context into static context
     * @var array
     */
    public static $temp = array();

    public function __construct(Validator $validator, Container $container)
    {
        $this->validator = $validator;
        $this->container = $container;
    }

    public static function dumpSql(QueryBuilder $query)
    {
        $fparams = function ($dql) {
            preg_match_all('/(?<=(?<!Bundle|Bundle:):)\w+/i', $dql, $results);
            $results = $results[0];

            /*
            $results = array_map(
                function ($x) {
                    return trim($x, ':');
                },
                $results
            );
            */

            return $results;
        };

        $expectedParams = $fparams($query->getQuery()->getDQL());

        $anonim = function ($param) use (&$anonim) {
            /** @var $param \Doctrine\ORM\Query\Parameter */
            if (is_object($param) &&
                !$param instanceof \DateTime &&
                !$param instanceof \Doctrine\ORM\Query\Parameter
            ) {
                return $param->getId();
            } elseif ($param instanceof \Doctrine\ORM\Query\Parameter) {
                if ($param->getValue() instanceof \DateTime) {
                    return $param->getValue()->format('Y-m-d H:i:s');
                } elseif ($param->getValue() instanceof ArrayCollection) {
                    $values = array();
                    foreach ($param->getValue()->toArray() as $item) {
                        $values[] = $anonim($item);
                    }

                    return implode(',', $values);
                } elseif (is_object($param->getValue())) {
                    return $param->getValue()->getId();
                } elseif (is_array($param->getValue())) {
                    return $anonim($param->getValue());
                } else {
                    return $param->getValue();
                }
            } elseif (is_array($param)) {
                $return = array();
                foreach ($param as $p) {
                    $return[] = $anonim($p);
                }

                return $return;
            } elseif ($param instanceof \DateTime) {
                return $param->format('Y-m-d H:i:s');
            } elseif (!is_object($param)) {
                return $param;
            } else {
                return $param->getValue();
            }
        };

        $params = array();
        foreach ($expectedParams as $key => $ep) {
            $pp = $query->getParameter($ep);

            $params[$key] = $anonim($pp);
        }

        //$helper = new DoctrineExtension();
        if (1 || php_sapi_name() === 'cli') {
            //bugged formatter for cli output ... waiting for sqlFormatter update
            \SqlFormatter::$cli = false;

            return \SqlFormatter::format(html_entity_decode(strip_tags(ToolService::replaceQueryParameters($query->getQuery()->getSQL(), $params))), false);
        }

        return \SqlFormatter::format(html_entity_decode(strip_tags(ToolService::replaceQueryParameters($query->getQuery()->getSQL(), $params))));
    }

    public static function replaceQueryParameters($query, $parameters)
    {
        $i = 0;

        $result = preg_replace_callback(
            '/\?|(:[a-z0-9_]+)/i',
            function ($matches) use ($parameters, &$i) {
                $key = substr($matches[0], 1);
                if (!array_key_exists($i, $parameters) && !array_key_exists($key, $parameters)) {
                    return $matches[0];
                }

                $value = array_key_exists($i, $parameters) ? $parameters[$i] : $parameters[$key];
                $result = is_null($value) ? 'NULL' : DoctrineExtension::escapeFunction($value);
                $i++;

                return $result;
            },
            $query
        );

        $result = \SqlFormatter::highlight($result);
        $result = str_replace(array("<pre ", "</pre>"), array("<span ", "</span>"), $result);

        return $result;
    }
}
