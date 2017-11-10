<?php

namespace frontend\models\catalog;

use yii\helpers\Url;
use common\models\catalog\property\PropertyValue;
use Elasticsearch\Client;
use Yii;
use yii\base\Component;
use yii\data\Pagination;
use yii\data\Sort;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use common\repo\PropertyValueRepository;
use common\models\catalog\category\Category;
use common\models\helpers\ModelHelper;

class SmartFilter extends Component
{
    public $ids = [];
    private $category;
    public $items;
    /** @var  Pagination */
    public $pagination;
    /** @var  Sort */
    public $sort;
    protected $properties;
    protected $sef;
    protected $request;
    protected $sefRequest = [];
    protected $queryRequest = [];
    protected $sefSlugs = [];
    public $aggregations;
    public $totalCount;

    //private $_facet;
    /** @var PropertyValueRepository */
    private $_values;

    public function __construct(Category $category, array $properties, array $sef = [], array $config = [])
    {
        $this->category = $category;
        $this->properties = $properties;
        $this->sef = $sef;

        $this->_values = \Yii::$container->get(PropertyValueRepository::class);

        parent::__construct($config);
    }

    public function init()
    {
        $this->items = $this->getItems();
        $this->normalizeRequest();

        foreach ($this->properties as $key => $property) {
            if ($property['sef']) $this->sefSlugs[$key] = null;
        }

        $this->pagination = new Pagination([
            'defaultPageSize' => 36,
            'validatePage'    => false,
        ]);

        $this->sort = new Sort([
            'defaultOrder' => ['updated_at' => SORT_DESC],
            'attributes' => [
                'updated_at',
                'price',
            ],
        ]);

        $this->getResponse();
        $this->fillItems();
    }

    private function fillItems()
    {
        foreach ($this->aggregations as $key => $aggregation) {
            $this->items[$key]['values'] = $aggregation;
        }

        foreach ($this->items as $pid => &$item) {
            $checked = false;
            $iteration = PropertyValue::find()
                ->select([
                    'id',
                    'name',
                    'slug',
                ])
                ->where([
                    'property_id' => $pid,
                    'id'          => array_keys($item['values']),
                    'status'      => ModelHelper::STATUS_ACTIVE,
                ])->asArray()->indexBy('id');


            foreach ($iteration->each() as $value) {
                if (!$checked && (isset($this->request[$item['slug']]) && $value['id'] == $this->request[$item['slug']]->id)) {
                    $item['checked'] = $value['checked'] = $checked = true;
                }
                $value['count'] = $item['values'][$value['id']];
                $value['link'] = $this->collectUrl($item['slug'], $value);
                $item['values'][$value['id']] = $value;
            }
        }
    }

    private function collectUrl($slug, array $value)
    {
        $sefRequest = $this->sefSlugs;
        $queryRequest = [];

        if (ArrayHelper::keyExists($slug, $sefRequest)) {
            $sefRequest[$slug] = $value['slug'];
        } else {
            $queryRequest[$slug] = $value['slug'];
        }

        foreach ($this->sefRequest as $s => $v) {
            if ($s == $slug) {
                if ($value['slug'] == $v->slug) {
                    unset($sefRequest[$slug]);
                }
            } else {
                $sefRequest[$this->items[$this->properties[$s]['property_id']]['slug']] = $v->slug;
            }
        }

        foreach ($this->queryRequest as $s => $v) {
            if ($s == $slug) {
                if ($value['slug'] == $v->slug) {
                    unset($queryRequest[$slug]);
                }
            } else {
                $queryRequest[$this->items[$this->properties[$s]['property_id']]['slug']] = $v->slug;
            }
        }
        ArrayHelper::removeValue($sefRequest, null);

        $params = [
            Yii::$app->controller->action->id,
            'id'    => $this->category->id,
            'sef'   => $sefRequest,
            'query' => $queryRequest,
        ];

        return Url::toRoute($params);
    }

    private function getWhere($name)
    {
        $where = [];
        foreach ($this->request as $k => $value) {
            if ($k != $name)
                $where[$value['property_id']] = $value->id;
        }

        return $where;
    }

    public function getResponse()
    {
        /** @var Client $client */
        $client = $this->client;

        $facets = [];
        foreach ($this->properties as $name => $property) {
            $where = $this->getWhere($name);
            $found = false;

            foreach ($facets as $i => $f) {
                if ($f['where'] == $where) {
                    $facets[$i]["facet"][] = $property['property_id'];
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $facets[] = [
                    "where" => $where,
                    "facet" => array($property['property_id']),
                ];
            }
        }

        $aggregations = [];
        foreach ($facets as $facet) {
            $where = $facet['where'];
            $filter = $facet['facet'];

            if (empty($where)) {
                foreach ($filter as $property_id) {
                    $aggregations[$property_id] = [
                        'terms' => [
                            'field' => 'values.' . $property_id . '.value_id',
                            'size'  => 20,
                        ],
                    ];
                }
            } else {
                foreach ($filter as $property_id) {
                    $must = [];
                    foreach ($where as $pid => $value) {
                        $must[] = [
                            'term' => [
                                'values.' . $pid . '.value_id' => $value,
                            ],
                        ];
                    }
                    $aggregations[$property_id] = [
                        'filter'       => [
                            'bool' => [
                                'must' => $must,
                            ],
                        ],
                        'aggregations' => [
                            $property_id => [
                                'terms' => [
                                    'field' => 'values.' . $property_id . '.value_id',
                                    'size'  => 20,
                                ],
                            ],
                        ],
                    ];
                }
            }
        }

        $postFilter = [];
        foreach ($this->request as $value) {
            $postFilter[] = [
                'term' => [
                    'values.' . $value->property_id . '.value_id' => $value->id,
                ],
            ];
        }

        $result = $client->search([
            'index' => 'products',
            'type'  => 'product',
            'body'  => [
                '_source'      => ['id'],
                'from'         => $this->pagination->getOffset(),
                'size'         => $this->pagination->getLimit(),
                'sort' => array_map(function ($attribute, $direction) {
                    return [$attribute => ['order' => $direction === SORT_ASC ? 'asc' : 'desc']];
                }, array_keys($this->sort->getOrders()), $this->sort->getOrders()),
                'query'        => [
                    'bool' => [
                        'must' => [
                            ['term' => ['categories' => $this->category->id]],
                        ],
                    ],
                ],
                'aggregations' => [
                    'values' => [
                        'nested'       => [
                            'path' => 'values',
                        ],
                        'aggregations' => $aggregations,
                    ],
                ],
                'post_filter'  => [
                    'nested' => [
                        'path'  => 'values',
                        'query' => [
                            'bool' => [
                                'must' => $postFilter,
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $this->ids = ArrayHelper::getColumn($result['hits']['hits'], '_source.id');
        $this->totalCount = $result['hits']['total'];
        $this->aggregations = $this->normalizeAggregations($result['aggregations']['values']);
    }

    private function normalizeAggregations(array $aggregations)
    {
        $aggs = [];
        foreach ($aggregations as $key => $aggregation) {
            if ($key !== 'doc_count') {
                if (isset($aggregation['buckets']))
                    $aggs[$key] = ArrayHelper::map($aggregation['buckets'], 'key', 'doc_count');
                else {
                    $aggs[$key] = ArrayHelper::map($aggregation[$key]['buckets'], 'key', 'doc_count');
                }
            }
        }

        return $aggs;
    }

    private function normalizeRequest()
    {
        foreach ($this->sef as $name => $val) {
            if (!$value = $this->_values->findActiveBySlug($this->properties[$name]['property_id'], $val)) {
                throw new NotFoundHttpException('Страница не найдена');
            }

            $this->sefRequest[$name] = $value;
        }

        foreach (Yii::$app->request->queryParams as $name => $val) {
            if (ArrayHelper::keyExists($name, $this->properties) && is_string($val)) {
                if ($value = $this->_values->findActiveBySlug($this->properties[$name]['property_id'], $val)) {
                    $this->queryRequest[$name] = $value;
                }
            }
        }

        $this->request = ArrayHelper::merge($this->sefRequest, $this->queryRequest);
    }

    private function getItems()
    {
        $items = [];
        foreach ($this->properties as $slug => $ar) {
            $items[$ar['property_id']] = [
                'property_id' => $ar['property_id'],
                'name'        => $ar['name'],
                'slug'        => $ar['slug'],
                'sef'         => $ar['sef'],
                'values'      => [],
            ];
        }

        return $items;
    }

    public function getClient()
    {
        return Yii::$container->get(Client::class);
    }

    public function isSet()
    {
        return count($this->request) > 0 ? true : false;
    }
}