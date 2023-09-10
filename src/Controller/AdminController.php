<?php


namespace WpPlugins\Winipayer\Controller;

use WpPlugins\Winipayer\Winipayer;

class AdminController
{

    public function __construct()
    {
        $this->init_hooks();
    }

    public function init_hooks()
    {
        add_action('admin_menu', [$this, 'admin_menu']);
    }


    public function admin_menu()
    {
        /* add_submenu_page('woocommerce', '', __('WiniPayer', 'winipayer-woocommerce'), 'manage_woocommerce', 'wc-winipayer-plugins', [$this, 'output']); */

        // Créez le menu parent "Winipayer"
        add_menu_page(
            __('Winipayer', 'winipayer-woocommerce'),
            // Titre du menu principal
            __('Winipayer', 'winipayer-woocommerce'),
            // Texte affiché dans le menu
            'manage_woocommerce',
            // Capacité requise pour accéder au menu
            'winipayer-menu',
            // Identifiant unique du menu principal
            [$this, 'output'] // Fonction de rendu du menu principal
        );

        add_submenu_page(
            'winipayer-menu',
            // Menu parent (slug du menu principal)
            __('Transactions', 'winipayer-woocommerce'),
            // Titre du sous-menu "des Transactions"
            __('Transactions', 'winipayer-woocommerce'),
            // Texte affiché dans le menu
            'manage_woocommerce',
            // Capacité requise pour accéder au sous-menu
            'winipayer-transactions',
            // Identifiant unique du sous-menu
            [$this, 'transactions_page'] // Fonction de rendu de la page "Transactions"
        );
    }

    public function output()
    {
        Winipayer::render('page_home', []);
    }

    public function transactions_page()
    {


        $page = $_GET['paginate'] ?? 1;

        $search = $_GET['search'] ?? null;

        $env = $_GET['env'] ?? 'test';

        $dateStart = $_GET['date_start'] ?? null;

        $dateEnd = $_GET['date_end'] ?? null;

        $winipayer = new Winipayer();

        $results = $winipayer->listInvoice($search, $page, $dateStart, $dateEnd, $env);

        if (!isset($results['success']) && !$results['success']) {
            $results['results'] = [];
        }

        $currentPage = isset($results['paginate']) ? $results['paginate']['current_page'] : 1;

        $countPage = isset($results['paginate']) ? $results['paginate']['last_page'] : 1;

        $disabledPrevious = $currentPage - 1 == 0 ? 'disabled' : '';

        $disabledNext = $currentPage + 1 > $countPage ? 'disabled' : '';

        $pagination = "<nav aria-label='Page navigation example'>
                    <ul class='pagination'>
                    <li class='page-item $disabledPrevious'><a class='page-link' href='" . $this->PreviousLink($currentPage) . "'>Précédent</a></li>";

        for ($i = 1; $i <= $countPage; $i++) {
            $pagination .= "<li class='page-item " . $this->actived($currentPage, $i) . "'><a class='page-link' href='" . $this->IndexLink($i) . "'>" . $i . "</a></li>";
        }

        $pagination .= "<li class='page-item $disabledNext'><a class='page-link' href='" . $this->NextLink($currentPage, $countPage) . "'>Suivant</a></li>
                    </ul>
                </nav>";
        Winipayer::render('page_transaction', [
            'list_invoices' => $results['results']['invoices'] ?? [],
            'paginationLink' => $pagination ?? '',
            'totalItemsFound' => isset($results['paginate']) && !empty($results['paginate']['total']) ? $results['paginate']['total'] : 0
        ]);
    }



    private function PreviousLink(int $currentPage)
    {
        if ($currentPage - 1 == 0) {
            return '#';
        } elseif ($currentPage - 1 > 0) {

            $previous = $currentPage - 1;

            return $this->generateLink($previous);
        }
    }

    private function IndexLink(int $index)
    {

        return $this->generateLink($index);
    }

    private function actived($currentPage, $indexPage)
    {
        if ($currentPage == $indexPage) {
            return 'active';
        }
    }

    private function NextLink(int $currentPage, int $countPage)
    {
        if ($currentPage + 1 > $countPage) {
            return '#';
        } else {
            $next = $currentPage + 1;

            return $this->generateLink($next);
        }
    }

    private function generateLink($index)
    {
        $search = isset($_GET['search']) ? '&search=' . $_GET['search'] : '';

        $date_start = isset($_GET['date_start']) && !empty($_GET['date_start']) ? '&date_start=' . $_GET['date_start'] : '';

        $date_end = isset($_GET['date_end']) && !empty($_GET['date_end']) ? '&date_end=' . $_GET['date_end'] : '';

        $env = isset($_GET['env']) ? '&env=' . $_GET['env'] : '&env=test';

        $url = admin_url('admin.php?page=winipayer-transactions' . $search . '' . $env . '' . $date_start . '' . $date_end . '&paginate=' . $index);

        return $url;
    }

}