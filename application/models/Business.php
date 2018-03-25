<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Business
 *
 * @property CI_Cache $cache
 */
class Business extends SimpleModel
{
    public $_table = 'business';

    protected $soft_delete = false;
    protected $after_get = ['setCategory'];

    protected $rowsCount = 15;
    protected $cacheKeyLast = 'last_results';
    protected $cacheKeyCurrent = 'result_%s';
    protected $cacheTTL = 5 * 60;

    private $apiURL = 'https://rmsp.nalog.ru/search-proc.json';

    public static $categories = [
        1 => "Микропредприятие",
        2 => "Малое предприятие",
        3 => "Среднее предприятие"
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('checker');
        $this->load->driver('cache', [
            'adapter' => 'memcached',
            'backup' => 'file'
        ]);
    }

    public function lastResults()
    {
        if (! $rows = $this->cache->get($this->cacheKeyLast)) {
            $rows = $this->limit($this->rowsCount)->order_by('modified', 'desc')->get_all();
            $this->cache->save($this->cacheKeyLast, $rows, $this->cacheTTL);
        }

        return $rows;
    }

    public function search($query, $create = false)
    {
        $query = (int)trim($query);

        $isInn = validInn($query);
        $isOgrn = validOgrn($query);

        $result = [
            'success' => false,
            'error' => null,
            'data' => null
        ];

        if (!$isInn && !$isOgrn) {
            $result['error'] = 'Введённый идентификатор не является ИНН или ОГРН(ИП)';
            return $result;
        }


        if (! $row = $this->cache->get(sprintf($this->cacheKeyCurrent, md5($query)))) {
            $where = [];
            if ($isInn) {
                $where['inn'] = $isInn;
            }
            if ($isOgrn) {
                $where['ogrn'] = $isOgrn;
            }
            if ($create) {
                $where['modified >='] = date('Y-m-d H:i:s', strtotime('-' . $this->cacheTTL . ' seconds'));
            }

            $row = $this->get_by($where);

            if (!$row || empty($row) || (!empty($row) && (strtotime($row->modified) + $this->cacheTTL) > time())) {
                if ($create) {
                    $response = $this->requestApi($query);
                    if (!empty($response->data)) {
                        $row = $this->saveFromResponse($response->data[0]);
                    } else {
                        $result['success'] = false;
                        $result['error'] = 'Субъект не найден';
                        return $result;
                    }
                } else {
                    $result['success'] = false;
                    $result['error'] = 'Субъект не найден';
                    return $result;
                }
            }

            $this->cache->delete($this->cacheKeyLast);
            foreach (['inn', 'ogrn'] as $key) {
                $this->cache->save(sprintf($this->cacheKeyCurrent, md5($row->{$key})), $row, $this->cacheTTL);
            }
        }

        $result['success'] = true;
        $result['data'] = $row;

        return $result;
    }

    protected function setCategory($row)
    {
        if (is_object($row)) {
            $row->category_name = self::$categories[$row->category];
        }
        if (is_array($row)) {
            $row['category_name'] = self::$categories[$row['category']];
        }
        return $row;
    }

    private function saveFromResponse($response)
    {
        $row = new \STDClass();
        $row->id = null;
        $row->inn = $response->inn;
        $row->ogrn = $response->ogrn;
        $row->category = $response->category;
        $row->name = $response->name_ex;
        $row->short_name = $response->namec;
        $row->region_type = $response->regiontype;
        $row->region_code = $response->regioncode;
        $row->region_name = $response->regionname;
        $row->city_type = $response->citytype;
        $row->city = $response->cityname;
        $row->street_type = $response->streettype;
        $row->street = $response->streetname;
        $row->house = !empty($response->house) ? $response->house : null;
        $row->office = !empty($response->office) ? $response->office : null;
        $row->new = $response->isnew;
        $row->created = (new DateTime($response->dtregistry))->format('Y-m-d H:i:s');
        $row->closed = strtotime($response->dtend) > time() ? null : $response->dtend;
        $row->okved1 = $response->okved1;
        $row->okved1_name = $response->okved1name;
        $row->modified = date('Y-m-d H:i:s');

        $exists = $this->get_by([
            'inn' => $row->inn,
            'ogrn' => $row->ogrn,
        ]);

        if ($exists && !empty($exists)) {
            foreach ($row as $fld => $val) {
                if ($fld == 'id') continue;
                $exists->{$fld} = $val;
            }
            $row = $exists;
            unset($exists, $row->category_name);
            $this->update($row->id, $row);
        } else {
            $row->id = $this->insert($row);
        }

        $row->category_name = self::$categories[$row->category];

        return $row;
    }

    private function requestApi($query)
    {
        $ch = curl_init($this->apiURL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HEADER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.140 Safari/537.36',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'mode' => 'quick',
                'query' => $query,
                'pageSize' => 1,
                'sortField' => 'NAME_EX',
                'sort' => 'ASC',
            ]),
        ]);

        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }
}