import React, { Component } from "react";

import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import Sort from "@commonFunctions/sort";
import List from "@commonFunctions/list";

import { NewsList } from "@managerPages/News/NewsList";

import { Search } from "@userComponents/Elements/Search";
import { Filter } from "@commonComponents/Elements/Filter";
import { ModalDelete } from "@userComponents/Shortcut/Modal";
import { LoaderElements } from "@userComponents/Elements/Loader";
import { Pagination, TopSorterPagination } from "@commonComponents/Elements/Pagination";

const URL_GET_DATA = "intern_api_fo_news_list";
const URL_DELETE_ELEMENT = "intern_api_fo_news_delete";

const SESSION_PERPAGE = "project.perpage.fo.news";
const SESSION_FILTERS = "project.filters.fo.news";

export class News extends Component {
	constructor (props) {
		super(props);

		let saveNbPerPage = sessionStorage.getItem(SESSION_PERPAGE);
		let perPage = saveNbPerPage !== null ? parseInt(saveNbPerPage) : 20;

		let saveFilters = props.highlight ? sessionStorage.getItem(SESSION_FILTERS) : null;

		this.state = {
			perPage: perPage,
			currentPage: 0,
			sorter: Sort.compareCreatedAtInverse,
			loadingData: true,
			filters: saveFilters !== null ? JSON.parse(saveFilters) : [],
			element: null,
		}

		this.pagination = React.createRef();
		this.delete = React.createRef();
	}

	componentDidMount = () => {
		this.handleGetData();
	}

	handleGetData = () => {
		const { perPage, sorter, filters } = this.state;
		List.getData(this, Routing.generate(URL_GET_DATA), perPage, sorter, this.props.highlight, filters, this.handleFilters);
	}

	handleUpdateData = (currentData) => {
		this.setState({ currentData })
	}

	handleSearch = (search) => {
		const { perPage, sorter, dataImmuable, filters } = this.state;
		List.search(this, 'news', search, dataImmuable, perPage, sorter, true, filters, this.handleFilters)
	}

	handleFilters = (filters, nData = null) => {
		const { dataImmuable, perPage, sorter } = this.state;
		return List.filter(this, 'visibility', nData ? nData : dataImmuable, filters, perPage, sorter, SESSION_FILTERS);
	}

	handleUpdateList = (element, context) => {
		const { data, dataImmuable, currentData, sorter } = this.state;
		List.updateListPagination(this, element, context, data, dataImmuable, currentData, sorter)
	}

	handlePaginationClick = (e) => {
		this.pagination.current.handleClick(e)
	}

	handleChangeCurrentPage = (currentPage) => {
		this.setState({ currentPage });
	}

	handlePerPage = (perPage) => {
		List.changePerPage(this, this.state.data, perPage, this.state.sorter, SESSION_PERPAGE);
	}

	handleModal = (identifiant, elem) => {
		this.delete.current.handleClick();
		this.setState({ element: elem })
	}

	render () {
		const { highlight } = this.props;
		const { data, currentData, element, loadingData, perPage, currentPage, filters } = this.state;

		let filtersItems = [
			{ value: 0, id: "f-v-all", label: "Tout le monde" },
			{ value: 1, id: "f-v-user", label: "Utilisateurs" },
		]

		return <>
			{loadingData
				? <LoaderElements />
				: <>
					<div>
						<div>
							<div>
								<Filter filters={filters} items={filtersItems} onFilters={this.handleFilters} />
							</div>
							<Search onSearch={this.handleSearch} placeholder="Rechercher par nom.." />
						</div>
					</div>

					<TopSorterPagination taille={data.length} currentPage={currentPage} perPage={perPage}
										 onClick={this.handlePaginationClick}
										 onPerPage={this.handlePerPage} />

					<NewsList data={currentData} highlight={parseInt(highlight)} onDelete={this.handleModal} />

					<Pagination ref={this.pagination} items={data} taille={data.length} currentPage={currentPage}
								perPage={perPage} onUpdate={this.handleUpdateData} onChangeCurrentPage={this.handleChangeCurrentPage} />

					<ModalDelete refModal={this.delete} element={element} routeName={URL_DELETE_ELEMENT}
								 title="Supprimer cette actualité" msgSuccess="Actualité supprimée."
								 onUpdateList={this.handleUpdateList}>
						Etes-vous sûr de vouloir supprimer définitivement cette actualité ?
					</ModalDelete>
				</>
			}
		</>
	}
}
