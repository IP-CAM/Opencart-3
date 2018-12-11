<?php

class ControllerCheckoutOrderpay extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('checkout/orderpay');

        //echo $this->request->post['espayproduct']."<br>";
        //echo $this->request->post['payment_sgopayment_credit_card_mdr'];

        if (isset($this->request->post['espayproduct'])) { //   
            $MODULE_PAYMENT_ESPAY_MODE = $this->config->get('payment_sgopayment_environment');
            $sgopaymentid = $this->config->get('payment_sgopayment_id');
            $data ['MODULE_PAYMENT_ESPAY_MODE'] = $MODULE_PAYMENT_ESPAY_MODE;
            $data ['sgopaymentid'] = $sgopaymentid;

            // fee
            $espayproductOri = $this->request->post['espayproduct'];
            $espayproduct = explode(":", $espayproductOri);

            $bankCode = $espayproduct[0];
            $productCode = $espayproduct[1];
            $productName = $espayproduct[2];
            $orderId = $this->request->post['cartid'];

            $data['bankCode'] = $bankCode;
            $data['productCode'] = $productCode;
            $data['orderId'] = $orderId;


            $feeTransaction = 0;
            $feeMDR = 0;
            if ($productCode == 'BCAKLIKPAY') {
                $feeTransaction = ($this->config->get('payment_sgopayment_transaction_fee_bca_klikpay') == '') ? 0  : $this->config->get('payment_sgopayment_transaction_fee_bca_klikpay');
            } elseif ($productCode == 'EPAYBRI') {
                $feeTransaction = ($this->config->get('payment_sgopayment_transaction_fee_epay_bri') == '') ? 0  : $this->config->get('payment_sgopayment_transaction_fee_epay_bri');
            } elseif ($productCode == 'MANDIRIIB') {
                $feeTransaction = ($this->config->get('payment_sgopayment_transaction_fee_mandiri_ib') == '') ? 0  : $this->config->get('payment_sgopayment_transaction_fee_mandiri_ib');
            } elseif ($productCode == 'MANDIRIECASH') {
                $feeTransaction = ($this->config->get('payment_sgopayment_transaction_fee_mandiri_ecash') == '') ? 0  : $this->config->get('payment_sgopayment_transaction_fee_mandiri_ecash');
            } elseif ($productCode == 'CREDITCARD') {
                $this->load->model('extension/total/transaction_fee');

                $totalorder = ($this->model_extension_total_transaction_fee->gettotalorder($orderId));

                $feeMDR = str_replace('%', '', $this->request->post['payment_sgopayment_credit_card_mdr']);

                $feeCreditCard = ($this->config->get('payment_sgopayment_transaction_fee_credit_card') == '') ? 0  : $this->config->get('payment_sgopayment_transaction_fee_credit_card');

                $feeTransaction = floatval($feeCreditCard) + ((floatval($totalorder) + floatval($feeCreditCard)) * floatval($feeMDR) / 100);
            } elseif ($productCode == 'PERMATAATM') {
                $feeTransaction = ($this->config->get('payment_sgopayment_transaction_fee_permata_atm') == '') ? 0  : $this->config->get('payment_sgopayment_transaction_fee_permata_atm');
           
            } elseif ($productCode == 'MANDIRIATM') {
                $feeTransaction = ($this->config->get('payment_sgopayment_transaction_fee_mandiri_atm') == '') ? 0  : $this->config->get('payment_sgopayment_transaction_fee_mandiri_atm');
            } elseif ($productCode == 'BNIATM') {
                $feeTransaction = ($this->config->get('payment_sgopayment_transaction_fee_bni_atm') == '') ? 0  : $this->config->get('payment_sgopayment_transaction_fee_bni_atm');
            } elseif ($productCode == 'BRIATM') {
            $feeTransaction = ($this->config->get('payment_sgopayment_transaction_fee_bri_atm') == '') ? 0  : $this->config->get('payment_sgopayment_transaction_fee_bri_atm');
            } elseif ($productCode == 'BCAATM') {
            $feeTransaction = ($this->config->get('payment_sgopayment_transaction_fee_bca_atm') == '') ? 0  : $this->config->get('payment_sgopayment_transaction_fee_bca_atm');
            } elseif ($productCode == 'PERMATANETPAY') {
                $feeTransaction = ($this->config->get('payment_sgopayment_transaction_fee_permata_netpay') == '') ? 0  : $this->config->get('payment_sgopayment_transaction_fee_permata_netpay');
            } elseif ($productCode == 'DANAMONATM') {
                $feeTransaction = ($this->config->get('payment_sgopayment_transaction_fee_danamon_atm') == '') ? 0  : $this->config->get('payment_sgopayment_transaction_fee_danamon_atm');
            } elseif ($productCode == 'DANAMONOB') {
                $feeTransaction = ($this->config->get('payment_sgopayment_transaction_fee_danamon_ob') == '') ? 0  : $this->config->get('payment_sgopayment_transaction_fee_danamon_ob');
            } elseif ($productCode == 'BIIATM') {
                $feeTransaction = ($this->config->get('payment_sgopayment_transaction_fee_bii_atm') == '') ? 0  : $this->config->get('payment_sgopayment_transaction_fee_bii_atm');
            } elseif ($productCode == 'NOBUPAY') {
                $feeTransaction = ($this->config->get('payment_sgopayment_transaction_fee_nobupay') == '') ? 0  : $this->config->get('payment_sgopayment_transaction_fee_nobupay');
            } elseif ($productCode == 'FINPAY195') {
                $feeTransaction = ($this->config->get('payment_sgopayment_transaction_fee_finpay') == '') ? 0  : $this->config->get('payment_sgopayment_transaction_fee_finpay');
            } elseif ($productCode == 'MAYAPADAIB') {
                $feeTransaction = ($this->config->get('payment_sgopayment_transaction_fee_mayapada_ib') == '') ? 0  : $this->config->get('payment_sgopayment_transaction_fee_mayapada_ib');
            } elseif ($productCode == 'BNIDBO') {
                $feeTransaction = ($this->request->post['test'] == '') ? 0 : $this->request->post['test'];
            } elseif ($productCode == 'DKIIB') {
                $feeTransaction = ($this->request->post['test'] == '') ? 0 : $this->request->post['test'];
            } elseif ($productCode == 'MANDIRISMS') {
                $feeTransaction = ($this->request->post['test'] == '') ? 0 : $this->request->post['test'];
            } elseif ($productCode == 'MUAMALATATM') {
                $feeTransaction = ($this->request->post['test'] == '') ? 0 : $this->request->post['test'];
            } elseif ($productCode == 'XLTUNAI') {
                $feeTransaction = ($this->request->post['test'] == '') ? 0 : $this->request->post['test'];
            }
            $data ['fee'] = $feeTransaction;
            $data ['feecurenccy'] = $this->currency->format($this->tax->calculate($data['fee'],'','') , $this->session->data['currency']);
            // end fee

            // Display total
            $this->load->model('extension/total/transaction_fee');
            $dtotal = $this->{'model_extension_total_transaction_fee'}->gettotalorder($orderId) + $data['fee'];
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $totalpay = $this->currency->format($this->tax->calculate($dtotal, '','') , $this->session->data['currency']);
            } else {
                $totalpay = false;
            }
            $data['totalpay'] = $totalpay;

            //update fee di table order
            $this->load->model('extension/payment/sgopayment');
           // $this->model_extension_payment_sgopayment->updatepaymethod($feeTransaction, $orderId, $productCode,$dtotal);
            $this->model_extension_payment_sgopayment->insertfee($feeTransaction, $orderId, $productCode);


            if (!isset($this->session->data['vouchers'])) {
                $this->session->data['vouchers'] = array();
            }


            if ($this->cart->hasProducts() || !empty($this->session->data['vouchers'])) {

                $data['heading_title'] = $this->language->get('heading_title');

                $data['column_name'] = $this->language->get('column_name');
                $data['column_model'] = $this->language->get('column_model');
                $data['column_quantity'] = $this->language->get('column_quantity');
                $data['column_price'] = $this->language->get('column_price');
                $data['column_total'] = $this->language->get('column_total');
                $data ['espay_product_name'] = $productName;


                $data['button_confirm_and_pay'] = $this->language->get('button_confirm_and_pay');


                if (isset($this->session->data['success'])) {
                    $data['success'] = $this->session->data['success'];

                    unset($this->session->data['success']);
                } else {
                    $data['success'] = '';
                }

                $data['action'] = $this->url->link('checkout/orderpay');


                $data['products'] = array();


                $products = $this->cart->getProducts();

                foreach ($products as $product) {
                    $product_total = 0;

                    foreach ($products as $product_2) {
                        if ($product_2['product_id'] == $product['product_id']) {
                            $product_total += $product_2['quantity'];
                        }
                    }

                    if ($product['minimum'] > $product_total) {
                        $data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
                    }


                //foreach ($this->cart->getProducts() as $product) {
                $option_data = array();

                    foreach ($product['option'] as $option) {
                        if ($option['type'] != 'file') {
                            $value = $option['value'];
                        } else {
                            $filename = $this->encryption->decrypt($option['option_value']);

                            $value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
                        }

                        $option_data[] = array(
                            'name' => $option['name'],
                            'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                        );
                    }


                /*$order_data['products'][] = array(
                    'product_id' => $product['product_id'],
                    'name'       => $product['name'],
                    'model'      => $product['model'],
                    'option'     => $option_data,
                    'download'   => $product['download'],
                    'quantity'   => $product['quantity'],
                    'subtract'   => $product['subtract'],
                    'price'      => $product['price'],
                    'total'      => $product['total'],
                    'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
                    'reward'     => $product['reward']
                );
            }*/
                    // Display prices
                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) , $this->session->data['currency']);
                    } else {
                        $price = false;
                    }
                    // Display prices
                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $total = $this->currency->format($this->tax->calculate($product['price'] , $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'] , $this->session->data['currency']);
                    } else {
                        $total = false;
                    }




                    $profile_description = '';

                    if ($product['recurring']) {
                        $frequencies = array(
                            'day' => $this->language->get('text_day'),
                            'week' => $this->language->get('text_week'),
                            'semi_month' => $this->language->get('text_semi_month'),
                            'month' => $this->language->get('text_month'),
                            'year' => $this->language->get('text_year'),
                        );

                        if ($product['recurring_trial']) {
                            $recurring_price = $this->currency->format($this->tax->calculate($product['recurring_trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')));
                            $profile_description = sprintf($this->language->get('text_trial_description'), $recurring_price, $product['recurring_trial_cycle'], $frequencies[$product['recurring_trial_frequency']], $product['recurring_trial_duration']) . ' ';
                        }

                        $recurring_price = $this->currency->format($this->tax->calculate($product['recurring_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')),  $this->session->data['currency'] );

                        if ($product['recurring_duration']) {
                            $profile_description .= sprintf($this->language->get('text_payment_description'), $recurring_price, $product['recurring_cycle'], $frequencies[$product['recurring_frequency']], $product['recurring_duration']);
                        } else {
                            $profile_description .= sprintf($this->language->get('text_payment_until_canceled_description'), $recurring_price, $product['recurring_cycle'], $frequencies[$product['recurring_frequency']], $product['recurring_duration']);
                        }
                    }
                    
                    $data['products'][] = array(
                        'cart_id' => $product['cart_id'],
                        'name' => $product['name'],
                        'model' => $product['model'],
                        'option' => $option_data,
                        'quantity' => $product['quantity'],
                        'stock' => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                        'reward' => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                        'price' => $price,
                        'total' => $total,
                        'href' => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                        'remove' => $this->url->link('checkout/cart', 'remove=' . $product['cart_id']),
                        'recurring' => $product['recurring'],
                        'name' => $product['name'],
                        'profile_description' => $profile_description,
                    );
                }

                $data['products_recurring'] = array();

                // Gift Voucher
                $data['vouchers'] = array();

                if (!empty($this->session->data['vouchers'])) {
                    foreach ($this->session->data['vouchers'] as $cart_id => $voucher) {
                        $data['vouchers'][] = array(
                            'cart_id' => $cart_id,
                            'description' => $voucher['description'],
                            'amount' => $this->currency->format($voucher['amount'], $this->session->data['currency']),
                            'remove' => $this->url->link('checkout/cart', 'remove=' . $cart_id)
                        );
                    }
                }

                if (isset($this->request->post['next'])) {
                    $data['next'] = $this->request->post['next'];
                } else {
                    $data['next'] = '';
                }

                // Totals
                $this->load->model('setting/extension');

                $total_data = array();
                $total = 0;
                $taxes = $this->cart->getTaxes();

                // Display prices
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $sort_order = array();

                    $results = $this->model_setting_extension->getExtensions('total');
                   /*echo '<pre>';
                      var_dump($results);
                      echo '</pre>';*/ 
                    foreach ($results as $cart_id => $value) {
                        $sort_order[$cart_id] = $this->config->get($value['code'] . '_sort_order');
                    }

                    array_multisort($sort_order, SORT_ASC, $results);

                   /* echo '<pre>';
                      var_dump($results);
                      echo '</pre>'; */
                    foreach ($results as $result) {

                        
                         /*echo '<pre>';
                          var_dump($total_data);
                          echo '</pre>'; 
*/
                        if ($this->config->get($result['code'] . '_status')) {
                              /*echo '<pre>';
                              var_dump($result['code'] . '_status');
                              echo '</pre>'; */

                            $this->load->model('extension/total/' . $result['code']);

                             // $this->{'model_extension_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                        }

                        $sort_order = array();

                        /* echo '<pre>';
                          echo '</pre>'; */

                        foreach ($total_data as $cart_id => $value) {
                            $sort_order[$cart_id] = $value['sort_order'];
                        }
                        array_multisort($sort_order, SORT_ASC, $total_data);
                    }
                }

                /* echo '<pre>';
                  echo '</pre>'; */

                $data['totals'] = $total_data;

                //$data['continue'] = $this->url->link('common/home');

                $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');


                $data['dir_js'] = HTTPS_SERVER . "catalog/view/javascript/sgopayment.js";
                // $data['dir_js'] = $this->document->addScript('view/javascript/sgopayment.js');

                 $data ["back_url"] = $this->url->link('extension/payment/sgopayment/success') . "&order_id=" . $this->session->data ['order_id'];

                $this->load->model('setting/extension');

                $data['checkout_buttons'] = array();

                    $data['shipping_required'] = $this->cart->hasShipping();

                    $data['column_left'] = $this->load->controller('common/column_left');
                    $data['column_right'] = $this->load->controller('common/column_right');
                    $data['content_top'] = $this->load->controller('common/content_top');
                    $data['content_bottom'] = $this->load->controller('common/content_bottom');
                    $data['footer'] = $this->load->controller('common/footer');
                    $data['header'] = $this->load->controller('common/header');

                    $this->response->setOutput($this->load->view('checkout/orderpay', $data));    

            } else {
                $data['heading_title'] = $this->language->get('heading_title');

                $data['text_error'] = $this->language->get('text_empty');

                $data['button_continue'] = $this->language->get('button_continue');

                $data['continue'] = $this->url->link('common/home');

                unset($this->session->data['success']);


                $data['shipping_required'] = $this->cart->hasShipping();

                $data['column_left'] = $this->load->controller('common/column_left');
                $data['column_right'] = $this->load->controller('common/column_right');
                $data['content_top'] = $this->load->controller('common/content_top');
                $data['content_bottom'] = $this->load->controller('common/content_bottom');
                $data['footer'] = $this->load->controller('common/footer');
                $data['header'] = $this->load->controller('common/header');

                $this->response->setOutput($this->load->view('checkout/not_found', $data));

             
            }
        } else {
             $this->response->redirect($this->url->link('common/home', 'user_token=' . $this->session->data['user_token'] . '&SSL', true));
            // $this->redirect($this->url->link('common/home', '', 'SSL'));
        }
    }

}

?>
