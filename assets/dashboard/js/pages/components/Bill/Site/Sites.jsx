import React, { Component } from 'react';

import { Layout }        from "@dashboardComponents/Layout/Page";
import Sort              from "@commonComponents/functions/sort";
import TopToolbar        from "@commonComponents/functions/topToolbar";

import { SiteFormulaire } from "@dashboardPages/components/Bill/Site/SiteForm";
import { SitesList } from "@dashboardPages/components/Bill/Site/SitesList";

const URL_DELETE_ELEMENT    = 'api_bill_sites_delete';
const URL_DELETE_GROUP      = 'api_bill_sites_delete_group';
const MSG_DELETE_ELEMENT    = 'Supprimer ce site ?';
const MSG_DELETE_GROUP      = 'Aucun site sélectionné.';
let SORTER = Sort.compareName;

let sorters = [
    { value: 0, label: 'Nom', identifiant: 'sorter-name' },
]

let sortersFunction = [Sort.compareName];

export class Sites extends Component {
    constructor(props) {
        super(props);

        this.state = {
            perPage: 10,
            currentPage: 0,
            sorter: SORTER,
            pathDeleteElement: URL_DELETE_ELEMENT,
            msgDeleteElement: MSG_DELETE_ELEMENT,
            pathDeleteGroup: URL_DELETE_GROUP,
            msgDeleteGroup: MSG_DELETE_GROUP,
            sessionName: "bill.sites.pagination",
            classes: props.classes ? props.classes : "main-content",
        }

        this.layout = React.createRef();

        this.handleGetData = this.handleGetData.bind(this);
        this.handleUpdateList = this.handleUpdateList.bind(this);
        this.handleSearch = this.handleSearch.bind(this);
        this.handlePerPage = this.handlePerPage.bind(this);
        this.handleChangeCurrentPage = this.handleChangeCurrentPage.bind(this);
        this.handleSorter = this.handleSorter.bind(this);

        this.handleContentList = this.handleContentList.bind(this);
        this.handleContentCreate = this.handleContentCreate.bind(this);
        this.handleContentUpdate = this.handleContentUpdate.bind(this);
    }

    handleGetData = (self) => { self.handleSetDataPagination(this.props.donnees); }

    handleUpdateList = (element, newContext=null) => { this.layout.current.handleUpdateList(element, newContext); }

    handleSearch = (search) => { this.layout.current.handleSearch(search, "site"); }

    handlePerPage = (perPage) => { TopToolbar.onPerPage(this, perPage, SORTER) }

    handleChangeCurrentPage = (currentPage) => { this.setState({ currentPage }); }

    handleSorter = (nb) => { SORTER = TopToolbar.onSorter(this, nb, sortersFunction, this.state.perPage) }

    handleContentList = (currentData, changeContext, getFilters, filters, data) => {
        const { perPage, currentPage } = this.state;

        return <SitesList onChangeContext={changeContext}
                             //filter-search
                             onSearch={this.handleSearch}
                             onDelete={this.layout.current.handleDelete}
                             onDeleteAll={this.layout.current.handleDeleteGroup}
                             //changeNumberPerPage
                             perPage={perPage}
                             onPerPage={this.handlePerPage}
                             //twice pagination
                             currentPage={currentPage}
                             onPaginationClick={this.layout.current.handleGetPaginationClick(this)}
                             taille={data.length}
                             //sorter
                             sorters={sorters}
                             onSorter={this.handleSorter}
                             //data
                             data={currentData} />
    }

    handleContentCreate = (changeContext) => {
        const { societyId, customers, counterSite, yearSite } = this.props;
        return <SiteFormulaire type="create" societyId={societyId} customers={JSON.parse(customers)}
                               counterSite={counterSite} yearSite={yearSite}
                               onChangeContext={changeContext} onUpdateList={this.handleUpdateList}/>
    }

    handleContentUpdate = (changeContext, element) => {
        const { societyId, customers, counterSite, yearSite } = this.props;
        return <SiteFormulaire type="update" societyId={societyId} customers={JSON.parse(customers)}
                               counterSite={counterSite} yearSite={yearSite}
                               element={element} onChangeContext={changeContext} onUpdateList={this.handleUpdateList}/>
    }

    render () {
        return <>
            <Layout ref={this.layout} {...this.state} onGetData={this.handleGetData}
                    onContentList={this.handleContentList}
                    onContentCreate={this.handleContentCreate} onContentUpdate={this.handleContentUpdate}
                    onChangeCurrentPage={this.handleChangeCurrentPage} />
        </>
    }
}
