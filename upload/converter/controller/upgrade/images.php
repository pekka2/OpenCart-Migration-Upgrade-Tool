<?php
class ControllerUpgradeImages extends Controller{
    public function index(){
		$this->language->load('upgrade/images');
                $this->lmodel->set('upgrade_images',$this->language->load('upgrade/images'));

		$this->load->model('upgrade/images');

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_upgrade_info'),
			'href'      => $this->url->link('upgrade/info'),
			'separator' => false
		);
	if( !empty( $this->request->post['skip'] ) ) {
		$this->data['msg_image_skipped'] = $this->language->get('msg_image_skipped');
		$this->data['skip'] = 'skip';
	} elseif( $this->request->post['step3'] ) { 
                  $this->data['step3'] = 'step3';
                  $this->data['upgrade_data'] = $this->model_upgrade_images->imagePaths( $this->request->post );
        }
                $this->data['simulate'] = ( !empty( $_POST['simulate'] ) ? true : false );
		$this->data['text_finish'] = $this->language->get('text_finish');
		$this->data['text_finish_text'] = $this->language->get('text_finish_text');
		$this->data['text_finish_note'] = $this->language->get('text_finish_note');
                $this->data['text_simulation'] = $this->language->get('text_simulation');
                $this->data['text_on'] = $this->language->get('text_on');
                $this->data['text_off'] = $this->language->get('text_off');

		$this->template = 'upgrade/images.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
   }
}
?>
