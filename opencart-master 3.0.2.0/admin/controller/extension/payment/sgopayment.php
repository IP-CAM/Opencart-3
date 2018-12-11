<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

class ControllerExtensionPaymentSgopayment extends Controller {

    private $error = array();
    private $sgopayment_ip = '116.90.162.170';

    public function index() {

        $this->load->language('extension/payment/sgopayment');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        $this->load->model('extension/payment/sgopayment');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $this->model_setting_setting->editSetting('payment_sgopayment', $this->request->post);             
            
            $this->session->data['success'] = $this->language->get('text_success');
            
            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
        }

        if (isset($this->error)) {
            $data['error'] = $this->error;
        } else {
            $data['error'] = array();
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        //add text for Environment
        $data['text_production'] = $this->language->get('text_production');
        $data['text_development'] = $this->language->get('text_development');
        //

        $data['text_all_zones'] = $this->language->get('text_all_zones');

        $data['entry_bank'] = $this->language->get('entry_bank');
        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_total'] = $this->language->get('entry_total');

        $data['entry_sgopayment_id'] = $this->language->get('entry_sgopayment_id');
        $data['entry_sgopayment_password'] = $this->language->get('entry_sgopayment_password');
        $data['entry_sgopayment_signaturekey'] = $this->language->get('entry_sgopayment_signaturekey');
        $data['entry_sgopayment_ip'] = $this->language->get('entry_sgopayment_ip');


        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_order_status_waiting'] = $this->language->get('entry_order_status_waiting');

        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $data['entry_status'] = $this->language->get('entry_status');
        //add language
        $data['entry_environment'] = $this->language->get('entry_environment');
        $data['entry_max_order_total'] = $this->language->get('entry_max_order_total');
        $data['entry_credit_card_mdr'] = $this->language->get('entry_credit_card_mdr');
        $data['entry_transaction_fee_bca_klikpay'] = $this->language->get('entry_transaction_fee_bca_klikpay');
        $data['entry_transaction_fee_epay_bri'] = $this->language->get('entry_transaction_fee_epay_bri');
        $data['entry_transaction_fee_mandiri_ib'] = $this->language->get('entry_transaction_fee_mandiri_ib');
        $data['entry_transaction_fee_mandiri_ecash'] = $this->language->get('entry_transaction_fee_mandiri_ecash');
        $data['entry_transaction_fee_credit_card'] = $this->language->get('entry_transaction_fee_credit_card');
        $data['entry_transaction_fee_permata_atm'] = $this->language->get('entry_transaction_fee_permata_atm');
        $data['entry_transaction_fee_danamon_ob'] = $this->language->get('entry_transaction_fee_danamon_ob');
        $data['entry_transaction_fee_danamon_atm'] = $this->language->get('entry_transaction_fee_danamon_atm');
        $data['entry_transaction_fee_mandiri_atm'] = $this->language->get('entry_transaction_fee_mandiri_atm');
        $data['entry_transaction_fee_bri_atm'] = $this->language->get('entry_transaction_fee_bri_atm');
        $data['entry_transaction_fee_bca_atm'] = $this->language->get('entry_transaction_fee_bca_atm');
        $data['entry_transaction_fee_bni_atm'] = $this->language->get('entry_transaction_fee_bni_atm');
        $data['entry_transaction_fee_bii_atm'] = $this->language->get('entry_transaction_fee_bii_atm');
        $data['entry_transaction_fee_permata_netpay'] = $this->language->get('entry_transaction_fee_permata_netpay');
        $data['entry_transaction_fee_nobupay'] = $this->language->get('entry_transaction_fee_nobupay');
        $data['entry_transaction_fee_finpay'] = $this->language->get('entry_transaction_fee_finpay');
        $data['entry_transaction_fee_mayapada_ib'] = $this->language->get('entry_transaction_fee_mayapada_ib');
        $data['entry_transaction_fee_bitcoin'] = $this->language->get('entry_transaction_fee_bitcoin');
        $data['entry_transaction_fee_label'] = $this->language->get('entry_transaction_fee_label');
        //
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_update'] = $this->language->get('button_update');

        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_payment'),
            'href'      => $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('extension/payment/sgopayment', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );
                
        $data['action'] = $this->url->link('extension/payment/sgopayment', 'user_token=' . $this->session->data['user_token'], 'SSL');
        
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');


        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['payment_sgopayment_geo_zone_id'])) {
            $data['payment_sgopayment_geo_zone_id'] = $this->request->post['payment_sgopayment_geo_zone_id'];
        } else {
            $data['payment_sgopayment_geo_zone_id'] = $this->config->get('payment_sgopayment_geo_zone_id');
        }

        if (isset($this->request->post['payment_sgopayment_sort_order'])) {
            $data['payment_sgopayment_sort_order'] = $this->request->post['payment_sgopayment_sort_order'];
        } else {
            $data['payment_sgopayment_sort_order'] = $this->config->get('payment_sgopayment_sort_order');
        }

        //add
        if (isset($this->request->post['payment_sgopayment_max_order_total'])) {
            $data['payment_sgopayment_max_order_total'] = $this->request->post['payment_sgopayment_max_order_total'];
        } else {
            $data['payment_sgopayment_max_order_total'] = $this->config->get('payment_sgopayment_max_order_total');
        }
        if (isset($this->request->post['payment_sgopayment_credit_card_mdr'])) {
            $data['payment_sgopayment_credit_card_mdr'] = $this->request->post['payment_sgopayment_credit_card_mdr'];
        } else {
            $data['payment_sgopayment_credit_card_mdr'] = $this->config->get('payment_sgopayment_credit_card_mdr');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_bca_klikpay'])) {
            $data['payment_sgopayment_transaction_fee_bca_klikpay'] = $this->request->post['payment_sgopayment_transaction_fee_bca_klikpay'];
        } else {
            $data['payment_sgopayment_transaction_fee_bca_klikpay'] = $this->config->get('payment_sgopayment_transaction_fee_bca_klikpay');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_epay_bri'])) {
            $data['payment_sgopayment_transaction_fee_epay_bri'] = $this->request->post['payment_sgopayment_transaction_fee_epay_bri'];
        } else {
            $data['payment_sgopayment_transaction_fee_epay_bri'] = $this->config->get('payment_sgopayment_transaction_fee_epay_bri');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_mandiri_ib'])) {
            $data['payment_sgopayment_transaction_fee_mandiri_ib'] = $this->request->post['payment_sgopayment_transaction_fee_mandiri_ib'];
        } else {
            $data['payment_sgopayment_transaction_fee_mandiri_ib'] = $this->config->get('payment_sgopayment_transaction_fee_mandiri_ib');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_mandiri_ecash'])) {
            $data['payment_sgopayment_transaction_fee_mandiri_ecash'] = $this->request->post['payment_sgopayment_transaction_fee_mandiri_ecash'];
        } else {
            $data['payment_sgopayment_transaction_fee_mandiri_ecash'] = $this->config->get('payment_sgopayment_transaction_fee_mandiri_ecash');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_credit_card'])) {
            $data['payment_sgopayment_transaction_fee_credit_card'] = $this->request->post['payment_sgopayment_transaction_fee_credit_card'];
        } else {
            $data['payment_sgopayment_transaction_fee_credit_card'] = $this->config->get('payment_sgopayment_transaction_fee_credit_card');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_permata_atm'])) {
            $data['payment_sgopayment_transaction_fee_permata_atm'] = $this->request->post['payment_sgopayment_transaction_fee_permata_atm'];
        } else {
            $data['payment_sgopayment_transaction_fee_permata_atm'] = $this->config->get('payment_sgopayment_transaction_fee_permata_atm');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_danamon_ob'])) {
            $data['payment_sgopayment_transaction_fee_danamon_ob'] = $this->request->post['payment_sgopayment_transaction_fee_danamon_ob'];
        } else {
            $data['payment_sgopayment_transaction_fee_danamon_ob'] = $this->config->get('payment_sgopayment_transaction_fee_danamon_ob');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_danamon_atm'])) {
            $data['payment_sgopayment_transaction_fee_danamon_atm'] = $this->request->post['payment_sgopayment_transaction_fee_danamon_atm'];
        } else {
            $data['payment_sgopayment_transaction_fee_danamon_atm'] = $this->config->get('payment_sgopayment_transaction_fee_danamon_atm');
        }
         if (isset($this->request->post['payment_sgopayment_transaction_fee_mandiri_atm'])) {
            $data['payment_sgopayment_transaction_fee_mandiri_atm'] = $this->request->post['payment_sgopayment_transaction_fee_mandiri_atm'];
        } else {
            $data['payment_sgopayment_transaction_fee_mandiri_atm'] = $this->config->get('payment_sgopayment_transaction_fee_mandiri_atm');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_bri_atm'])) {
            $data['payment_sgopayment_transaction_fee_bri_atm'] = $this->request->post['payment_sgopayment_transaction_fee_bri_atm'];
        } else {
            $data['payment_sgopayment_transaction_fee_bri_atm'] = $this->config->get('payment_sgopayment_transaction_fee_bri_atm');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_bni_atm'])) {
            $data['payment_sgopayment_transaction_fee_bni_atm'] = $this->request->post['payment_sgopayment_transaction_fee_bni_atm'];
        } else {
            $data['payment_sgopayment_transaction_fee_bni_atm'] = $this->config->get('payment_sgopayment_transaction_fee_bni_atm');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_bca_atm'])) {
            $data['payment_sgopayment_transaction_fee_bca_atm'] = $this->request->post['payment_sgopayment_transaction_fee_bca_atm'];
        } else {
            $data['payment_sgopayment_transaction_fee_bca_atm'] = $this->config->get('payment_sgopayment_transaction_fee_bca_atm');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_bii_atm'])) {
            $data['payment_sgopayment_transaction_fee_bii_atm'] = $this->request->post['payment_sgopayment_transaction_fee_bii_atm'];
        } else {
            $data['payment_sgopayment_transaction_fee_bii_atm'] = $this->config->get('payment_sgopayment_transaction_fee_bii_atm');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_permata_netpay'])) {
            $data['payment_sgopayment_transaction_fee_permata_netpay'] = $this->request->post['payment_sgopayment_transaction_fee_permata_netpay'];
        } else {
            $data['payment_sgopayment_transaction_fee_permata_netpay'] = $this->config->get('payment_sgopayment_transaction_fee_permata_netpay');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_nobupay'])) {
            $data['payment_sgopayment_transaction_fee_nobupay'] = $this->request->post['payment_sgopayment_transaction_fee_nobupay'];
        } else {
            $data['payment_sgopayment_transaction_fee_nobupay'] = $this->config->get('payment_sgopayment_transaction_fee_nobupay');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_finpay'])) {
            $data['payment_sgopayment_transaction_fee_finpay'] = $this->request->post['payment_sgopayment_transaction_fee_finpay'];
        } else {
            $data['payment_sgopayment_transaction_fee_finpay'] = $this->config->get('payment_sgopayment_transaction_fee_finpay');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_mayapada_ib'])) {
            $data['payment_sgopayment_transaction_fee_mayapada_ib'] = $this->request->post['payment_sgopayment_transaction_fee_mayapada_ib'];
        } else {
            $data['payment_sgopayment_transaction_fee_mayapada_ib'] = $this->config->get('payment_sgopayment_transaction_fee_mayapada_ib');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_bitcoin'])) {
            $data['payment_sgopayment_transaction_fee_bitcoin'] = $this->request->post['payment_sgopayment_transaction_fee_bitcoin'];
        } else {
            $data['payment_sgopayment_transaction_fee_bitcoin'] = $this->config->get('payment_sgopayment_transaction_fee_bitcoin');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_label'])) {
            $data['payment_sgopayment_transaction_fee_label'] = $this->request->post['payment_sgopayment_transaction_fee_label'];
        } else {
            $data['payment_sgopayment_transaction_fee_label'] = $this->config->get('payment_sgopayment_transaction_fee_label');
        }
        //
        //$data['productlist'] =$this->buildListProduct();
        

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();

        foreach ($languages as $language) {
            if (isset($this->error['bank_' . $language['language_id']])) {
                $data['error_bank_' . $language['language_id']] = $this->error['bank_' . $language['language_id']];
            } else {
                $data['error_bank_' . $language['language_id']] = '';
            }
        }

      

        $this->load->model('localisation/language');

        foreach ($languages as $language) {
            if (isset($this->request->post['sgopayment_bank_' . $language['language_id']])) {
                $data['sgopayment_bank_' . $language['language_id']] = $this->request->post['sgopayment_bank_' . $language['language_id']];
            } else {
                $data['sgopayment_bank_' . $language['language_id']] = $this->config->get('sgopayment_bank_' . $language['language_id']);
            }
        }


        $data['languages'] = $languages;

        if (isset($this->request->post['payment_sgopayment_total'])) {
            $data['payment_sgopayment_total'] = $this->request->post['payment_sgopayment_total'];
        } else {
            $data['payment_sgopayment_total'] = $this->config->get('payment_sgopayment_total');
        }

        if (isset($this->request->post['payment_sgopayment_id'])) {
            $data['payment_sgopayment_id'] = $this->request->post['payment_sgopayment_id'];
        } else {
            $data['payment_sgopayment_id'] = $this->config->get('payment_sgopayment_id');
        }

        if (isset($this->request->post['payment_sgopayment_password'])) {
            $data['payment_sgopayment_password'] = $this->request->post['payment_sgopayment_password'];
        } else {
            $data['payment_sgopayment_password'] = $this->config->get('payment_sgopayment_password');
        }

        if (isset($this->request->post['payment_sgopayment_signaturekey'])) {
            $data['payment_sgopayment_signaturekey'] = $this->request->post['payment_sgopayment_signaturekey'];
        } else {
            $data['payment_sgopayment_signaturekey'] = $this->config->get('payment_sgopayment_signaturekey');
        }

        if (isset($this->request->post['payment_sgopayment_ip'])) {
            $data['payment_sgopayment_ip'] = $this->request->post['payment_sgopayment_ip'];
        } else {

            if ($this->config->get('payment_sgopayment_ip') != "") {
                $data['payment_sgopayment_ip'] = $this->config->get('payment_sgopayment_ip');
            } else {
                $data['payment_sgopayment_ip'] = $this->payment_sgopayment_ip;
            }
        }



        if (isset($this->request->post['payment_sgopayment_order_status_id'])) {
            $data['payment_sgopayment_order_status_id'] = $this->request->post['payment_sgopayment_order_status_id'];
        } else {
            $data['payment_sgopayment_order_status_id'] = $this->config->get('payment_sgopayment_order_status_id');
        }

        if (isset($this->request->post['payment_sgopayment_order_status_waiting'])) {
            $data['payment_sgopayment_order_status_waiting'] = $this->request->post['payment_sgopayment_order_status_waiting'];
        } else {
            $data['payment_sgopayment_order_status_waiting'] = $this->config->get('payment_sgopayment_order_status_waiting');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['payment_sgopayment_geo_zone_id'])) {
            $data['payment_sgopayment_geo_zone_id'] = $this->request->post['payment_sgopayment_geo_zone_id'];
        } else {
            $data['payment_sgopayment_geo_zone_id'] = $this->config->get('payment_sgopayment_geo_zone_id');
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();



        if (isset($this->request->post['payment_sgopayment_status'])) {
            $data['payment_sgopayment_status'] = $this->request->post['payment_sgopayment_status'];
        } else {
            $data['payment_sgopayment_status'] = $this->config->get('payment_sgopayment_status');
        }

        if (isset($this->request->post['payment_sgopayment_sort_order'])) {
            $data['payment_sgopayment_sort_order'] = $this->request->post['payment_sgopayment_sort_order'];
        } else {
            $data['payment_sgopayment_sort_order'] = $this->config->get('payment_sgopayment_sort_order');
        }

        //add
        if (isset($this->request->post['payment_sgopayment_environment'])) {
            $data['payment_sgopayment_environment'] = $this->request->post['payment_sgopayment_environment'];
        } else {
            $data['payment_sgopayment_environment'] = $this->config->get('payment_sgopayment_environment');
        }

        if (isset($this->request->post['payment_sgopayment_max_order_total'])) {
            $data['payment_sgopayment_max_order_total'] = $this->request->post['payment_sgopayment_max_order_total'];
        } else {
            $data['payment_sgopayment_max_order_total'] = $this->config->get('payment_sgopayment_max_order_total');
        }
        if (isset($this->request->post['payment_sgopayment_credit_card_mdr'])) {
            $data['payment_sgopayment_credit_card_mdr'] = $this->request->post['payment_sgopayment_credit_card_mdr'];
        } else {
            $data['payment_sgopayment_credit_card_mdr'] = $this->config->get('payment_sgopayment_credit_card_mdr');
        }
        if (isset($this->request->post['payment_sgopayment_transaction_fee_bca_klikpay'])) {
            $data['payment_sgopayment_transaction_fee_bca_klikpay'] = $this->request->post['payment_sgopayment_transaction_fee_bca_klikpay'];
        } else {
            $data['payment_sgopayment_transaction_fee_bca_klikpay'] = $this->config->get('payment_sgopayment_transaction_fee_bca_klikpay');
        }

        //

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/sgopayment', $data));
    }

    public function install() {
        $this->load->model('extension/payment/sgopayment');
        $this->load->model('setting/setting');
        $this->model_extension_payment_sgopayment->install();
    }

    public function uninstall() {
        $this->load->model('extension/payment/sgopayment');
        $this->model_extension_payment_sgopayment->uninstall();
    }

    public function validate() {
        if (!$this->user->hasPermission('modify', 'extension/payment/sgopayment')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (empty($this->request->post['payment_sgopayment_id'])) {
            $this->error['payment_sgopayment_id'] = $this->language->get('error_payment_id');
        }

        if (empty($this->request->post['payment_sgopayment_password'])) {
            $this->error['payment_sgopayment_password'] = $this->language->get('error_password');
        }

        if (empty($this->request->post['payment_sgopayment_signaturekey'])) {
            $this->error['payment_sgopayment_signaturekey'] = $this->language->get('error_signaturekey');
        }

        if (empty($this->request->post['payment_sgopayment_total'])) {
            $this->error['payment_sgopayment_total'] = $this->language->get('error_total');
        }

        if (empty($this->request->post['payment_sgopayment_max_order_total'])) {
            $this->error['payment_sgopayment_max_order_total'] = $this->language->get('error_max_order_total');
        }

        if (empty($this->request->post['payment_sgopayment_transaction_fee_label'])) {
            $this->error['payment_sgopayment_transaction_fee_label'] = $this->language->get('error_payment_sgopayment_transaction_fee_label');
        }

        if ($this->request->post['payment_sgopayment_order_status_waiting'] == $this->request->post['payment_sgopayment_order_status_id']) {
            $this->error['payment_sgopayment_order_status'] = $this->language->get('error_status_same');
        }


        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    private function getListProduct() {
        $requestMerchant = new stdClass();
        $requestProduct = new stdClass();

        $this->load->model('extension/payment/sgopayment');

        $urlMerchant = 'https://sandbox-api.espay.id/rest/merchant/merchantinfo';
        $requestMerchant->key = $this->config->get('payment_sgopayment_id');
        //var_dump($requestMerchant);
        $responseMerchant = $this->Call($urlMerchant, $requestMerchant);
        //var_dump($responseMerchant);
        $responseMerchant = json_decode($responseMerchant);
        //var_dump($responseMerchant);
        //die();

        $this->model_extension_payment_sgopayment->insertProduct($responseMerchant);

        /* if ($responseMerchant->body->errorCode == '0000'){
          $requestProduct->merchantCode = $responseMerchant->body->commCode;
          $this->model_extension_payment_sgopayment->clearProduct();


          $urlProduct = 'http://116.90.162.173:20809/rest/merchant/merchantinfo';
          $products = $this->Call($urlProduct, $requestProduct);
          $products = json_decode($products);
          var_dump ($products);
          $this->model_extension_payment_sgopayment->insertProduct($products);

          } */
    }

    public function Call($url, $request) {
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

        curl_setopt($curl, CURLOPT_HEADER, false);
        //curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); //use http 1.1
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        //NOTE: skip SSL certificate verification (this allows sending request to hosts with self signed certificates, but reduces security)
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);


        //enable ssl version 3
        //this is added because mandiri ecash case that ssl version that have been not supported before
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);

        curl_setopt($curl, CURLOPT_VERBOSE, true);
        //save to temporary file (php built in stream), cannot save to php://memory
        $verbose = fopen('php://temp', 'rw+');
        curl_setopt($curl, CURLOPT_STDERR, $verbose);

        $response = curl_exec($curl);

        return $response;
    }

    private function buildListProduct() {
        $i = 0;
        $html = '';
        $products = $this->model_extension_payment_sgopayment->getProduct();

        foreach ($products as $product) {
            if ($i == 0) {
                $html .= ' <tr>
                            <td>Product</td>
                            <td><input checked type="checkbox" name="test" disabled="disabled"   >&nbsp' . $product['productname'] . '</td>
                            </tr>';
            } else {
                $html .= ' <tr>
                            <td>&nbsp;</td>
                            <td><input checked type="checkbox" name="test" disabled="disabled"   >&nbsp' . $product['productname'] . '</td>
                            </tr>';
            }
            $i++;
        }

        return $html;
    }

}

