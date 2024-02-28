import React, { Component } from "react";

import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import Sort from "@commonFunctions/sort";
import List from "@commonFunctions/list";

import { OrdersList } from "@userPages/Order/OrdersList";

import { LoaderElements } from "@userComponents/Elements/Loader";
import { Pagination } from "@commonComponents/Elements/Pagination";

const URL_GET_DATA = "intern_api_fo_orders_list";

export class Orders extends Component {
	constructor(props) {
		super(props);

		this.state = {
			perPage: 10,
			currentPage: 0,
			sorter: Sort.compareCreatedAtInverse,
			loadingData: true,
			element: null,
		}

		this.pagination = React.createRef();
	}

	componentDidMount = () => { this.handleGetData(); }

	handleGetData = () => {
		const { perPage, sorter } = this.state;
		List.getData(this, Routing.generate(URL_GET_DATA), perPage, sorter, this.props.highlight);
	}

	handleUpdateData = (currentData) => { this.setState({ currentData }) }

	handleUpdateList = (element, context) => {
		const { data, dataImmuable, currentData, sorter } = this.state;
		List.updateListPagination(this, element, context, data, dataImmuable, currentData, sorter)
	}

	handleChangeCurrentPage = (currentPage) => { this.setState({ currentPage }); }

	handleModal = (identifiant, elem) => {
		this[identifiant].current.handleClick();
		this.setState({ element: elem })
	}

	render () {
		const { highlight } = this.props;
		const { data, currentData, element, loadingData, perPage, currentPage } = this.state;

		return <>
			{loadingData
				? <LoaderElements />
				: <>
					<OrdersList data={currentData} highlight={parseInt(highlight)} onDelete={this.handleModal} />

					<Pagination ref={this.pagination} items={data} taille={data.length} currentPage={currentPage}
								perPage={perPage} onUpdate={this.handleUpdateData} onChangeCurrentPage={this.handleChangeCurrentPage}/>
				</>
			}
		</>
	}
}
