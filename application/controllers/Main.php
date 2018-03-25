<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Main
 *
 * @property CI_Config $config
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 * @property Business $business
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Output $output
 * @property CI_Cache $cache
 */
class Main extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('business');
        $this->load->helper('form');
    }

    public function index($format = 'html', $search = false)
    {
        $this->_checkFormat($format);

        if (!empty($this->input->post('search')) && !$search) {
            $search = $this->input->post('search');
        }

        $result = [
            'success' => false,
            'error' => null,
            'data' => null,
        ];

        if ($search == false) {
            $result['success'] = true;
            $result['data'] = $this->business->lastResults();
        } else {
            $result = $this->business->search($search, /*$this->input->method(TRUE) == 'POST'*/ 1);
        }

        if ($this->input->is_ajax_request() || $format == 'json') {
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'UTF-8')
                ->set_output(json_encode($result, JSON_UNESCAPED_UNICODE));
            return;
        }

        $this->load->view(!$search ? 'main' : 'search', $result);
    }

    private function _checkFormat($format)
    {
        if (!in_array($format, ['html', 'json'])) {
            $this->output
                ->set_status_header(200)
                ->set_output('Не верный формат');
            exit();
        }
    }
}