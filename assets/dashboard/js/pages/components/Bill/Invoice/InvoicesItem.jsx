import React, { Component } from 'react';

import Routing    from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import Sanitaze   from "@commonComponents/functions/sanitaze";
import helper     from "../functions/helper";

import { ButtonIcon, ButtonIconDropdown } from "@dashboardComponents/Tools/Button";

const STATUS_DRAFT = 0;
const STATUS_TO_PAY = 1;
const STATUS_PAID = 2;
const STATUS_PAID_PARTIAL = 3;

const URL_DOWNLOAD_QUOTATION = "api_bill_quotations_download"
const URL_DOWNLOAD_ELEMENT   = "api_bill_invoices_download"
const URL_DUPLICATE_ELEMENT  = "api_bill_invoices_duplicate";
const URL_SEND_ELEMENT       = "api_bill_invoices_send";
const URL_ARCHIVE_ELEMENT    = "api_bill_invoices_archive";
const URL_CREATE_AVOIR       = "user_bill_invoices_create_avoir";
const URL_DOWNLOAD_AVOIR     = "api_bill_avoirs_download";
const URL_CREATE_QUOTATION   = "admin_bill_invoices_create_quotation";

export class InvoicesItem extends Component {
    constructor(props) {
        super();

        this.handleDuplicate = this.handleDuplicate.bind(this);
        this.handleArchive = this.handleArchive.bind(this);
        this.handleSend = this.handleSend.bind(this);
    }

    handleDuplicate = (elem) => {
        helper.confirmAction(this, "create", elem, Routing.generate(URL_DUPLICATE_ELEMENT, {'id': elem.id}),
            "Copier cette facture ?", "La nouvelle facture sera en mode brouillon.", "Facture copiée avec succès.", 3)
    }

    handleArchive = (elem) => {
        helper.confirmAction(this, "update", elem, Routing.generate(URL_ARCHIVE_ELEMENT, {'id': elem.id}),
            "Archiver cette facture ?", "", "Facture archivée avec succès.")
    }

    handleSend = (elem) => {
        helper.confirmAction(this, "update", elem, Routing.generate(URL_SEND_ELEMENT, {'id': elem.id}),
            "Envoyer cette facture ?", elem.isSent ? "Un mail a déjà été envoyé" : "", "Facture envoyée avec succès.")
    }

    render () {
        const { elem, onChangeContext, onDelete, onGenerate, onPayement } = this.props;

        let dropdownItems = [
            {data: <div onClick={() => this.handleDuplicate(elem)}>Copier</div>},
            {data: <div onClick={() => this.handleSend(elem)}>Envoyer</div>},
            {data: <div className="dropdown-separator" />},
            {data: <a href={Routing.generate(URL_CREATE_QUOTATION, {'id': elem.id})}>Créer un devis</a>},
        ];

        if(!elem.isArchived){

            if(elem.quotationId){
                dropdownItems = [...dropdownItems, ...[
                    {data: <div className="dropdown-separator" />},
                    {data: <a href={Routing.generate(URL_DOWNLOAD_QUOTATION, {'id': elem.quotationId})} target="_blank">Voir le devis</a>},
                ]]
            }

            if(elem.status === STATUS_DRAFT){
                dropdownItems = [...[
                    {data: <div onClick={() => onChangeContext("update", elem)}>Modifier</div>},
                    {data: <div onClick={() => onDelete(elem)}>Supprimer</div>},
                    {data: <div className="dropdown-separator" />},
                    {data: <div onClick={() => onGenerate(elem)}>Finaliser</div>},
                    {data: <div className="dropdown-separator" />},
                ], ...dropdownItems]
            }

            if(elem.status !== STATUS_DRAFT){
                dropdownItems = [...[
                    {data: <div onClick={() => this.handleArchive(elem)}>Archiver</div>}
                ], ...dropdownItems]
            }

            if(elem.status === STATUS_TO_PAY){
                dropdownItems = [...[
                    {data: <div onClick={() => onPayement(elem)}>Entrer un paiement</div>},
                    {data: <div className="dropdown-separator" />},
                ], ...dropdownItems]
            }

            if(!elem.avoirId && elem.status !== STATUS_DRAFT){
                dropdownItems = [...dropdownItems, ...[
                    {data: <div className="dropdown-separator" />},
                    {data: <a href={Routing.generate(URL_CREATE_AVOIR, {'id': elem.id})}>Créer un avoir</a>},
                ]]
            }

            if(elem.avoirId){
                dropdownItems = [...dropdownItems, ...[
                    {data: <div className="dropdown-separator" />},
                    {data: <a href={Routing.generate(URL_DOWNLOAD_AVOIR, {'id': elem.avoirId})} target="_blank">Voir l'avoir</a>},
                ]]
            }
        }

        return <div className="item">
            <div className="item-content">
                <div className="item-body">
                    <div className="infos infos-col-7">
                        <div className="col-1">
                            <div className="sub">{elem.status === STATUS_DRAFT ? "-" : elem.numero}</div>
                            <div className="sub">{elem.refQuotation ? "(" + elem.refQuotation + ")" : ""}</div>
                        </div>
                        <div className="col-2">
                            <div className="name">
                                <span>{elem.toName}</span>
                            </div>
                            <span className="sub">{elem.toEmail}</span>
                        </div>
                        <div className="col-3">
                            <div className="sub">{elem.dateAtString}</div>
                        </div>
                        <div className="col-4">
                            <div className="sub">{elem.dueType !== 1 ? elem.dueAtString : "Acquittée"}</div>
                        </div>
                        <div className="col-5">
                            <div className="name">{Sanitaze.toFormatCurrency(elem.totalTtc)}</div>
                            {elem.status === STATUS_PAID_PARTIAL && elem.toPay !== 0 && <>
                                <div className="sub">({Sanitaze.toFormatCurrency(elem.totalTtc - elem.toPay)} encaissé)</div>
                            </>}
                        </div>
                        <div className="col-6">
                            <div className="badges">
                                <div className={"badge badge-" + elem.status}>{elem.statusString}</div>
                            </div>
                            {elem.isArchived && <div className="badge badge-default">Archivé</div>}
                        </div>
                        <div className="col-7 actions">
                            <ButtonIcon icon="file" element="a" target="_blank" onClick={Routing.generate(URL_DOWNLOAD_ELEMENT, {'id': elem.id})}>Télécharger</ButtonIcon>
                            <ButtonIconDropdown type="default" icon="more" items={dropdownItems} />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    }
}
