<?php


use WpPlugins\Winipayer\WiniPayer;

class WiniPayerPaymentGateway extends WC_Payment_Gateway
{

    /**
     * Creation du constructeur
     */
    public function __construct()
    {
        // Définissez les propriétés de la passerelle ici
        $this->id = '9668d260-3f24-4603-b254-7e7a65aab0e4'; // Identifiant unique pour la passerelle
        $this->icon = 'https://www.winibuilder.com/file/project/wb100023/config/72150d38-a6ad-4ed8-9244-621b380f70a4.png'; // URL de l'icône de la passerelle
        $this->method_title = 'WiniPayer Payment Gateway'; // Nom de la passerelle
        $this->method_description = 'Pay with our winipayer payment gateway'; // Description de la passerelle
        $this->has_fields = true;
        $this->pay_button_id = 'winipayer' . $this->id;

        $this->init_form_fields();
        $this->init_settings();

        $this->title = $this->get_option('title');

        $this->description = $this->get_option('description');
        $this->has_fields = $this->get_option('has_fields');
        $this->pay_button_id = $this->get_option('pay_button_id');

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

        add_filter('woocommerce_payment_gateways', [$this, 'add_to_gateways']);
    }

    public function add_to_gateways($gateways)
    {
        $gateways[] = $this;
        return $gateways;
    }

    public function process_admin_options()
    {

        $post_data = $this->get_post_data();
        $arrayValue = [];
        foreach ($this->get_form_fields() as $key => $field) {

            if ('title' !== $this->get_field_type($field)) {
                try {

                    $this->settings[$key] = $this->get_field_value($key, $field, $post_data);

                    $arrayValue[] = [
                        'id' => $key,
                        'type' => $field['type'],
                        'value' => $this->settings[$key],
                    ];
                } catch (Exception $e) {
                    $this->add_error($e->getMessage());
                }
            }
        }
        $arrayValue = json_encode($arrayValue);
    }

    /**
     * Creation et affichage des champs de configuration de winipayer
     * @return void
     */
    public function init_form_fields()
    {

        $pathsForm = __DIR__ . '/forms/index.json';

        $contenu = file_get_contents($pathsForm);

        $forms = json_decode($contenu, true);

        $this->form_fields = $forms;

    }


    /**
     * Creation de la commande puis redirection vers la page de paiement de winipayer
     * @param mixed $order_id
     * @return array
     */
    public function process_payment($order_id)
    {
        // Gérez ici la logique de paiement en utilisant votre classe Paiement

        // Exemple : Obtenez le montant de la commande
        $order = wc_get_order($order_id);
        $amount = $order->get_total();

        // Vérifiez si la clé apply_key est correcte

        $this->validateParams();

        // Créez une instance de votre classe Paiement
        $paiement = new WiniPayer();

        // Créez la facture en utilisant votre méthode createInvoice
        $result = $paiement->createInvoice($amount, 'Payment for Order #' . $order_id);

        // Vérifiez si la facture a été créée avec succès
        if (isset($result['success']) && $result['success']) {
            // Marquez la commande comme payée
            WC()->session->set('order', $order);

            // Redirigez l'utilisateur vers la page de confirmation
            return array(
                'result' => 'success',
                'redirect' => $result['results']['process_url'] ? $result['results']['process_url'] : $this->get_return_url($order)
            );
        } else {

            $messageErrors = isset($result['errors']) && !empty($result['errors']) ? $result['errors']['msg'] : '';
            // Gestion des erreurs
            return wc_add_notice('Payment error: ' . $messageErrors, 'error');
        }

        //$order->payment_complete();

        // Videz le panier
        //WC()->cart->empty_cart();
    }

    /**
     * Validation des parametres de configuration
     * @return void
     */
    private function validateParams()
    {

        $rowsOptions = array(
            WiniPayer::OPTION_WINIPAYER_ENV,
            WiniPayer::OPTION_WINIPAYER_APPLY_KEY,
            WiniPayer::OPTION_WINIPAYER_TOKEN_KEY,
            WiniPayer::OPTION_WINIPAYER_PRIVATE_KEY,
            WiniPayer::OPTION_WINIPAYER_CURRENCY,
            WiniPayer::OPTION_WINIPAYER_WPSECURE,
            WiniPayer::OPTION_WINIPAYER_CALLBACK_URL,
            WiniPayer::OPTION_WINIPAYER_CANCEL_URL,
            WiniPayer::OPTION_WINIPAYER_RETURN_URL
        );

        $errors = 0;
        $errors_row = '';

        foreach ($rowsOptions as $row) {

            $value = $this->get_option($row);

            if ($value == '') {
                $errors_row .= "Invalid or missing <strong> $row </strong> in the configuration of plugin's Winipayer<br>";
                $errors = 1;
            }
        }
        if ($errors === 1) {
            return wc_add_notice($errors_row, 'error');
        }
    }
}

?>