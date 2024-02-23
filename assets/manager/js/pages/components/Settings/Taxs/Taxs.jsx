import React, { Component } from "react";

import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import Sort from "@commonFunctions/sort";
import List from "@commonFunctions/list";

import { TaxsList } from "@managerPages/Settings/Taxs/TaxsList";

import { Search } from "@commonComponents/Elements/Search";
import { ModalDelete } from "@commonComponents/Shortcut/Modal";
import { LoaderElements } from "@commonComponents/Elements/Loader";
import { Pagination, TopSorterPagination } from "@commonComponents/Elements/Pagination";

const URL_GET_DATA        = "intern_api_fo_settings_taxs_list";
const URL_DELETE_ELEMENT  = "intern_api_fo_settings_taxs_delete";

const SESSION_PERPAGE = "project.perpage.fo.taxs";

export class Taxs extends Component {
    constructor(props) {
        super(props);

        let saveNbPerPage = sessionStorage.getItem(SESSION_PERPAGE);
        let perPage = saveNbPerPage !== null ? parseInt(saveNbPerPage) : 20;

        this.state = {
            perPage: perPage,
            currentPage: 0,
            sorter: Sort.compareTaux,
            loadingData: true,
            element: null,
        }

        this.pagination = React.createRef();
        this.delete = React.createRef();
    }

    componentDidMount = () => { this.handleGetData(); }

    handleGetData = () => {
        const { perPage, sorter, filters } = this.state;
        List.getData(this, Routing.generate(URL_GET_DATA), perPage, sorter, this.props.highlight);
    }

    handleUpdateData = (currentData) => { this.setState({ currentData }) }

    handleSearch = (search) => {
        const { perPage, sorter, dataImmuable } = this.state;
        List.search(this, 'tax', search, dataImmuable, perPage, sorter)
    }

    handleUpdateList = (element, context) => {
        const { data, dataImmuable, currentData, sorter } = this.state;
        List.updateListPagination(this, element, context, data, dataImmuable, currentData, sorter)
    }

    handlePaginationClick = (e) => { this.pagination.current.handleClick(e) }

    handleChangeCurrentPage = (currentPage) => { this.setState({ currentPage }); }

    handlePerPage = (perPage) => { List.changePerPage(this, this.state.data, perPage, this.state.sorter, SESSION_PERPAGE); }

    handleModal = (identifiant, elem) => {
        this.delete.current.handleClick();
        this.setState({ element: elem })
    }

    render () {
        const { highlight } = this.props;
        const { data, currentData, element, loadingData, perPage, currentPage } = this.state;

        return <>
            {loadingData
                ? <LoaderElements />
                : <>
                    <div className="toolbar">
                        <div className="col-1">
                            <Search onSearch={this.handleSearch} placeholder="Rechercher par code ou taux.."/>
                        </div>
                    </div>

                    <TopSorterPagination taille={data.length} currentPage={currentPage} perPage={perPage}
                                         onClick={this.handlePaginationClick}
                                         onPerPage={this.handlePerPage} />

                    <TaxsList data={currentData} highlight={parseInt(highlight)} onDelete={this.handleModal} />

                    <Pagination ref={this.pagination} items={data} taille={data.length} currentPage={currentPage}
                                perPage={perPage} onUpdate={this.handleUpdateData} onChangeCurrentPage={this.handleChangeCurrentPage}/>

                    <ModalDelete refModal={this.delete} element={element} routeName={URL_DELETE_ELEMENT}
                                 title="Supprimer cette taxe" msgSuccess="Taxe supprimée."
                                 onUpdateList={this.handleUpdateList} >
                        Etes-vous sûr de vouloir supprimer définitivement cette taxe ?
                    </ModalDelete>
                </>
            }
        </>
    }
}
