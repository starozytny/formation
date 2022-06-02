import React, { Component } from 'react';

import Routing      from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import helper       from "@dashboardPages/components/Bill/functions/helper";

import { Button, ButtonIcon } from "@dashboardComponents/Tools/Button";
import { Alert }    from "@dashboardComponents/Tools/Alert";
import { Aside }    from "@dashboardComponents/Tools/Aside";
import { Search }   from "@dashboardComponents/Layout/Search";
import { Filter, FilterSelected } from "@dashboardComponents/Layout/Filter";
import { TopSorterPagination } from "@dashboardComponents/Layout/Pagination";

import { InvoicesItem }   from "@dashboardPages/components/Bill/Invoice/InvoicesItem";
import { InvoiceGenerateFormulaire } from "@dashboardPages/components/Bill/Invoice/InvoiceGenerate";
import { InvoicePayementFormulaire } from "@dashboardPages/components/Bill/Invoice/InvoicePayement";

const URL_GENERATE_INVOICE  = "api_bill_invoices_generate";
const URL_INDEX_ELEMENTS    = "admin_bill_invoices_index";

export class InvoicesList extends Component {
    constructor(props) {
        super(props);

        this.state = {
            dateInvoice: props.society.dateInvoiceJavascript ? new Date(props.society.dateInvoiceJavascript) : null,
            element: null
        }

        this.filter = React.createRef();
        this.asideGenerate = React.createRef();
        this.asidePayement = React.createRef();

        this.handleFilter = this.handleFilter.bind(this);
        this.handleGenerate = this.handleGenerate.bind(this);
        this.handlePayement = this.handlePayement.bind(this);
        this.handleUpdateDateInvoice = this.handleUpdateDateInvoice.bind(this);
        this.handleCloseAside = this.handleCloseAside.bind(this);
    }

    handleFilter = (e) => {
        this.filter.current.handleChange(e, true);
    }

    handleUpdateDateInvoice = (dateAt) => { this.setState({ dateInvoice: dateAt }) }

    handleCloseAside = () => {
        if(this.asideGenerate.current){
            this.asideGenerate.current.handleClose();
        }

        if(this.asidePayement.current){
            this.asidePayement.current.handleClose();
        }
    }

    handleGenerate = (elem) => { helper.checkDatesInvoice(this, "invoice", Routing.generate(URL_GENERATE_INVOICE, {'id': elem.id}), elem, this.state.dateInvoice); }

    handlePayement = (elem) => {
        this.setState({ element: elem })
        this.asidePayement.current.handleOpen();
    }

    render () {
        const { data, onChangeContext, taille, onGetFilters, filters, onSearch, perPage, onPerPage,
            onPaginationClick, currentPage, sorters, onSorter, onUpdateList } = this.props;
        const { element, dateInvoice } = this.state;

        let filtersLabel = ["Brouillon", "A régler", "Payée", "Partiel"];
        let filtersId    = ["f-br", "f-are", "f-pa", 'f-par'];

        let itemsFilter = [
            { value: 0, id: filtersId[0], label: filtersLabel[0] },
            { value: 1, id: filtersId[1], label: filtersLabel[1] },
            { value: 2, id: filtersId[2], label: filtersLabel[2] },
            { value: 3, id: filtersId[3], label: filtersLabel[3] },
        ];

        let contentGenerate = <InvoiceGenerateFormulaire type="invoice" onUpdateList={onUpdateList} onCloseAside={this.handleCloseAside}
                                                         onUpdateDateInvoice={this.handleUpdateDateInvoice} dateInvoice={dateInvoice}
                                                         element={element} key={element ? element.id : 1}/>

        let contentPayement = <InvoicePayementFormulaire onUpdateList={onUpdateList} onCloseAside={this.handleCloseAside}
                                                         element={element} key={element ? element.id : 1}/>

        return <>
            <div>
                <div className="toolbar">
                    <div className="item create">
                        <Button onClick={() => onChangeContext("create")}>Ajouter une facture</Button>
                    </div>
                    <div className="item filter-search">
                        <Filter ref={this.filter} items={itemsFilter} filters={filters} onGetFilters={onGetFilters} />
                        <Search onSearch={onSearch} placeholder="Recherche par numéro ou nom"/>
                        <FilterSelected filters={filters} itemsFiltersLabel={filtersLabel} itemsFiltersId={filtersId} onChange={this.handleFilter}/>
                    </div>
                </div>

                <TopSorterPagination sorters={sorters} onSorter={onSorter}
                                     currentPage={currentPage} perPage={perPage} onPerPage={onPerPage} taille={taille} onClick={onPaginationClick}/>

                <div className="items-table">
                    <div className="items items-default">
                        <div className="item item-header">
                            <div className="item-content">
                                <div className="item-body">
                                    <div className="infos infos-col-7">
                                        <div className="col-1">Numéro</div>
                                        <div className="col-2">Client</div>
                                        <div className="col-3">Date</div>
                                        <div className="col-4">Date échéance</div>
                                        <div className="col-5">Montant TTC</div>
                                        <div className="col-6">Statut</div>
                                        <div className="col-7 actions">Actions</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {data && data.length !== 0 ? data.map(elem => {
                            return <InvoicesItem {...this.props} onGenerate={this.handleGenerate} onPayement={this.handlePayement} elem={elem} key={elem.id}/>
                        }) : <Alert>Aucun résultat</Alert>}
                    </div>
                </div>

                <div className="page-actions">
                    <div className="selectors-actions">
                        <div className="item">
                            <ButtonIcon element="a" icon="briefcase" text="Voir les archivées" onClick={Routing.generate(URL_INDEX_ELEMENTS, {'archive': 1})} />
                        </div>
                    </div>
                </div>
            </div>

            <Aside ref={this.asideGenerate} content={contentGenerate} >Modifier la date de facturation</Aside>
            <Aside ref={this.asidePayement} content={contentPayement} >Entrer un paiement</Aside>
        </>
    }
}
