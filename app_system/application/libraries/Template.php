<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//template
//created by : Julius Michael Rinaldo

class Template
{

    public function loadView($bodypath, $data, $type)
    {
        $ci =& get_instance();
        $ci->load->model("language_model");
        $ci->load->library("access_management");
        $data['languages'] = $ci->language_model->all();
        //$data['admin_access_sidemenu'] = $ci->access_management->generate_admin_sidemenu(1);
        
        if ($type == "admin") {
		$ci->load->model("company_model");
		$data['current_company_data'] = $ci->company_model->find(1,"",0);
            $data['head'] = $ci->load->view("template/includes/" . $type . "-head", $data, TRUE);
            $data['header'] = $ci->load->view("template/includes/" . $type . "-header", $data, TRUE);
            $data['sidebar'] = $ci->load->view("template/includes/" . $type . "-sidebar", $data, TRUE);
            $data['footer'] = $ci->load->view("template/includes/" . $type . "-footer", $data, TRUE);
            $data['foot'] = $ci->load->view("template/includes/" . $type . "-foot", $data, TRUE);
            $data['content'] = $ci->load->view($bodypath, $data, TRUE);
            $view = $ci->load->view("template/admin.php", $data, TRUE);
        } else if ($type == "public") {
            $data['content_view'] = $ci->load->view($bodypath, $data, TRUE);
            $view = $ci->load->view("template/public.php", $data, TRUE);
        } else {
            $view = $ci->load->view($bodypath, $data, TRUE);
        }

        $language = $this->setLanguage();
       
        $ci->load->model("language_model");
        $lang = $ci->language_model->setLanguage();
        $content = $ci->language_model->replaceLanguage($view, $lang);
        //$ci->access_management->generate_admin_sidemenu();
        echo $content;
        
    }

    public function setLanguage()
    {
        if (isset($_GET['langSelection'])) {
            $selLang = $_GET['langSelection'];
        } else {
            if (isset($_SESSION['language'])) {
                $selLang = $_SESSION['language'];
            } else {
                $selLang = "en";
            }
        }
        return $selLang;
    }

    public function replace_language($view, $selLang)
    {
        $ci =& get_instance();
        $ci->load->model("language_presentation_model");
        $languages = $ci->language_presentation_model->all();
        foreach ($languages as $language) {
            $view = str_replace($language['template'], $language[$selLang], $view);
        }
        return $view;
    }
    
    public function templateView($view)
    {
        $ci =& get_instance();
        $ci->load->model("language_model");
        $ci->load->library("access_management");
        $data['languages'] = $ci->language_model->all();
        //$data['admin_access_sidemenu'] = $ci->access_management->generate_admin_sidemenu(1);

        $language = $this->setLanguage();
       
        $ci->load->model("language_model");
        $lang = $ci->language_model->setLanguage();
        $content = $ci->language_model->replaceLanguage($view, $lang);
        return $content;
        
    }
}
