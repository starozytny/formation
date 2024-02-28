import React, { Component } from "react";
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import axios from "axios";

import Sort from "@commonFunctions/sort";
import List from "@commonFunctions/list";
import Formulaire from "@commonFunctions/formulaire";

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
		const { highlight } = this.props;
		const { perPage, sorter } = this.state;

		const self = this;
		axios({ method: "GET", url: Routing.generate(URL_GET_DATA), data: {} })
			.then(function (response) {

				let orders = JSON.parse(response.data.orders);
				let participants = JSON.parse(response.data.participants);

				orders.forEach(order => {
					let workers = [];
					participants.forEach(p => {
						if(p.foOrder.id === order.id){
							workers.push(p);
						}
					})

					order.participants = workers
				})

				let data = orders;
				let dataImmuable = orders;

				if(sorter) data.sort(sorter);
				if(sorter) dataImmuable.sort(sorter);

				let [currentData, currentPage] = List.setCurrentPage(highlight, data, perPage, 'id');

				self.setState({ data: data, dataImmuable: dataImmuable, currentData: currentData, currentPage: currentPage, loadingData: false })
			})
			.catch(function (error) { Formulaire.displayErrors(self, error); })
		;
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
