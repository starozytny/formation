import React, { Component } from "react";

import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import Sort from "@commonFunctions/sort";
import List from "@commonFunctions/list";

import { WorkersList } from "@userPages/Worker/WorkersList";

import { Search } from "@userComponents/Elements/Search";
import { ModalDelete } from "@userComponents/Shortcut/Modal";
import { LoaderElements } from "@userComponents/Elements/Loader";
import { Pagination } from "@commonComponents/Elements/Pagination";

const URL_GET_DATA = "intern_api_fo_workers_list";
const URL_DELETE_ELEMENT = "intern_api_fo_workers_delete";

export class Workers extends Component {
	constructor(props) {
		super(props);

		this.state = {
			perPage: 7,
			currentPage: 0,
			sorter: Sort.compareLastname,
			loadingData: true,
			element: null,
		}

		this.pagination = React.createRef();
		this.delete = React.createRef();
	}

	componentDidMount = () => { this.handleGetData(); }

	handleGetData = () => {
		const { perPage, sorter } = this.state;
		List.getData(this, Routing.generate(URL_GET_DATA), perPage, sorter, this.props.highlight);
	}

	handleUpdateData = (currentData) => { this.setState({ currentData }) }

	handleSearch = (search) => {
		const { perPage, sorter, dataImmuable } = this.state;
		List.search(this, 'worker', search, dataImmuable, perPage, sorter)
	}

	handleUpdateList = (element, context) => {
		const { data, dataImmuable, currentData, sorter } = this.state;
		List.updateListPagination(this, element, context, data, dataImmuable, currentData, sorter)
	}

	handleChangeCurrentPage = (currentPage) => { this.setState({ currentPage }); }

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
							<Search onSearch={this.handleSearch} placeholder="Rechercher par nom ou prénom.."/>
						</div>
					</div>

					<WorkersList data={currentData} highlight={parseInt(highlight)} onDelete={this.handleModal} />

					<Pagination ref={this.pagination} items={data} taille={data.length} currentPage={currentPage}
								perPage={perPage} onUpdate={this.handleUpdateData} onChangeCurrentPage={this.handleChangeCurrentPage}/>

					<ModalDelete refModal={this.delete} element={element} routeName={URL_DELETE_ELEMENT}
								 title="Supprimer ce membre de l'équipe" msgSuccess="Membre supprimé de l'équipe."
								 onUpdateList={this.handleUpdateList}>
						Etes-vous sûr de vouloir supprimer définitivement ce membre de l'équipe ?
					</ModalDelete>
				</>
			}
		</>
	}
}
