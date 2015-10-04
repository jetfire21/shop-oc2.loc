<?php
class ControllerModuleAffiliate extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->language->load('module/affiliate');

		$this->document->setTitle($this->language->get('heading_title'));

			
			$this->load->model('module/affiliatemmm');
				if ($this->request->server['REQUEST_METHOD'] != 'POST') {
					$this->model_module_affiliatemmm->createAffiliate();
				}
			if(isset($this->request->post['affiliate_level_commission'])){
				$count = 1;
				foreach ($this->request->post['affiliate_level_commission'] as $key => $value) {
					$value['level_id'] = $count;
					$this->request->post['affiliate_level_commission'][$key]['level_id'] = '' . $count;
					$count++;
				}
			}
			
            
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('affiliate', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/affiliate', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/affiliate', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['affiliate_module'])) {
			$this->data['modules'] = $this->request->post['affiliate_module'];
		} elseif ($this->config->get('affiliate_module')) { 
			$this->data['modules'] = $this->config->get('affiliate_module');
		}	

			
				$this->data['levels'] = array();
				if (isset($this->request->post['affiliate_level_commission'])) {
					$this->data['levels'] = $this->request->post['affiliate_level_commission'];
				} elseif ($this->config->get('affiliate_level_commission')) {
					$this->data['levels'] = $this->config->get('affiliate_level_commission');
				}
				$this->data['entry_affiliate_level'] = $this->language->get('entry_affiliate_level');
				$this->load->model('module/affiliatemmm');
				$this->data['entry_affiliate_commission'] = sprintf($this->language->get('entry_affiliate_commission'), $this->model_module_affiliatemmm->getAffiliateAllCommission(), '%');
				$this->data['entry_affiliate_count'] = $this->language->get('entry_affiliate_count');
				$this->data['button_add_level'] = $this->language->get('button_add_level');
				$this->data['entry_customer_lifetime'] = $this->language->get('entry_customer_lifetime');
				$this->data['entry_product_commission'] = $this->language->get('entry_product_commission');
				$this->data['affiliate_customer_lifetime'] = $this->config->get('affiliate_customer_lifetime');
				$this->data['affiliate_product_commission'] = $this->config->get('affiliate_product_commission');
			
            
					
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->template = 'module/affiliate.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/affiliate')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>