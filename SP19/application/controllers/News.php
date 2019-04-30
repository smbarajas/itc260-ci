<?php
// application/controllers/News.php
class News extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('news_model');
        $this->load->helper('url_helper');
        // Bad News Dudes
        $this->config->set_item('banner', 'Bad News Dudes');
    }
    public function index() {
        // Replace default title with 'this->' title
        $this->config->set_item('title', 'Bad News Dudes');
        // Nav Links
        $nav1 = $this->config->item('nav1');
        // var_dump($nav1);
        // die;
        $data['news'] = $this->news_model->get_news();
        $data['title'] = 'News archive';
        $this->load->view('news/index', $data);
    }
    public function view($slug = NULL) {
        // slug without dashes
        $dashless_slug = str_replace("-", " ", $slug);
        // uppercase slug words
        $dashless_slug = ucwords($dashless_slug);
        // Use dashless slug for title
        $this->config->set_item('title', 'Newsflash! - ' . $dashless_slug);
        $data['news_item'] = $this->news_model->get_news($slug);
        if (empty($data['news_item'])) {
            show_404();
        }
        $data['title'] = $data['news_item']['title'];
        $this->load->view('news/view', $data);
    }
    public function create() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['title'] = 'Create a news item';
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('text', 'Text', 'required');
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('news/create', $data);
        } else {
            // $this->news_model->set_news();
            // $this->load->view('news/success', $data);
            $slug = $this->news_model->set_news();
            if($slug!==false) { // slug sent
                feedback('Data entered successfully', 'info');
                redirect('news/view/' . $slug);
            } else { // error
                feedback('Data NOT entered!', 'error');
                redirect('news/create');
            }
        }
    }
}
