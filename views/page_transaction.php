<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <?php if (isset($_GET['search']) || isset($_GET['date_start']) || isset($_GET['date_end'])) { ?>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12" style="margin-top:25px">
                                <div class="alert alert-success show" role="alert">
                                    <h4><strong>
                                            <?php echo $totalItemsFound; ?>
                                            <?php echo $totalItemsFound > 1 ? 'Eléments' : 'Elément'; ?>
                                            <?php echo $totalItemsFound > 1 ? 'trouvés' : 'trouvé'; ?> !!!
                                        </strong></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-md-3 col-xs-12 col-sm-12" style="margin-top:20px;">
                    <h1 class="page-header">Liste transactions</h1>
                </div>
                <div class="col-md-9 col-xs-12 col-sm-12">
                    <form class="form-inline page-header  pull-right"
                        action="<?php echo admin_url('admin.php') . '?page=winipayer-transactions' ?>" method="GET">
                        <div class="form-group mb-2">
                            <label for="Env">Environnement de la transaction</label>
                            <select name="env" id="env" class="form-control" style="width: 100% !important;">
                                <option value="test" <?php echo isset($_GET['env']) && $_GET['env'] === 'test' ? 'selected' : '' ?>>Test</option>
                                <option value="prod" <?php echo isset($_GET['env']) && $_GET['env'] === 'prod' ? 'selected' : '' ?>>Production</option>
                            </select>
                        </div>
                        <div class="form-group mx-sm-3 mb-2" style="margin-top:20px;">
                            <label for="date_start">Date debut:</label>
                            <input type="date" name="date_start"
                                value="<?php echo isset($_GET['date_start']) ? $_GET['date_start'] : ''; ?>"
                                class="form-control" id="date_start" placeholder="Date Debut">
                        </div>
                        <div class="form-group mx-sm-3 mb-2" style="margin-top:20px;">
                            <label for="date_end">Date Fin:</label>
                            <input type="date" name="date_end"
                                value="<?php echo isset($_GET['date_end']) ? $_GET['date_end'] : ''; ?>"
                                class="form-control" id="date_end" placeholder="Date Fin">
                        </div>
                        <div class="form-group mx-sm-3 mb-2" style="margin-top:20px;">
                            <label for="search" class="sr-only">search</label>
                            <input type="text" name="search"
                                value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>"
                                class="form-control" id="search" placeholder="Recherche">
                        </div>
                        <input type="hidden" name="page" value="winipayer-transactions">
                        <button type="submit" class="btn btn-primary mb-2" style="margin-top:20px;">Rechercher</button>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Invoice ID</th>
                            <th>Boutique</th>
                            <th>Montant T.</th>
                            <th>Operateur</th>
                            <th>Ref Operateur</th>
                            <th>Env.</th>
                            <th>Wpsecure</th>
                            <th>Etat</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (is_array($list_invoices) && !empty($list_invoices)) {
                            $count = 1;
                            foreach ($list_invoices ?? [] as $invoice) { ?>
                                <tr>
                                    <td>
                                        <?php echo $count++; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $chaine_tronquee = substr($invoice['uuid'], 0, 19);
                                        $chaine_tronquee .= '...';
                                        echo $chaine_tronquee ?? '--' ?>
                                    </td>
                                    <td>
                                        <?php echo $invoice['store']['name'] ?? '--'; ?>
                                    </td>
                                    <td>
                                        <?php echo $invoice['amount'] ? number_format($invoice['amount'], '2', '.', ' ') . ' ' . $invoice['currency'] ?? '--' : '--'; ?>
                                    </td>
                                    <td>
                                        <?php echo $invoice['operator'] ?? '--'; ?>
                                    </td>
                                    <td>
                                        <?php echo $invoice['operator_ref'] ?? '--'; ?>
                                    </td>
                                    <td>
                                        <?php echo $invoice['env'] ?? '--'; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($invoice['wpsecure'] === true && empty($invoice['wpsecure_validate'])) { ?>
                                            <a href="#" class="btn btn-primary" target="_blank"> Valider la facture </a>
                                        <?php } elseif ($invoice['wpsecure'] === true && !empty($invoice['wpsecure_validate'])) { ?>
                                            <p>
                                                <?php echo $invoice['wpsecure_validate']; ?>
                                            </p>
                                        <?php } elseif (!$invoice['wpsecure']) { ?>
                                            <button class="btn btn-warning">
                                                <span class="badge badge-warning">
                                                    Désactiver
                                                </span>
                                            </button>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php
                                        $state = '';
                                        if ($invoice['state'] == 'success') {
                                            $state = 'success';
                                        } elseif ($invoice['state'] == 'waiting') {
                                            $state = 'warning';
                                        } elseif ($invoice['state'] == 'cancel') {
                                            $state = 'danger';
                                        }
                                        ?>
                                        <button class="btn btn-<?php echo $state ?>">
                                            <span class="badge badge-<?php echo $state ?>">
                                                <?php echo $invoice['state'] ?? '--'; ?>
                                            </span>
                                        </button>
                                    </td>
                                    <td>
                                        <?php echo $invoice['created_at'] ?? '--'; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo $invoice['checkout_receipt'] ?? '--'; ?>" class="btn btn-info"
                                            target="_blank" title="Voir le reçu">
                                            <span class="dashicons dashicons-welcome-view-site"></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php }
                        } else {
                            ?>
                            <tr>
                                <td colspan="11" class="text-center mt-5">Aucune donnée disponible</td>
                            </tr>
                            <?php
                        } ?>
                    </tbody>
                </table>
                <?php echo $paginationLink ?? ''; ?>
            </div>
        </div>
    </div>
</div>