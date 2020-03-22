<div class="modal" id="ajouterRouteurModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Formulaire d'ajout d'un Routeur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajouterRouteurForm" method="POST" action="">
                    @csrf
                    <div class="form-group row">
                        <label for="nomRouteur" class="col-sm-3 col-form-label">Nom du Routeur *</label>
                        <div class="col-sm-9">
                            <input type="text" required class="form-control" id="nomRouteur" name="nom">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="prixRouteur" class="col-sm-3 col-form-label">Prix du Routeur *</label>
                        <div class="col-sm-9">
                            <input type="number" required min="0" class="form-control" id="prixRouteur" placeholder="en €" name="prix">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Valider</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="ajouterBaseModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Formulaire d'ajout d'une Base</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajouterBaseForm" method="POST" action="">
                    @csrf
                    <div class="form-group row">
                        <label for="nomBase" class="col-sm-3 col-form-label">Nom de la Base *</label>
                        <div class="col-sm-9">
                            <input type="text" required class="form-control" id="nomBase" name="nom">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="routeursPourBase" class="col-sm-3 col-form-label">Sélectionnez un Routeur *</label>
                        <div class="col-sm-9">
                            <select ui-select2 ng-model="routeurPourBase" required id="routeursPourBase" placeholder="Sélectionnez un routeur..." name="routeur" style="width:100%">
                            </select>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Valider</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="ajouterAnnonceurModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Formulaire d'ajout d'un Annonceur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajouterAnnonceurForm" method="POST" action="">
                    @csrf
                    <div class="form-group row">
                        <label for="nomAnnonceur" class="col-sm-4 col-form-label">Nom de l'Annonceur *</label>
                        <div class="col-sm-8">
                            <input type="text" required class="form-control" id="nomAnnonceur" name="nom">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="urlAnnonceur" class="col-sm-4 col-form-label">URL de l'Annonceur</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="urlAnnonceur" name="url">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="adresse_facturation" class="col-sm-4 col-form-label">Adresse de Facturation *</label>
                        <div class="col-sm-8">
                            <input type="text" required class="form-control" id="adresse_facturation" name="adresse_facturation">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email_comptabilite" class="col-sm-4 col-form-label">Email Comptabilité</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="email_comptabilite" name="email_comptabilite">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email_direction" class="col-sm-4 col-form-label">Email Direction</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="email_direction" name="email_direction">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email_production" class="col-sm-4 col-form-label">Email Production</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="email_production" name="email_production">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="delai_paiement" class="col-sm-4 col-form-label">Délai de Paiement *</label>
                        <div class="col-sm-8">
                            <input type="date" required class="form-control" id="delai_paiement" name="delai_paiement">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Valider</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="ajouterCampagneModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Formulaire d'ajout d'une Campagne</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajouterCampagneForm" method="POST" action="">
                    @csrf
                    <div class="form-group row">
                        <label for="nomCampagne" class="col-sm-4 col-form-label">Nom de la Campagne *</label>
                        <div class="col-sm-8">
                            <input type="text" required class="form-control" id="nomCampagne" name="nom">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="type_remuneration" class="col-sm-4 col-form-label">Type de rémuneration *</label>
                        <div class="col-sm-8">
                            <input type="text" required class="form-control" id="type_remuneration" name="type_remuneration">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="remuneration" class="col-sm-4 col-form-label">Rémuneration *</label>
                        <div class="col-sm-8">
                            <input type="number" min="0" required placeholder="en €" class="form-control" id="remuneration" name="remuneration">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="annonceursPourCampagne" class="col-sm-4 col-form-label">Sélectionnez un Annonceur *</label>
                        <div class="col-sm-8">
                            <select ui-select2 ng-model="annonceurPourCampagne" required id="annonceursPourCampagne" name="annonceur" style="width:100%">
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Valider</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="planifierEnvoiModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Formulaire de planification d'un envoi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="planifierEnvoiForm" method="POST" action="">
                    @csrf
                    <div class="form-group row">
                        <label for="annonceursPourPlanning" class="col-sm-5 col-form-label">Sélectionnez un Annonceur *</label>
                        <div class="col-sm-7">
                            <select ui-select2 ng-model="annonceurPourPlanning" required id="annonceursPourPlanning" name="annonceur" style="width:100%">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="campagnesPourPlanning" class="col-sm-5 col-form-label">Sélectionnez une Campagne *</label>
                        <div class="col-sm-7">
                            <select ui-select2 disabled ng-model="campagnePourPlanning" required id="campagnesPourPlanning" name="campagne" style="width:100%">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="routeursPourPlanning" class="col-sm-5 col-form-label">Sélectionnez un Routeur *</label>
                        <div class="col-sm-7">
                            <select ui-select2 ng-model="routeurPourPlanning" required id="routeursPourPlanning" name="routeur" style="width:100%">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="basesPourPlanning" class="col-sm-5 col-form-label">Sélectionnez une Base *</label>
                        <div class="col-sm-7">
                            <select ui-select2 disabled ng-model="basePourPlanning" required id="basesPourPlanning" name="base" style="width:100%">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="volume" class="col-sm-5 col-form-label">Volume envoyé *</label>
                        <div class="col-sm-7">
                            <input type="number" min="0" required class="form-control" id="volume" name="volume">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="date_envoi" class="col-sm-5 col-form-label">Date d'envoi *</label>
                        <div class="col-sm-7">
                            <input type="date" required class="form-control" id="date_envoi" name="date_envoi">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Valider</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>