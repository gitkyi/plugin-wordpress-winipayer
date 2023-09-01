<?php


namespace WpPlugins\Winipayer;

use WiniPayerPaymentGateway;
use WpPlugins\Winipayer\Controller\AdminController;


class WiniPayer
{

    const OPTION_WINIPAYER_ENV = 'winipayer_env';
    const OPTION_WINIPAYER_APPLY_KEY = 'winipayer_apply_key';
    const OPTION_WINIPAYER_TOKEN_KEY = 'winipayer_token_key';
    const OPTION_WINIPAYER_PRIVATE_KEY = 'winipayer_private_key';
    const OPTION_WINIPAYER_WPSECURE = 'winipayer_wpsecure';
    const OPTION_WINIPAYER_VERSION = 'winipayer_version';
    const OPTION_WINIPAYER_CURRENCY = 'winipayer_currency';
    const OPTION_WINIPAYER_CANCEL_URL = 'winipayer_cancel_url';
    const OPTION_WINIPAYER_RETURN_URL = 'winipayer_return_url';
    const OPTION_WINIPAYER_CALLBACK_URL = 'winipayer_callback_url';
    const OPTION_WINIPAYER_X_AUTH_TOKEN = 'winipayer_auth_token';
    const OPTION_WINIPAYER_X_AUTH_EMAIL = 'winipayer_auth_email';
    const OPTION_WINIPAYER_ENDPOINT = 'winipayer_end_point';

    private array $_channel = [];
    private array $_items = [];
    private string $_customer_owner = '';
    private array $_custom_data = [];
    private array $_store = [];


    /**
     * Summary of __construct
     */
    public function __construct()
    {

        add_action('plugins_loaded', array($this, 'init_gateway'));

        if (is_admin()) {
            $adminController = new AdminController();
        }
    }


    public function init_gateway()
    {

        if (class_exists('WC_Payment_Gateway')) {
            require_once 'WiniPayerPaymentGateway.php'; // Remplacez par le chemin vers votre classe de passerelle
            add_filter('woocommerce_payment_gateways', array($this, 'add_winipayer_gateway'));
        }
    }

    public function add_winipayer_gateway($gateways)
    {
        $gateways[] = 'WiniPayerPaymentGateway'; // Nom de la classe de votre passerelle
        return $gateways;
    }

    /**
     * Rendu des vues
     */
    public static function render(string $name, array $args = [])
    {

        extract($args);

        $file = WINIPAYER_PLUGIN_DIR . "views/$name.php";

        ob_start();

        include_once($file);

        echo ob_get_clean();

    }

    /**
     * setChannel
     *
     * @param array $channel
     * @return WiniPayer
     */
    public function setChannel(array $channel): WiniPayer
    {
        $this->_channel = $channel;
        return $this;
    }

    /**
     * setCustomerOwner
     *
     * @param string $uuid
     * @return WiniPayer
     */
    public function setCustomerOwner(string $uuid): WiniPayer
    {
        if (!$this->_uuid($uuid)) {
            throw new \Exception('WiniPayer : setCustomerOwner => Invalid customer owner uuid.');
        }
        $this->_customer_owner = $uuid;
        return $this;
    }

    /**
     * setCustomData
     *
     * @param array $data
     * @return WiniPayer
     */
    public function setCustomData(array $data): WiniPayer
    {
        $this->_custom_data = $data;
        return $this;
    }

    /**
     * setItems
     *
     * @param array $items
     * @return WiniPayer
     */
    public function setItems(array $items): WiniPayer
    {

        foreach ($items as $key => $value) {

            if (!isset($value['name']) || !is_string($value['name']) || strlen($value['name']) < 2) {
                throw new \Exception('WiniPayer : setItems => Invalid item name.');
            }

            if (!isset($value['quantity']) || !is_int($value['quantity'])) {
                throw new \Exception('WiniPayer : setItems => Invalid item quantity.');
            }

            if (!isset($value['unit_price']) || !is_int($value['unit_price'])) {
                throw new \Exception('WiniPayer : setItems => Invalid item unit_price.');
            }

            $total_price = $value['quantity'] * $value['unit_price'];

            if (!isset($value['total_price']) || !is_int($value['total_price']) || $value['total_price'] != $total_price) {
                throw new \Exception('WiniPayer : setItems => Invalid item total_price.');
            }

            $this->_items[] = $value;
        }

        return $this;
    }

    public function setStore(array $store): WiniPayer
    {
        $this->_store = $store;
        return $this;
    }

    /**
     * createInvoice
     *
     * @param float $amount
     * @param string $description
     * @param string $currency
     * @param string $cancel_url
     * @param string $return_url
     * @param string $callback_url
     * @return array
     */
    public function createInvoice(float $amount, string $description, string $currency = 'xof', string $cancel_url = '', string $return_url = '', string $callback_url = ''): array
    {

        $gateway = new WiniPayerPaymentGateway();

        $params = [
            'env' => $gateway->get_option(self::OPTION_WINIPAYER_ENV),
            'version' => empty($gateway->get_option(self::OPTION_WINIPAYER_VERSION)) ? 'v1' : $gateway->get_option(self::OPTION_WINIPAYER_VERSION),
            'wpsecure' => $gateway->get_option(self::OPTION_WINIPAYER_WPSECURE),
            'amount' => $amount,
            'currency' => $gateway->get_option(self::OPTION_WINIPAYER_CURRENCY) ?? $currency,
            'description' => $description,
            'cancel_url' => $gateway->get_option(self::OPTION_WINIPAYER_CANCEL_URL) ?? $cancel_url,
            'return_url' => $gateway->get_option(self::OPTION_WINIPAYER_RETURN_URL) ?? $return_url,
            'callback_url' => $gateway->get_option(self::OPTION_WINIPAYER_CALLBACK_URL) ?? $callback_url,
        ];

        if (!empty($this->_channel)) {
            $params['channel'] = $this->_channel;
        }
        if (!empty($this->_customer_owner)) {
            $params['customer_owner'] = json_encode($this->_customer_owner);
        }
        if (!empty($this->_items)) {
            $params['items'] = json_encode($this->_items);
        }
        if (!empty($this->_custom_data)) {
            $params['custom_data'] = json_encode($this->_custom_data);
        }
        if (!empty($this->_store)) {
            $params['store'] = json_encode($this->_store);
        }

        $headers = [
            'X-Merchant-Apply' => $gateway->get_option(self::OPTION_WINIPAYER_APPLY_KEY),
            'X-Merchant-Token' => $gateway->get_option(self::OPTION_WINIPAYER_TOKEN_KEY),
        ];

        return $this->_curl('POST', '/transaction/invoice/create', $params, $headers);
    }

    public function detailInvoice(string $uuid): array
    {

        $gateway = new WiniPayerPaymentGateway();

        if (!$this->_uuid($uuid)) {
            throw new \Exception('WiniPayer : detailInvoice => Invalid invoice uuid.');
        }

        $params = [
            'env' => $gateway->get_option(self::OPTION_WINIPAYER_ENV),
            'version' => empty($gateway->get_option(self::OPTION_WINIPAYER_VERSION)) ? 'v1' : $gateway->get_option(self::OPTION_WINIPAYER_VERSION),
        ];

        $headers = [
            'X-Merchant-Apply' => $gateway->get_option(self::OPTION_WINIPAYER_APPLY_KEY),
            'X-Merchant-Token' => $gateway->get_option(self::OPTION_WINIPAYER_TOKEN_KEY),
        ];

        return $this->_curl('POST', '/transaction/invoice/detail/' . $uuid, $params, $headers);
    }


    /**
     * Summary of listInvoice
     * @return array
     */
    public function listInvoice(array $params): array
    {

        $gateway = new WiniPayerPaymentGateway();

        $headers = [
            'X-Auth-Token' => $gateway->get_option(self::OPTION_WINIPAYER_X_AUTH_TOKEN),
            'X-Auth-Email' => $gateway->get_option(self::OPTION_WINIPAYER_X_AUTH_EMAIL),
        ];

        return $this->_curl('POST', '/user/invoice/list', $params, $headers);
    }


    public function valideInvoice(string $uuid, float $amount): bool
    {

        $gateway = new WiniPayerPaymentGateway();

        if (!$this->_uuid($uuid)) {
            throw new \Exception('WiniPayer : detailInvoice => Invalid invoice uuid.');
        }

        $response = $this->detailInvoice($uuid);

        if (!isset($response['success']) || $response['success'] !== true) {
            return false;
        }

        $invoice = $response['results'];

        $id = $invoice['uuid'] ?? '';
        $hash = $invoice['hash'] ?? '';
        $env = $invoice['env'] ?? '';
        $state = $invoice['state'] ?? '';
        $total = $invoice['amount'] ?? 0;

        if (
            $id !== $uuid ||
            hash('sha256', $gateway->get_option(self::OPTION_WINIPAYER_PRIVATE_KEY)) !== $hash ||
            $env !== $gateway->get_option(self::OPTION_WINIPAYER_ENV) ||
            $state !== 'success' ||
            $total < $amount
        ) {
            return false;
        }

        return true;
    }

    /**
     * For call winipayer API
     *
     * @param string $method
     * @param string $link
     * @param array $params
     * @param array $headers
     * @return array
     */
    private function _curl(string $method = 'POST', string $link = '', array $params = [], array $headers = []): array
    {

        $url = 'https://api.winipayer.com' . $link;

        $headerFields = [];
        foreach ($headers as $key => $value) {
            $headerFields[] = $key . ': ' . $value;
        }

        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $params,
                CURLOPT_HTTPHEADER => $headerFields,
            )
        );

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            throw new \Exception($error);
        }

        curl_close($curl);

        return json_decode($response, true);
    }

    /**
     * Check if uuid is valide
     *
     * @param string $uuid
     * @return boolean
     */
    private function _uuid(string $uuid): bool
    {
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';
        return preg_match($pattern, $uuid) === 1;
    }
}